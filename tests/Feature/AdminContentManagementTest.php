<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Submission;
use App\Models\TestCase as TestCaseModel;
use App\Models\User;
use Database\Seeders\ModulSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminContentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ModulSeeder::class);
    }

    public function test_admin_can_create_exercise_and_choose_assertion_or_input_output_test_cases(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $lesson = Lesson::firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.latihan.create'))
            ->assertOk()
            ->assertSee('Buat Latihan');

        $this->post(route('admin.latihan.store'), [
            'lesson_id' => $lesson->id,
            'title' => 'Uji Fungsi',
            'description' => 'Latihan untuk menguji fungsi siswa.',
            'difficulty' => 'easy',
            'time_limit_ms' => 10000,
            'memory_limit_mb' => 128,
        ])->assertRedirect();

        $exercise = Exercise::where('title', 'Uji Fungsi')->firstOrFail();
        $this->assertSame('uji-fungsi', $exercise->slug);
        $this->assertFalse($exercise->is_published);

        $this->post(route('admin.latihan.test-cases.store', $exercise), [
            'mode' => 'assertion',
            'assertion_code' => "assert jumlah(2, 3) == 5\r\n",
            'weight' => 2,
            'is_hidden' => 0,
        ])->assertRedirect();

        $assertion = TestCaseModel::where('exercise_id', $exercise->id)->firstOrFail();
        $this->assertSame('assert jumlah(2, 3) == 5', $assertion->assertion_code);
        $this->assertSame('', $assertion->expected_output);
        $this->assertNull($assertion->input);

        $this->post(route('admin.latihan.test-cases.store', $exercise), [
            'mode' => 'input_output',
            'input' => "2\r\n3",
            'expected_output' => '5',
            'weight' => 1,
            'is_hidden' => 0,
        ])->assertRedirect();

        $ioCase = TestCaseModel::where('exercise_id', $exercise->id)->orderByDesc('id')->firstOrFail();
        $this->assertNull($ioCase->assertion_code);
        $this->assertSame("2\n3", $ioCase->input);
        $this->assertSame('5', $ioCase->expected_output);
    }

    public function test_published_exercise_requires_a_public_active_test_case(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $exercise = $this->createExercise();

        $this->actingAs($admin)
            ->put(route('admin.latihan.status.update', $exercise), ['is_published' => true])
            ->assertSessionHasErrors('is_published');
        $this->assertFalse($exercise->fresh()->is_published);

        $exercise->testCases()->create([
            'expected_output' => 'ok',
            'weight' => 1,
            'is_hidden' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->put(route('admin.latihan.status.update', $exercise), ['is_published' => true])
            ->assertRedirect();
        $this->assertTrue($exercise->fresh()->is_published);

        $this->get(route('admin.latihan'))
            ->assertOk()
            ->assertSee('name="is_published"', false)
            ->assertSee('Terbit')
            ->assertSee('Draft');

        $this->put(route('admin.latihan.status.update', $exercise), ['is_published' => '0'])
            ->assertRedirect();
        $this->assertFalse($exercise->fresh()->is_published);
    }

    public function test_admin_can_edit_existing_test_case_without_leaving_stale_mode_values(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $exercise = $this->createExercise();
        $testCase = $exercise->testCases()->create([
            'input' => "1\n2",
            'expected_output' => '3',
            'weight' => 1,
            'is_hidden' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.test-cases.edit', $testCase))
            ->assertOk()
            ->assertSee('Input / Output');

        $this->put(route('admin.test-cases.update', $testCase), [
            'mode' => 'assertion',
            'assertion_code' => 'assert add(1, 2) == 3',
            'weight' => 4,
            'is_hidden' => 1,
        ])->assertRedirect();

        $this->assertSame('assert add(1, 2) == 3', $testCase->fresh()->assertion_code);
        $this->assertNull($testCase->fresh()->input);
        $this->assertSame('', $testCase->fresh()->expected_output);
    }

    public function test_admin_can_manage_modules_and_lessons_without_cascading_student_history(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = Course::firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.materi'))
            ->assertOk()
            ->assertSee('Tambah Modul')
            ->assertDontSee('<dialog', false);

        $this->get(route('admin.materi.create'))
            ->assertOk()
            ->assertSee('Tambah Modul')
            ->assertSee('Atur informasi modul');

        $this->post(route('admin.materi.store'), [
            'course_id' => $course->id,
            'title' => 'Modul Baru',
            'description' => 'Materi percobaan.',
            'sort_order' => 99,
            'is_published' => 0,
        ])->assertRedirect();

        $module = Module::where('title', 'Modul Baru')->firstOrFail();
        $this->get(route('admin.materi.edit', $module))
            ->assertOk()
            ->assertSee('Edit Modul')
            ->assertDontSee('<dialog', false);
        $this->get(route('admin.materi.lessons', $module))
            ->assertOk()
            ->assertSee('Tambah Pelajaran');

        $this->post(route('admin.materi.lessons.store', $module), [
            'title' => 'Pelajaran Baru',
            'content' => "# Materi\n\nIsi pelajaran.",
            'sort_order' => 1,
            'is_published' => 0,
        ])->assertRedirect();

        $lesson = $module->lessons()->where('title', 'Pelajaran Baru')->firstOrFail();
        $this->get(route('admin.lessons.edit', $lesson))
            ->assertOk()
            ->assertSee('Isi materi');

        $this->put(route('admin.lessons.update', $lesson), [
            'title' => 'Pelajaran Diperbarui',
            'content' => 'Materi diperbarui.',
            'sort_order' => 2,
            'is_published' => 1,
        ])->assertRedirect();

        $this->assertSame('pelajaran-baru', $lesson->fresh()->slug);
        $this->assertSame('Pelajaran Diperbarui', $lesson->fresh()->title);

        $this->delete(route('admin.materi.destroy', $module))
            ->assertSessionHasErrors('module');
        $this->assertDatabaseHas('modules', ['id' => $module->id]);
    }

    public function test_exercises_and_test_cases_with_student_history_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'learner']);
        $exercise = $this->createExercise();
        $testCase = $exercise->testCases()->create([
            'expected_output' => 'ok',
            'weight' => 1,
            'is_hidden' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);
        $submission = Submission::create([
            'user_id' => $student->id,
            'exercise_id' => $exercise->id,
            'source_code' => 'print("ok")',
            'status' => 'accepted',
            'score' => 100,
            'passed_tests' => 1,
            'total_tests' => 1,
            'submitted_at' => now(),
        ]);
        DB::table('submission_results')->insert([
            'submission_id' => $submission->id,
            'test_case_id' => $testCase->id,
            'status' => 'passed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.latihan.destroy', $exercise))
            ->assertSessionHasErrors('exercise');
        $this->delete(route('admin.test-cases.destroy', $testCase))
            ->assertSessionHasErrors('test_case');

        $this->assertDatabaseHas('exercises', ['id' => $exercise->id]);
        $this->assertDatabaseHas('test_cases', ['id' => $testCase->id]);
        $this->assertDatabaseHas('submissions', ['id' => $submission->id]);
    }

    public function test_non_admin_cannot_manage_modules_or_exercises(): void
    {
        $learner = User::factory()->create(['role' => 'learner']);

        $this->actingAs($learner)
            ->get(route('admin.latihan.create'))
            ->assertForbidden();
        $this->get(route('admin.materi'))
            ->assertForbidden();
    }

    private function createExercise(): Exercise
    {
        return Exercise::create([
            'lesson_id' => Lesson::query()->value('id'),
            'title' => 'Soal Test',
            'slug' => 'soal-test-'.uniqid(),
            'description' => 'Deskripsi soal test.',
            'difficulty' => 'easy',
            'time_limit_ms' => 10000,
            'memory_limit_mb' => 128,
            'sort_order' => 1,
            'is_published' => false,
        ]);
    }
}
