<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Submission;
use App\Models\TestCase as ExerciseTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LearnerLearningFlowIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        config([
            'services.judge0.url' => 'http://judge0.test',
            'services.judge0.language_id' => 10,
        ]);
    }

    public function test_admin_content_flows_through_learner_submission_grading_and_history(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $learner = User::factory()->create(['role' => 'learner']);

        $this->actingAs($admin)
            ->post(route('admin.materi.store'), [
                'title' => 'Modul Integrasi',
                'level' => 'Pemula',
                'description' => 'Materi integrasi.',
                'lesson_title' => 'Lesson Integrasi',
                'lesson_content' => 'Konten lesson untuk integrasi.',
                'is_published' => true,
            ])->assertRedirect();

        $module = Module::query()->where('title', 'Modul Integrasi')->firstOrFail();
        $course = Course::query()->findOrFail($module->course_id);
        $lesson = Lesson::query()->where('module_id', $module->id)->firstOrFail();

        $this->post(route('admin.latihan.store'), [
            'lesson_id' => $lesson->id,
            'title' => 'Latihan Integrasi',
            'description' => 'Jumlahkan dua bilangan.',
            'instructions' => 'Cetak hasil penjumlahan.',
            'starter_code' => "a = int(input())\nb = int(input())\nprint(a + b)",
            'difficulty' => 'easy',
            'time_limit_ms' => 2000,
            'memory_limit_mb' => 128,
        ])->assertRedirect();

        $exercise = Exercise::query()->where('title', 'Latihan Integrasi')->firstOrFail();

        $this->post(route('admin.latihan.test-cases.store', $exercise), [
            'jenis' => 'io',
            'input' => "2\n3",
            'expected_output' => '5',
            'assertion_code' => '',
            'weight' => 1,
            'sort_order' => 1,
        ])->assertRedirect();

        $testCase = ExerciseTestCase::query()->where('exercise_id', $exercise->id)->firstOrFail();

        $this->put(route('admin.latihan.status.update', $exercise), ['is_published' => true])
            ->assertRedirect()->assertSessionHas('status');
        $this->assertDatabaseHas('exercises', ['id' => $exercise->id, 'is_published' => true]);

        $sourceCode = "a = int(input())\nb = int(input())\nprint(a + b)";
        Http::fake(fn () => Http::response([
            'status' => ['id' => 3, 'description' => 'Accepted'],
            'stdout' => "5\n",
            'time' => '0.01',
        ]));

        $this->actingAs($learner)
            ->get(route('module.show', [$course, $module]))
            ->assertOk()
            ->assertSee('Lesson Integrasi');
        $this->get(route('latihan.show', $exercise->slug))
            ->assertOk()
            ->assertSee('Latihan Integrasi');

        $this->postJson(route('submissions.test-case.run', [
            'exerciseId' => $exercise->id,
            'testCaseId' => $testCase->id,
        ]), ['source_code' => $sourceCode])
            ->assertOk()
            ->assertJsonPath('is_passed', true)
            ->assertJsonPath('passed_tests', 1)
            ->assertJsonPath('total_tests', 1);

        $submissionResponse = $this->postJson(route('submissions.submit'), [
            'exercise_id' => $exercise->id,
            'source_code' => $sourceCode,
        ])->assertOk()
            ->assertJsonPath('status', 'accepted')
            ->assertJsonPath('score', 100)
            ->assertJsonPath('passed_tests', 1)
            ->assertJsonPath('total_tests', 1);

        $submission = Submission::query()->findOrFail($submissionResponse->json('submission_id'));
        $this->assertDatabaseHas('submission_results', [
            'submission_id' => $submission->id,
            'test_case_id' => $testCase->id,
            'status' => 'passed',
            'actual_output' => "5\n",
        ]);

        $this->post(route('lesson.complete', [$course, $module, $lesson]))
            ->assertRedirect(route('module.show', [$course, $module])."#materi-{$lesson->id}");

        $this->get(route('submissions.history', ['selected' => $submission->id]))
            ->assertOk()
            ->assertSee('Modul Integrasi')
            ->assertSee('Latihan Integrasi')
            ->assertSee("#SUB-{$submission->id}")
            ->assertSee('100/100');

        $this->actingAs($admin)
            ->get(route('admin.penilaian'))
            ->assertOk()
            ->assertSee($learner->name)
            ->assertSee('Latihan Integrasi')
            ->assertSee('100')
            ->assertSee('Lolos');
    }
}
