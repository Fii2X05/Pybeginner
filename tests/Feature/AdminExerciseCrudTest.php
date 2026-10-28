<?php

namespace Tests\Feature;

use App\Models\Exercise;
use App\Models\TestCase as ExerciseTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminExerciseCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.latihan.create'))->assertRedirect(route('login'));
        $this->post(route('admin.latihan.store'), [])->assertRedirect(route('login'));
    }

    public function test_learner_cannot_access_exercise_form_or_store(): void
    {
        $this->actingAs($this->makeUser('learner'));

        $this->get(route('admin.latihan.create'))->assertForbidden();
        $this->post(route('admin.latihan.store'), $this->validPayload(1))->assertForbidden();
        $this->assertDatabaseCount('exercises', 0);
    }

    public function test_admin_sees_create_form_with_lessons(): void
    {
        $this->createLesson();
        $this->actingAs($this->makeUser('admin'));

        $this->get(route('admin.latihan.create'))
            ->assertOk()
            ->assertSee('Tambah Latihan')
            ->assertSee('Fungsi')
            ->assertSee('Simpan &amp; Lanjut ke Test Case', false)
            ->assertDontSee('Simpan &amp; Terbitkan', false);
    }

    public function test_form_shows_message_when_no_lessons_exist(): void
    {
        $this->actingAs($this->makeUser('admin'));

        $this->get(route('admin.latihan.create'))
            ->assertOk()
            ->assertSee('Belum ada pelajaran')
            ->assertSee(route('admin.materi'))
            ->assertDontSee('Pilih pelajaran');
    }

    public function test_exercise_list_has_add_button(): void
    {
        $this->actingAs($this->makeUser('admin'));

        $this->get(route('admin.latihan'))
            ->assertOk()
            ->assertSee(route('admin.latihan.create'));
    }

    public function test_store_saves_exercise_as_draft(): void
    {
        $lessonId = $this->createLesson();
        $this->actingAs($this->makeUser('admin'));

        $this->post(route('admin.latihan.store'), $this->validPayload($lessonId))
            ->assertRedirectContains('/test-case')
            ->assertSessionHas('status');

        $this->assertDatabaseHas('exercises', [
            'lesson_id' => $lessonId,
            'title' => 'Jumlah Dua Bilangan',
            'slug' => 'jumlah-dua-bilangan',
            'difficulty' => 'easy',
            'time_limit_ms' => 2000,
            'memory_limit_mb' => 128,
            'is_published' => false,
        ]);
    }

    public function test_draft_exercise_is_hidden_from_learners(): void
    {
        $lessonId = $this->createLesson();
        $this->actingAs($this->makeUser('admin'));
        $this->post(route('admin.latihan.store'), $this->validPayload($lessonId));

        $this->actingAs($this->makeUser('learner'));

        $this->get(route('latihan.show', 'jumlah-dua-bilangan'))->assertNotFound();
        $this->get(route('latihan.index', ['level' => 'easy']))
            ->assertOk()
            ->assertDontSee('Jumlah Dua Bilangan');
    }

    public function test_draft_exercise_is_labelled_in_admin_list(): void
    {
        $lessonId = $this->createLesson();
        $this->actingAs($this->makeUser('admin'));
        $this->post(route('admin.latihan.store'), $this->validPayload($lessonId));

        $this->get(route('admin.latihan'))
            ->assertOk()
            ->assertSee('Jumlah Dua Bilangan')
            ->assertSee('Draft');
    }

    public function test_slug_is_generated_from_title_and_made_unique(): void
    {
        $lessonId = $this->createLesson();
        $this->actingAs($this->makeUser('admin'));

        $this->post(route('admin.latihan.store'), $this->validPayload($lessonId));
        $this->post(route('admin.latihan.store'), $this->validPayload($lessonId));
        $this->post(route('admin.latihan.store'), $this->validPayload($lessonId));

        $this->assertEqualsCanonicalizing(
            ['jumlah-dua-bilangan', 'jumlah-dua-bilangan-2', 'jumlah-dua-bilangan-3'],
            Exercise::pluck('slug')->all(),
        );
    }

    public function test_slug_must_be_unique_across_all_lessons(): void
    {
        $lessonA = $this->createLesson();
        $lessonB = $this->createLesson('Lain');
        $this->actingAs($this->makeUser('admin'));

        $this->post(route('admin.latihan.store'), $this->validPayload($lessonA, ['slug' => 'soal-satu']));
        $this->post(route('admin.latihan.store'), $this->validPayload($lessonB, ['slug' => 'soal-satu']))
            ->assertSessionHasErrors('slug');

        $this->assertDatabaseCount('exercises', 1);
    }

    public function test_slug_format_is_validated(): void
    {
        $lessonId = $this->createLesson();
        $this->actingAs($this->makeUser('admin'));

        $this->post(route('admin.latihan.store'), $this->validPayload($lessonId, ['slug' => 'Slug Salah!']))
            ->assertSessionHasErrors('slug');
    }

    public function test_required_fields_are_validated(): void
    {
        $this->createLesson();
        $this->actingAs($this->makeUser('admin'));

        $this->post(route('admin.latihan.store'), [])
            ->assertSessionHasErrors(['lesson_id', 'title', 'description', 'difficulty', 'time_limit_ms', 'memory_limit_mb']);

        $this->assertDatabaseCount('exercises', 0);
    }

    public function test_lesson_must_exist(): void
    {
        $this->actingAs($this->makeUser('admin'));

        $this->post(route('admin.latihan.store'), $this->validPayload(9999))
            ->assertSessionHasErrors('lesson_id');
    }

    public function test_difficulty_only_accepts_easy_medium_hard(): void
    {
        $lessonId = $this->createLesson();
        $this->actingAs($this->makeUser('admin'));

        foreach (['mudah', 'expert', 'EASY'] as $invalid) {
            $this->post(route('admin.latihan.store'), $this->validPayload($lessonId, ['difficulty' => $invalid]))
                ->assertSessionHasErrors('difficulty');
        }

        foreach (['easy', 'medium', 'hard'] as $valid) {
            $this->post(route('admin.latihan.store'), $this->validPayload($lessonId, ['difficulty' => $valid]))
                ->assertSessionDoesntHaveErrors();
        }

        $this->assertDatabaseCount('exercises', 3);
    }

    public function test_time_limit_must_be_within_range(): void
    {
        $lessonId = $this->createLesson();
        $this->actingAs($this->makeUser('admin'));

        foreach ([999, 5001, 'abc'] as $invalid) {
            $this->post(route('admin.latihan.store'), $this->validPayload($lessonId, ['time_limit_ms' => $invalid]))
                ->assertSessionHasErrors('time_limit_ms');
        }

        foreach ([1000, 5000] as $valid) {
            $this->post(route('admin.latihan.store'), $this->validPayload($lessonId, ['time_limit_ms' => $valid]))
                ->assertSessionDoesntHaveErrors();
        }
    }

    public function test_memory_limit_must_be_within_range(): void
    {
        $lessonId = $this->createLesson();
        $this->actingAs($this->makeUser('admin'));

        foreach ([15, 251, 'abc'] as $invalid) {
            $this->post(route('admin.latihan.store'), $this->validPayload($lessonId, ['memory_limit_mb' => $invalid]))
                ->assertSessionHasErrors('memory_limit_mb');
        }

        foreach ([16, 250] as $valid) {
            $this->post(route('admin.latihan.store'), $this->validPayload($lessonId, ['memory_limit_mb' => $valid]))
                ->assertSessionDoesntHaveErrors();
        }
    }

    public function test_text_line_endings_are_normalized(): void
    {
        $lessonId = $this->createLesson();
        $this->actingAs($this->makeUser('admin'));

        $this->post(route('admin.latihan.store'), $this->validPayload($lessonId, [
            'description' => "Baris satu\r\nBaris dua",
            'starter_code' => "def jumlah(a, b):\r\n    pass\r\n\r\n",
        ]));

        $exercise = Exercise::firstOrFail();

        $this->assertSame("Baris satu\nBaris dua", $exercise->description);
        $this->assertSame("def jumlah(a, b):\n    pass\n", $exercise->starter_code);
    }

    public function test_sort_order_defaults_to_next_number(): void
    {
        $lessonId = $this->createLesson();
        $this->actingAs($this->makeUser('admin'));

        $this->post(route('admin.latihan.store'), $this->validPayload($lessonId, ['title' => 'Satu']));
        $this->post(route('admin.latihan.store'), $this->validPayload($lessonId, ['title' => 'Dua']));
        $this->post(route('admin.latihan.store'), $this->validPayload($lessonId, ['title' => 'Tiga', 'sort_order' => 10]));

        $this->assertSame(1, Exercise::where('title', 'Satu')->value('sort_order'));
        $this->assertSame(2, Exercise::where('title', 'Dua')->value('sort_order'));
        $this->assertSame(10, Exercise::where('title', 'Tiga')->value('sort_order'));
    }

    public function test_old_input_is_kept_when_validation_fails(): void
    {
        $lessonId = $this->createLesson();
        $this->actingAs($this->makeUser('admin'));

        $this->from(route('admin.latihan.create'))
            ->post(route('admin.latihan.store'), $this->validPayload($lessonId, ['time_limit_ms' => 9999]))
            ->assertRedirect(route('admin.latihan.create'));

        $this->get(route('admin.latihan.create'))
            ->assertOk()
            ->assertSee('Jumlah Dua Bilangan');
    }

    public function test_store_always_saves_as_draft_even_if_publish_is_requested(): void
    {
        $lessonId = $this->createLesson();
        $this->actingAs($this->makeUser('admin'));

        $this->post(route('admin.latihan.store'), $this->validPayload($lessonId, ['is_published' => 1]))
            ->assertRedirectContains('/test-case')
            ->assertSessionHas('status');

        $this->assertDatabaseHas('exercises', ['slug' => 'jumlah-dua-bilangan', 'is_published' => false]);

        $this->actingAs($this->makeUser('learner'));
        $this->get(route('latihan.index', ['level' => 'easy']))
            ->assertOk()
            ->assertDontSee('Jumlah Dua Bilangan');
    }

    public function test_store_redirects_to_the_test_case_page_of_the_new_exercise(): void
    {
        $lessonId = $this->createLesson();
        $this->actingAs($this->makeUser('admin'));

        $this->post(route('admin.latihan.store'), $this->validPayload($lessonId))
            ->assertRedirect(route('admin.latihan.test-cases', Exercise::firstOrFail()));
    }

    public function test_admin_sees_edit_form_with_existing_values(): void
    {
        $exercise = $this->makeExercise(['title' => 'Soal Lama']);
        $this->actingAs($this->makeUser('admin'));

        $this->get(route('admin.latihan.edit', $exercise))
            ->assertOk()
            ->assertSee('Edit Latihan')
            ->assertSee('Soal Lama')
            ->assertSee('Simpan sebagai Draft')
            ->assertSee('Simpan &amp; Terbitkan', false);
    }

    public function test_edit_form_clamps_legacy_limits_into_allowed_range(): void
    {
        $exercise = $this->makeExercise(['time_limit_ms' => 10000]);
        $this->actingAs($this->makeUser('admin'));

        $this->get(route('admin.latihan.edit', $exercise))
            ->assertOk()
            ->assertSee('value="5000"', false);
    }

    public function test_update_changes_exercise_fields(): void
    {
        $exercise = $this->makeExercise();
        $this->addTestCase($exercise);
        $this->actingAs($this->makeUser('admin'));

        $this->put(route('admin.latihan.update', $exercise), $this->validPayload($exercise->lesson_id, [
            'title' => 'Judul Baru',
            'slug' => 'judul-baru',
            'difficulty' => 'hard',
            'time_limit_ms' => 3000,
            'memory_limit_mb' => 64,
            'sort_order' => 7,
            'is_published' => 1,
        ]))->assertRedirect(route('admin.latihan'))->assertSessionHas('status');

        $this->assertDatabaseHas('exercises', [
            'id' => $exercise->id,
            'title' => 'Judul Baru',
            'slug' => 'judul-baru',
            'difficulty' => 'hard',
            'time_limit_ms' => 3000,
            'memory_limit_mb' => 64,
            'sort_order' => 7,
            'is_published' => true,
        ]);
    }

    public function test_update_keeps_existing_slug_when_slug_is_blank(): void
    {
        $exercise = $this->makeExercise(['slug' => 'slug-lama']);
        $this->actingAs($this->makeUser('admin'));

        $this->put(route('admin.latihan.update', $exercise), $this->validPayload($exercise->lesson_id, [
            'title' => 'Judul Berubah',
            'slug' => '',
        ]));

        $this->assertSame('slug-lama', $exercise->fresh()->slug);
    }

    public function test_update_allows_own_slug_but_rejects_slug_of_another_exercise(): void
    {
        $first = $this->makeExercise(['slug' => 'soal-satu']);
        $second = $this->makeExercise(['slug' => 'soal-dua', 'lesson_id' => $first->lesson_id]);
        $this->actingAs($this->makeUser('admin'));

        $this->put(route('admin.latihan.update', $first), $this->validPayload($first->lesson_id, ['slug' => 'soal-satu']))
            ->assertSessionDoesntHaveErrors();

        $this->put(route('admin.latihan.update', $second), $this->validPayload($second->lesson_id, ['slug' => 'soal-satu']))
            ->assertSessionHasErrors('slug');

        $this->assertSame('soal-dua', $second->fresh()->slug);
    }

    public function test_update_validates_input(): void
    {
        $exercise = $this->makeExercise();
        $this->actingAs($this->makeUser('admin'));

        $this->put(route('admin.latihan.update', $exercise), $this->validPayload($exercise->lesson_id, [
            'title' => '',
            'difficulty' => 'mudah',
            'time_limit_ms' => 10,
        ]))->assertSessionHasErrors(['title', 'difficulty', 'time_limit_ms']);
    }

    public function test_update_can_unpublish_exercise(): void
    {
        $exercise = $this->makeExercise(['is_published' => true]);
        $this->actingAs($this->makeUser('admin'));

        $this->put(route('admin.latihan.update', $exercise), $this->validPayload($exercise->lesson_id, ['is_published' => 0]));

        $this->assertFalse($exercise->fresh()->is_published);
    }

    public function test_status_can_be_toggled_from_the_list(): void
    {
        $exercise = $this->makeExercise();
        $this->addTestCase($exercise);
        $this->actingAs($this->makeUser('admin'));

        $this->put(route('admin.latihan.status.update', $exercise), ['is_published' => 1])
            ->assertRedirect()
            ->assertSessionHas('status');
        $this->assertTrue($exercise->fresh()->is_published);

        $this->put(route('admin.latihan.status.update', $exercise), ['is_published' => 0]);
        $this->assertFalse($exercise->fresh()->is_published);

        $this->put(route('admin.latihan.status.update', $exercise), ['is_published' => 'bukan-boolean'])
            ->assertSessionHasErrors('is_published');
    }

    public function test_destroy_deletes_exercise_and_its_test_cases(): void
    {
        $exercise = $this->makeExercise();
        $this->addTestCase($exercise);
        $this->actingAs($this->makeUser('admin'));

        $this->delete(route('admin.latihan.destroy', $exercise))
            ->assertRedirect(route('admin.latihan'))
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('exercises', ['id' => $exercise->id]);
        $this->assertDatabaseCount('test_cases', 0);
    }

    public function test_exercise_list_shows_edit_delete_and_status_controls(): void
    {
        $exercise = $this->makeExercise(['title' => 'Soal Daftar']);
        $this->actingAs($this->makeUser('admin'));

        $this->get(route('admin.latihan'))
            ->assertOk()
            ->assertSee('Soal Daftar')
            ->assertSee(route('admin.latihan.edit', $exercise))
            ->assertSee(route('admin.latihan.destroy', $exercise))
            ->assertSee(route('admin.latihan.status.update', $exercise));
    }

    public function test_guest_and_learner_cannot_edit_update_or_delete(): void
    {
        $exercise = $this->makeExercise();
        $payload = $this->validPayload($exercise->lesson_id, ['title' => 'Diretas']);

        $this->get(route('admin.latihan.edit', $exercise))->assertRedirect(route('login'));
        $this->put(route('admin.latihan.update', $exercise), $payload)->assertRedirect(route('login'));
        $this->delete(route('admin.latihan.destroy', $exercise))->assertRedirect(route('login'));

        $this->actingAs($this->makeUser('learner'));

        $this->get(route('admin.latihan.edit', $exercise))->assertForbidden();
        $this->put(route('admin.latihan.update', $exercise), $payload)->assertForbidden();
        $this->put(route('admin.latihan.status.update', $exercise), ['is_published' => 1])->assertForbidden();
        $this->delete(route('admin.latihan.destroy', $exercise))->assertForbidden();

        $this->assertDatabaseHas('exercises', ['id' => $exercise->id, 'title' => 'Jumlah']);
    }

    public function test_exercise_cannot_be_published_without_an_active_visible_test_case(): void
    {
        $this->actingAs($this->makeUser('admin'));

        $scenarios = [
            'tanpa test case' => null,
            'hanya tersembunyi' => ['is_hidden' => true],
            'hanya nonaktif' => ['is_active' => false],
        ];

        foreach ($scenarios as $label => $testCaseOverrides) {
            $exercise = $this->makeExercise();

            if ($testCaseOverrides !== null) {
                $this->addTestCase($exercise, $testCaseOverrides);
            }

            // Dropdown status di daftar: ditolak dengan pesan error.
            $this->put(route('admin.latihan.status.update', $exercise), ['is_published' => 1])
                ->assertSessionHasErrors('is_published');

            $this->assertFalse($exercise->fresh()->is_published, $label);

            // Form edit: disimpan sebagai draft, lalu diarahkan ke halaman test case.
            $this->put(route('admin.latihan.update', $exercise), $this->validPayload($exercise->lesson_id, ['is_published' => 1]))
                ->assertRedirect(route('admin.latihan.test-cases', $exercise));

            $this->assertFalse($exercise->fresh()->is_published, $label);
        }
    }

    public function test_publish_from_edit_form_without_test_case_saves_changes_as_draft_and_opens_test_cases(): void
    {
        $exercise = $this->makeExercise(['title' => 'Judul Lama']);
        $this->actingAs($this->makeUser('admin'));

        $this->put(route('admin.latihan.update', $exercise), $this->validPayload($exercise->lesson_id, [
            'title' => 'Judul Baru',
            'is_published' => 1,
        ]))
            ->assertRedirect(route('admin.latihan.test-cases', $exercise))
            ->assertSessionHas('status', fn (string $status): bool => str_contains($status, 'test case'));

        $fresh = $exercise->fresh();
        $this->assertSame('Judul Baru', $fresh->title);
        $this->assertFalse($fresh->is_published);
    }

    public function test_exercise_can_be_published_with_an_active_visible_test_case(): void
    {
        $exercise = $this->makeExercise();
        $this->addTestCase($exercise);
        $this->actingAs($this->makeUser('admin'));

        $this->put(route('admin.latihan.update', $exercise), $this->validPayload($exercise->lesson_id, ['is_published' => 1]))
            ->assertRedirect(route('admin.latihan'))
            ->assertSessionDoesntHaveErrors();

        $this->assertTrue($exercise->fresh()->is_published);
    }

    public function test_update_without_status_field_keeps_exercise_published(): void
    {
        $exercise = $this->makeExercise(['is_published' => true]);
        $this->addTestCase($exercise);
        $this->actingAs($this->makeUser('admin'));

        $payload = $this->validPayload($exercise->lesson_id, ['title' => 'Judul Diubah']);
        unset($payload['is_published']);

        $this->put(route('admin.latihan.update', $exercise), $payload)
            ->assertRedirect(route('admin.latihan'));

        $fresh = $exercise->fresh();
        $this->assertSame('Judul Diubah', $fresh->title);
        $this->assertTrue($fresh->is_published);
    }

    public function test_exercise_can_always_be_saved_as_draft_without_test_cases(): void
    {
        $exercise = $this->makeExercise(['is_published' => true]);
        $this->actingAs($this->makeUser('admin'));

        $this->put(route('admin.latihan.update', $exercise), $this->validPayload($exercise->lesson_id, ['is_published' => 0]))
            ->assertSessionDoesntHaveErrors();

        $this->assertFalse($exercise->fresh()->is_published);
    }

    public function test_edit_form_explains_why_publish_is_unavailable(): void
    {
        $exercise = $this->makeExercise();
        $this->actingAs($this->makeUser('admin'));

        $this->get(route('admin.latihan.edit', $exercise))
            ->assertOk()
            ->assertSee('Soal ini belum bisa diterbitkan karena belum punya test case aktif yang terlihat')
            ->assertSee('Simpan &amp; Terbitkan', false);

        $this->addTestCase($exercise);

        $this->get(route('admin.latihan.edit', $exercise))
            ->assertOk()
            ->assertDontSee('Soal ini belum bisa diterbitkan karena belum punya test case aktif yang terlihat');
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function addTestCase(Exercise $exercise, array $overrides = []): ExerciseTestCase
    {
        return ExerciseTestCase::create(array_merge([
            'exercise_id' => $exercise->id,
            'input' => '1',
            'expected_output' => '1',
            'weight' => 1,
            'is_hidden' => false,
            'is_active' => true,
            'sort_order' => 1,
        ], $overrides));
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function makeExercise(array $overrides = []): Exercise
    {
        return Exercise::create(array_merge([
            'lesson_id' => $this->createLesson(),
            'title' => 'Jumlah',
            'slug' => 'jumlah-'.uniqid(),
            'description' => 'Jumlahkan dua bilangan.',
            'instructions' => null,
            'starter_code' => null,
            'difficulty' => 'easy',
            'time_limit_ms' => 2000,
            'memory_limit_mb' => 128,
            'sort_order' => 1,
            'is_published' => false,
        ], $overrides));
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(int $lessonId, array $overrides = []): array
    {
        return array_merge([
            'lesson_id' => $lessonId,
            'title' => 'Jumlah Dua Bilangan',
            'slug' => '',
            'description' => 'Jumlahkan dua bilangan.',
            'instructions' => 'Cetak hasilnya.',
            'starter_code' => 'print(1 + 1)',
            'difficulty' => 'easy',
            'time_limit_ms' => 2000,
            'memory_limit_mb' => 128,
            'sort_order' => '',
        ], $overrides);
    }

    private function makeUser(string $role): User
    {
        return User::factory()->create(['role' => $role]);
    }

    private function createLesson(string $title = 'Fungsi'): int
    {
        $now = now();
        $uid = uniqid();

        $courseId = DB::table('courses')->insertGetId([
            'title' => 'Python',
            'slug' => 'python-'.$uid,
            'description' => 'Test course',
            'level' => 'beginner',
            'is_published' => true,
            'sort_order' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $moduleId = DB::table('modules')->insertGetId([
            'course_id' => $courseId,
            'title' => 'Dasar '.$uid,
            'slug' => 'dasar-'.$uid,
            'description' => 'Test module',
            'sort_order' => 1,
            'is_published' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return DB::table('lessons')->insertGetId([
            'module_id' => $moduleId,
            'title' => $title,
            'slug' => 'pelajaran-'.$uid,
            'content' => 'Test lesson',
            'sort_order' => 1,
            'is_published' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}