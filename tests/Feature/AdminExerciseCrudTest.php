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

    public function test_guest_and_learner_cannot_manage_test_cases(): void
    {
        $exercise = $this->makeExercise();
        $testCase = $this->makeTestCase($exercise);

        $this->get(route('admin.latihan.test-cases', $exercise))->assertRedirect(route('login'));
        $this->post(route('admin.latihan.test-cases.store', $exercise), $this->validPayload())->assertRedirect(route('login'));
        $this->delete(route('admin.test-cases.destroy', $testCase))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create(['role' => 'learner']));

        $this->get(route('admin.latihan.test-cases', $exercise))->assertForbidden();
        $this->get(route('admin.latihan.test-cases.create', $exercise))->assertForbidden();
        $this->post(route('admin.latihan.test-cases.store', $exercise), $this->validPayload())->assertForbidden();
        $this->get(route('admin.test-cases.edit', $testCase))->assertForbidden();
        $this->put(route('admin.test-cases.update', $testCase), $this->validPayload())->assertForbidden();
        $this->delete(route('admin.test-cases.destroy', $testCase))->assertForbidden();

        $this->assertDatabaseCount('test_cases', 1);
    }

    public function test_list_shows_warning_when_exercise_has_no_test_cases(): void
    {
        $exercise = $this->makeExercise();
        $this->actingAs($this->makeAdmin());

        $this->get(route('admin.latihan.test-cases', $exercise))
            ->assertOk()
            ->assertSee('belum punya test case')
            ->assertSee(route('admin.latihan.test-cases.create', $exercise));
    }

    public function test_list_shows_test_cases_and_warns_when_all_are_hidden(): void
    {
        $exercise = $this->makeExercise();
        $this->makeTestCase($exercise, ['input' => '3', 'expected_output' => 'KELUARAN-RAHASIA', 'is_hidden' => true]);
        $this->actingAs($this->makeAdmin());

        $this->get(route('admin.latihan.test-cases', $exercise))
            ->assertOk()
            ->assertSee('KELUARAN-RAHASIA')
            ->assertSee('Tersembunyi')
            ->assertSee('Semua test case tersembunyi');
    }

    public function test_exercise_list_links_to_test_cases_with_count(): void
    {
        $exercise = $this->makeExercise();
        $this->makeTestCase($exercise);
        $this->makeTestCase($exercise);
        $this->actingAs($this->makeAdmin());

        $this->get(route('admin.latihan'))
            ->assertOk()
            ->assertSee(route('admin.latihan.test-cases', $exercise))
            ->assertSee('Test Case (2)');
    }

    public function test_admin_sees_create_form(): void
    {
        $exercise = $this->makeExercise();
        $this->actingAs($this->makeAdmin());

        $this->get(route('admin.latihan.test-cases.create', $exercise))
            ->assertOk()
            ->assertSee('Tambah Test Case')
            ->assertSee('Simpan Test Case');
    }

    public function test_store_saves_test_case_with_defaults(): void
    {
        $exercise = $this->makeExercise();
        $this->actingAs($this->makeAdmin());

        $this->post(route('admin.latihan.test-cases.store', $exercise), $this->validPayload())
            ->assertRedirect(route('admin.latihan.test-cases', $exercise))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('test_cases', [
            'exercise_id' => $exercise->id,
            'input' => "2\n3",
            'expected_output' => '5',
            'weight' => 1,
            'is_hidden' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    public function test_store_normalizes_line_endings_and_can_hide_test_case(): void
    {
        $exercise = $this->makeExercise();
        $this->actingAs($this->makeAdmin());

        $this->post(route('admin.latihan.test-cases.store', $exercise), $this->validPayload([
            'input' => "2\r\n3",
            'is_hidden' => 1,
        ]));

        $testCase = ExerciseTestCase::firstOrFail();

        $this->assertSame("2\n3", $testCase->input);
        $this->assertTrue($testCase->is_hidden);
    }

    public function test_store_assigns_next_sort_order_per_exercise(): void
    {
        $exercise = $this->makeExercise();
        $other = $this->makeExercise();
        $this->makeTestCase($other, ['sort_order' => 9]);
        $this->actingAs($this->makeAdmin());

        $this->post(route('admin.latihan.test-cases.store', $exercise), $this->validPayload());
        $this->post(route('admin.latihan.test-cases.store', $exercise), $this->validPayload());

        $this->assertSame([1, 2], ExerciseTestCase::where('exercise_id', $exercise->id)->orderBy('sort_order')->pluck('sort_order')->all());
    }

    public function test_expected_output_is_required_unless_assertion_code_is_given(): void
    {
        $exercise = $this->makeExercise();
        $this->actingAs($this->makeAdmin());

        $this->post(route('admin.latihan.test-cases.store', $exercise), $this->validPayload(['expected_output' => '']))
            ->assertSessionHasErrors('expected_output');

        $this->post(route('admin.latihan.test-cases.store', $exercise), $this->validPayload([
            'expected_output' => '',
            'assertion_code' => 'assert jumlah(2, 3) == 5',
        ]))->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('test_cases', [
            'exercise_id' => $exercise->id,
            'assertion_code' => 'assert jumlah(2, 3) == 5',
            'expected_output' => '',
        ]);
    }

    public function test_weight_must_be_within_range(): void
    {
        $exercise = $this->makeExercise();
        $this->actingAs($this->makeAdmin());

        foreach ([0, 101, 'abc'] as $invalid) {
            $this->post(route('admin.latihan.test-cases.store', $exercise), $this->validPayload(['weight' => $invalid]))
                ->assertSessionHasErrors('weight');
        }

        $this->assertDatabaseCount('test_cases', 0);
    }

    public function test_admin_sees_edit_form_with_existing_values(): void
    {
        $testCase = $this->makeTestCase($this->makeExercise(), ['expected_output' => 'NILAI-LAMA']);
        $this->actingAs($this->makeAdmin());

        $this->get(route('admin.test-cases.edit', $testCase))
            ->assertOk()
            ->assertSee('Edit Test Case')
            ->assertSee('NILAI-LAMA');
    }

    public function test_update_changes_test_case(): void
    {
        $testCase = $this->makeTestCase($this->makeExercise());
        $this->actingAs($this->makeAdmin());

        $this->put(route('admin.test-cases.update', $testCase), $this->validPayload([
            'input' => '10',
            'expected_output' => '20',
            'weight' => 5,
            'is_hidden' => 1,
            'sort_order' => 4,
        ]))->assertRedirect(route('admin.latihan.test-cases', $testCase->exercise_id));

        $this->assertDatabaseHas('test_cases', [
            'id' => $testCase->id,
            'input' => '10',
            'expected_output' => '20',
            'weight' => 5,
            'is_hidden' => true,
            'sort_order' => 4,
        ]);
    }

    public function test_update_validates_input(): void
    {
        $testCase = $this->makeTestCase($this->makeExercise());
        $this->actingAs($this->makeAdmin());

        $this->put(route('admin.test-cases.update', $testCase), $this->validPayload(['expected_output' => '', 'weight' => 0]))
            ->assertSessionHasErrors(['expected_output', 'weight']);
    }

    public function test_destroy_deletes_only_that_test_case(): void
    {
        $exercise = $this->makeExercise();
        $first = $this->makeTestCase($exercise);
        $second = $this->makeTestCase($exercise);
        $this->actingAs($this->makeAdmin());

        $this->delete(route('admin.test-cases.destroy', $first))
            ->assertRedirect(route('admin.latihan.test-cases', $exercise))
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('test_cases', ['id' => $first->id]);
        $this->assertDatabaseHas('test_cases', ['id' => $second->id]);
    }

    public function test_learner_sees_visible_test_cases_but_not_hidden_ones(): void
    {
        $exercise = $this->makeExercise(['is_published' => true, 'slug' => 'soal-uji']);
        $this->makeTestCase($exercise, ['input' => '1', 'expected_output' => 'KELUARAN-CONTOH']);
        $this->makeTestCase($exercise, ['input' => '2', 'expected_output' => 'KELUARAN-TERSEMBUNYI', 'is_hidden' => true]);

        $this->actingAs(User::factory()->create(['role' => 'learner']))
            ->get(route('latihan.show', 'soal-uji'))
            ->assertOk()
            ->assertSee('KELUARAN-CONTOH')
            ->assertDontSee('KELUARAN-TERSEMBUNYI');
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'input' => "2\n3",
            'expected_output' => '5',
            'assertion_code' => '',
            'weight' => 1,
            'sort_order' => '',
        ], $overrides);
    }

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function makeTestCase(Exercise $exercise, array $overrides = []): ExerciseTestCase
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
        $now = now();
        $uid = uniqid();

        $courseId = DB::table('courses')->insertGetId([
            'title' => 'Python', 'slug' => 'python-'.$uid, 'description' => 'Test course',
            'level' => 'beginner', 'is_published' => true, 'sort_order' => 1,
            'created_at' => $now, 'updated_at' => $now,
        ]);
        $moduleId = DB::table('modules')->insertGetId([
            'course_id' => $courseId, 'title' => 'Dasar '.$uid, 'slug' => 'dasar-'.$uid,
            'description' => 'Test module', 'sort_order' => 1, 'is_published' => true,
            'created_at' => $now, 'updated_at' => $now,
        ]);
        $lessonId = DB::table('lessons')->insertGetId([
            'module_id' => $moduleId, 'title' => 'Fungsi', 'slug' => 'pelajaran-'.$uid,
            'content' => 'Test lesson', 'sort_order' => 1, 'is_published' => true,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        return Exercise::create(array_merge([
            'lesson_id' => $lessonId,
            'title' => 'Jumlah',
            'slug' => 'jumlah-'.$uid,
            'description' => 'Jumlahkan dua bilangan.',
            'difficulty' => 'easy',
            'time_limit_ms' => 2000,
            'memory_limit_mb' => 128,
            'sort_order' => 1,
            'is_published' => false,
        ], $overrides));
    }
}
