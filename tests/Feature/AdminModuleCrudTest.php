<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Module;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminModuleCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_guest_and_learner_cannot_manage_modules(): void
    {
        $module = $this->makeModule();

        $this->get(route('admin.materi'))->assertRedirect(route('login'));
        $this->post(route('admin.materi.store'), $this->validPayload())->assertRedirect(route('login'));
        $this->put(route('admin.materi.update', $module), $this->validUpdatePayload())->assertRedirect(route('login'));
        $this->delete(route('admin.materi.destroy', $module))->assertRedirect(route('login'));

        $this->actingAs($this->makeUser('learner'));

        $this->get(route('admin.materi'))->assertForbidden();
        $this->post(route('admin.materi.store'), $this->validPayload())->assertForbidden();
        $this->put(route('admin.materi.update', $module), $this->validUpdatePayload())->assertForbidden();
        $this->delete(route('admin.materi.destroy', $module))->assertForbidden();

        $this->assertDatabaseCount('modules', 1);
    }

    public function test_admin_can_view_module_list(): void
    {
        $this->makeModule(['title' => 'Modul untuk Daftar']);
        $this->actingAs($this->makeUser('admin'));

        $this->get(route('admin.materi'))
            ->assertOk()
            ->assertSee('Modul untuk Daftar');
    }

    public function test_admin_can_create_and_publish_a_module_with_its_first_lesson(): void
    {
        $this->actingAs($this->makeUser('admin'));

        $response = $this->post(route('admin.materi.store'), $this->validPayload([
            'is_published' => 1,
        ]));

        $module = Module::query()->where('title', 'Variabel Python')->firstOrFail();

        $response->assertRedirect(route('admin.materi.lessons', $module))->assertSessionHas('status');
        $this->assertDatabaseHas('modules', [
            'id' => $module->id,
            'course_id' => $module->course_id,
            'slug' => 'variabel-python',
            'title' => 'Variabel Python',
            'level' => 'Pemula',
            'description' => 'Pengenalan variabel.',
            'sort_order' => 1,
            'is_published' => true,
        ]);
        $this->assertDatabaseHas('lessons', [
            'module_id' => $module->id,
            'title' => 'Mengenal Variabel',
            'slug' => 'mengenal-variabel',
            'content' => 'Materi pengantar.',
            'sort_order' => 1,
            'is_published' => true,
        ]);
    }

    public function test_module_is_published_by_default_when_publish_option_is_omitted(): void
    {
        $this->actingAs($this->makeUser('admin'))
            ->post(route('admin.materi.store'), $this->validPayload());

        $module = Module::query()->where('title', 'Variabel Python')->firstOrFail();

        $this->assertDatabaseHas('modules', ['id' => $module->id, 'is_published' => true]);
        $this->assertDatabaseHas('lessons', [
            'module_id' => $module->id,
            'slug' => 'mengenal-variabel',
            'is_published' => true,
        ]);
    }

    public function test_module_creation_validates_required_fields_and_allowed_level(): void
    {
        $this->actingAs($this->makeUser('admin'));

        $this->post(route('admin.materi.store'), [])
            ->assertSessionHasErrors(['title', 'level', 'lesson_title', 'lesson_content']);
        $this->post(route('admin.materi.store'), $this->validPayload(['level' => 'Ahli']))
            ->assertSessionHasErrors('level');

        $this->assertDatabaseCount('modules', 0);
    }

    public function test_module_title_must_be_unique_within_course(): void
    {
        $this->actingAs($this->makeUser('admin'));
        $payload = $this->validPayload();

        $this->post(route('admin.materi.store'), $payload)->assertSessionHasNoErrors();
        $this->post(route('admin.materi.store'), $payload)->assertSessionHasErrors('title');

        $this->assertDatabaseCount('modules', 1);
    }

    public function test_admin_can_update_module_details_and_publication_status(): void
    {
        $module = $this->makeModule(['is_published' => false]);
        $this->actingAs($this->makeUser('admin'));

        $this->put(route('admin.materi.update', $module), $this->validUpdatePayload([
            'title' => 'Modul Diperbarui',
            'level' => 'Menengah',
            'description' => 'Deskripsi terbaru.',
            'sort_order' => 3,
            'is_published' => 1,
        ]))->assertRedirect()->assertSessionHas('status');

        $this->assertDatabaseHas('modules', [
            'id' => $module->id,
            'title' => 'Modul Diperbarui',
            'level' => 'Menengah',
            'description' => 'Deskripsi terbaru.',
            'sort_order' => 3,
            'is_published' => true,
        ]);
    }

    public function test_omitting_publish_option_changes_module_back_to_draft(): void
    {
        $module = $this->makeModule(['is_published' => true]);
        $this->actingAs($this->makeUser('admin'))
            ->put(route('admin.materi.update', $module), $this->validUpdatePayload())
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('modules', ['id' => $module->id, 'is_published' => false]);
    }

    public function test_module_update_validates_required_fields_and_values(): void
    {
        $module = $this->makeModule();
        $this->actingAs($this->makeUser('admin'));

        $this->put(route('admin.materi.update', $module), [])
            ->assertSessionHasErrors(['title', 'level', 'sort_order']);
        $this->put(route('admin.materi.update', $module), $this->validUpdatePayload([
            'level' => 'Ahli',
            'sort_order' => 0,
        ]))->assertSessionHasErrors(['level', 'sort_order']);

        $this->assertDatabaseHas('modules', ['id' => $module->id, 'title' => 'Modul Contoh']);
    }

    public function test_module_title_must_be_unique_when_updated(): void
    {
        $firstModule = $this->makeModule(['title' => 'Modul Pertama', 'slug' => 'modul-pertama']);
        $secondModule = $this->makeModule(['title' => 'Modul Kedua', 'slug' => 'modul-kedua']);
        $this->actingAs($this->makeUser('admin'));

        $this->put(route('admin.materi.update', $secondModule), $this->validUpdatePayload([
            'title' => $firstModule->title,
        ]))->assertSessionHasErrors('title');

        $this->assertDatabaseHas('modules', ['id' => $secondModule->id, 'title' => 'Modul Kedua']);
    }

    public function test_admin_can_update_module_level_from_list(): void
    {
        $module = $this->makeModule(['level' => 'Pemula']);
        $this->actingAs($this->makeUser('admin'));

        $this->put(route('admin.materi.level.update', $module), ['level' => 'Menengah Bawah'])
            ->assertRedirect()->assertSessionHas('status');

        $this->assertDatabaseHas('modules', ['id' => $module->id, 'level' => 'Menengah Bawah']);
        $this->put(route('admin.materi.level.update', $module), ['level' => 'Ahli'])
            ->assertSessionHasErrors('level');
    }

    public function test_admin_can_delete_module_and_its_lessons(): void
    {
        $module = $this->makeModule();
        $lesson = $module->lessons()->create([
            'title' => 'Materi Hapus',
            'slug' => 'materi-hapus',
            'content' => 'Konten.',
            'sort_order' => 1,
            'is_published' => false,
        ]);
        $this->actingAs($this->makeUser('admin'));

        $this->delete(route('admin.materi.destroy', $module))
            ->assertRedirect(route('admin.materi'))
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('modules', ['id' => $module->id]);
        $this->assertDatabaseMissing('lessons', ['id' => $lesson->id]);
    }

    private function makeUser(string $role): User
    {
        return User::factory()->create(['role' => $role]);
    }

    /** @param array<string, mixed> $attributes */
    private function makeModule(array $attributes = []): Module
    {
        $course = Course::query()->firstOrCreate(
            ['slug' => 'python-dasar'],
            ['title' => 'Python Dasar', 'is_published' => true, 'sort_order' => 1],
        );

        return Module::query()->create(array_merge([
            'course_id' => $course->id,
            'title' => 'Modul Contoh',
            'slug' => 'modul-contoh',
            'level' => 'Pemula',
            'description' => 'Deskripsi modul.',
            'sort_order' => 1,
            'is_published' => true,
        ], $attributes));
    }

    /** @param array<string, mixed> $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Variabel Python',
            'level' => 'Pemula',
            'description' => 'Pengenalan variabel.',
            'lesson_title' => 'Mengenal Variabel',
            'lesson_content' => 'Materi pengantar.',
        ], $overrides);
    }

    /** @param array<string, mixed> $overrides
     * @return array<string, mixed>
     */
    private function validUpdatePayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Modul Contoh',
            'level' => 'Pemula',
            'description' => 'Deskripsi diperbarui.',
            'sort_order' => 2,
        ], $overrides);
    }
}
