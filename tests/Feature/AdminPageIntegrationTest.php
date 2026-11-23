<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPageIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Memastikan pengunjung (guest) dialihkan ke halaman login saat mengakses halaman admin.
     */
    public function test_guest_is_redirected_to_login_when_accessing_admin_pages(): void
    {
        $routes = [
            'admin.dashboard',
            'admin.materi',
            'admin.latihan',
            'admin.siswa',
            'admin.penilaian',
            'admin.pengaturan',
        ];

        foreach ($routes as $routeName) {
            $this->get(route($routeName))->assertRedirect(route('login'));
        }
    }

    /**
     * Memastikan pengguna biasa (learner) mendapat HTTP 403 Forbidden saat mengakses halaman admin.
     */
    public function test_learner_is_forbidden_from_accessing_admin_pages(): void
    {
        $learner = User::factory()->create(['role' => 'learner']);

        $this->actingAs($learner)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($learner)->get(route('admin.siswa'))->assertForbidden();
        $this->actingAs($learner)->get(route('admin.pengaturan'))->assertForbidden();
    }

    /**
     * Memastikan admin dapat membuka dashboard dan melihat ringkasan jumlah siswa yang akurat.
     */
    public function test_admin_can_access_dashboard_and_view_accurate_summary_counts(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->count(3)->create(['role' => 'learner']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHas('totalSiswa', 3);
        $response->assertViewHasAll(['totalModul', 'totalLatihan', 'totalSubmissions', 'recentActivities']);
    }

    /**
     * Memastikan URL root panel admin (/admin) menampilkan halaman dashboard admin.
     */
    public function test_admin_root_url_loads_dashboard_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.index'));

        $response->assertOk();
        $response->assertViewIs('admin.dashboard');
    }

    /**
     * Memastikan admin dapat mengakses halaman pengelolaan materi dan latihan.
     */
    public function test_admin_can_access_materi_and_latihan_management_pages(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.materi'))
            ->assertOk()
            ->assertViewIs('admin.materi');

        $this->actingAs($admin)
            ->get(route('admin.latihan'))
            ->assertOk()
            ->assertViewIs('admin.latihan');
    }

    /**
     * Memastikan halaman daftar siswa hanya menampilkan pengguna dengan role learner.
     */
    public function test_admin_can_view_registered_students_list_excluding_admins(): void
    {
        $admin = User::factory()->create(['name' => 'Admin Utama', 'role' => 'admin']);
        $otherAdmin = User::factory()->create(['name' => 'Admin Kedua', 'role' => 'admin']);
        $studentOne = User::factory()->create(['name' => 'Budi Siswa', 'role' => 'learner']);
        $studentTwo = User::factory()->create(['name' => 'Siti Siswa', 'role' => 'learner']);

        $response = $this->actingAs($admin)->get(route('admin.siswa'));

        $response->assertOk();
        $response->assertViewIs('admin.siswa');
        $response->assertSee($studentOne->name);
        $response->assertSee($studentTwo->name);

        $students = $response->viewData('students');
        $studentIds = $students->pluck('id')->all();

        $this->assertContains($studentOne->id, $studentIds);
        $this->assertContains($studentTwo->id, $studentIds);
        $this->assertNotContains($admin->id, $studentIds);
        $this->assertNotContains($otherAdmin->id, $studentIds);
    }

    /**
     * Memastikan admin dapat mengakses halaman penilaian submission siswa.
     */
    public function test_admin_can_access_penilaian_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.penilaian'));

        $response->assertOk();
        $response->assertViewIs('admin.penilaian');
        $response->assertViewHas('submissions');
    }

    /**
     * Memastikan halaman admin memuat komponen konfirmasi logout.
     */
    public function test_admin_dashboard_includes_logout_confirmation_modal(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('id="logout-confirmation"', false);
        $response->assertSee('data-confirm-logout', false);
    }
}
