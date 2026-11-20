<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminStudentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_search_and_filter_students(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['name' => 'Alya Google', 'google_id' => 'google-aly']);
        User::factory()->create(['name' => 'Bima Email', 'email' => 'bima@example.com']);

        $response = $this->actingAs($admin)->get(route('admin.siswa', [
            'search' => 'aly',
            'registration' => 'google',
            'joined' => 'oldest',
        ]));

        $response->assertOk()
            ->assertSee('Alya Google')
            ->assertDontSee('Bima Email');
    }

    public function test_user_filters_can_be_combined_for_search_role_registration_and_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create([
            'name' => 'Rina Mentor',
            'email' => 'rina.mentor@example.com',
            'role' => 'mentor',
            'google_id' => 'google-rina',
            'is_suspended' => true,
        ]);
        User::factory()->create([
            'name' => 'Rina Learner',
            'role' => 'learner',
            'google_id' => 'google-rina-learner',
            'is_suspended' => true,
        ]);
        User::factory()->create([
            'name' => 'Rina Email Mentor',
            'role' => 'mentor',
            'is_suspended' => true,
        ]);
        User::factory()->create([
            'name' => 'Rina Active Mentor',
            'role' => 'mentor',
            'google_id' => 'google-rina-active',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.siswa', [
                'search' => 'rina',
                'role' => 'mentor',
                'registration' => 'google',
                'status' => 'suspended',
            ]))
            ->assertOk()
            ->assertSee('Rina Mentor')
            ->assertDontSee('Rina Learner')
            ->assertDontSee('Rina Email Mentor')
            ->assertDontSee('Rina Active Mentor');
    }

    public function test_user_pagination_preserves_only_validated_filters(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        for ($index = 1; $index <= 17; $index++) {
            User::factory()->create([
                'name' => sprintf('Page Mentor %02d', $index),
                'role' => 'mentor',
            ]);
        }

        $response = $this->actingAs($admin)->get(route('admin.siswa', [
            'search' => 'Page Mentor',
            'role' => 'mentor',
            'page' => 2,
            'tracking' => 'unvalidated-value',
        ]));

        $response->assertOk()
            ->assertSee('Page Mentor 16')
            ->assertSee('Page Mentor 17')
            ->assertDontSee('Page Mentor 01');

        preg_match_all('/href="([^"]*page=\d+[^"]*)"/', $response->getContent(), $matches);
        $paginationUrls = array_map(
            static fn (string $url): string => html_entity_decode($url, ENT_QUOTES),
            $matches[1],
        );
        $this->assertNotEmpty($paginationUrls, 'Pagination links should be rendered.');

        $firstPageQuery = null;
        foreach ($paginationUrls as $url) {
            parse_str((string) parse_url($url, PHP_URL_QUERY), $query);

            if (($query['page'] ?? null) === '1') {
                $firstPageQuery = $query;
                break;
            }
        }

        $this->assertNotNull($firstPageQuery, 'A link to the first results page should be rendered.');
        $this->assertSame('Page Mentor', $firstPageQuery['search'] ?? null);
        $this->assertSame('mentor', $firstPageQuery['role'] ?? null);
        $this->assertArrayNotHasKey('tracking', $firstPageQuery);
    }

    public function test_admin_user_management_lists_and_filters_all_roles(): void
    {
        $admin = User::factory()->create(['name' => 'Current Administrator', 'role' => 'admin']);
        User::factory()->create(['name' => 'Listed Administrator', 'role' => 'admin']);
        User::factory()->create(['name' => 'Learner User', 'role' => 'learner']);
        User::factory()->create(['name' => 'Mentor User', 'role' => 'mentor']);

        $this->actingAs($admin)
            ->get(route('admin.siswa'))
            ->assertOk()
            ->assertSee('Kelola Pengguna')
            ->assertSee('Listed Administrator')
            ->assertSee('Learner User')
            ->assertSee('Mentor User')
            ->assertSee('Semua role');

        $this->get(route('admin.siswa', ['role' => 'mentor']))
            ->assertOk()
            ->assertSee('Mentor User')
            ->assertDontSee('Learner User')
            ->assertDontSee('Listed Administrator');
    }

    public function test_admin_accounts_are_visible_but_cannot_be_modified_or_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $otherAdmin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->put(route('admin.siswa.role', $otherAdmin), ['role' => 'learner'])
            ->assertForbidden();
        $this->post(route('admin.siswa.suspension', $otherAdmin))
            ->assertForbidden();
        $this->delete(route('admin.siswa.destroy', $otherAdmin), ['confirm_name' => $otherAdmin->name])
            ->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $otherAdmin->id, 'role' => 'admin', 'is_suspended' => false]);
    }

    public function test_admin_can_view_student_details_change_role_and_suspend_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['name' => 'Student Detail']);

        $this->actingAs($admin)
            ->get(route('admin.siswa.show', $student))
            ->assertOk()
            ->assertSee('Student Detail')
            ->assertSee('Riwayat latihan terbaru');

        $this->put(route('admin.siswa.role', $student), ['role' => 'mentor'])
            ->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $student->id, 'role' => 'mentor']);

        $student->update(['role' => 'learner']);
        $this->post(route('admin.siswa.suspension', $student))->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $student->id, 'is_suspended' => true]);
    }

    public function test_suspended_students_cannot_log_in_or_continue_learning(): void
    {
        $student = User::factory()->create(['is_suspended' => true]);

        $this->post(route('login.store'), [
            'email' => $student->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->actingAs($student)
            ->get(route('modul.index'))
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_account_deletion_requires_the_exact_student_name(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['name' => 'Nadia Student']);

        $this->actingAs($admin)
            ->delete(route('admin.siswa.destroy', $student), ['confirm_name' => 'Nadia'])
            ->assertSessionHasErrors('confirm_name');
        $this->assertDatabaseHas('users', ['id' => $student->id]);

        $this->delete(route('admin.siswa.destroy', $student), ['confirm_name' => 'Nadia Student'])
            ->assertRedirect(route('admin.siswa'));
        $this->assertDatabaseMissing('users', ['id' => $student->id]);
    }

    public function test_non_admin_cannot_manage_students(): void
    {
        $student = User::factory()->create();

        $this->actingAs($student)
            ->get(route('admin.siswa'))
            ->assertForbidden();
    }
}
