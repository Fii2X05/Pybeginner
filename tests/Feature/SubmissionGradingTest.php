<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SubmissionGradingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.judge0.url' => 'http://judge0.test']);
        config(['services.judge0.language_id' => 10]);
        config(['services.judge0.max_processes_and_or_threads' => 8]);
        config(['services.judge0.per_process_time_limit' => true]);
        config(['services.judge0.per_process_memory_limit' => true]);
    }

    public function test_run_executes_public_assertion_tests_without_exposing_hidden_cases(): void
    {
        Http::fake(fn () => Http::response([
            'status' => ['id' => 3, 'description' => 'Accepted'],
            'stdout' => '',
            'time' => '0.01',
        ]));

        $exerciseId = $this->createExercise([
            [
                'assertion_code' => 'assert jumlah(2, 3) == 5',
                'is_hidden' => false,
            ],
            [
                'assertion_code' => 'assert jumlah(1, 1) == 2',
                'is_hidden' => true,
            ],
        ]);

        $response = $this->postJson(route('submissions.run'), [
            'exercise_id' => $exerciseId,
            'source_code' => "def jumlah(a, b):\n    return a + b",
        ]);

        $response->assertOk()
            ->assertJsonPath('passed_tests', 1)
            ->assertJsonPath('total_tests', 1)
            ->assertJsonPath('score', 100)
            ->assertJsonPath('results.0.expected', 'Semua assertion harus lolos')
            ->assertJsonMissing(['assertion_code' => 'assert jumlah(1, 1) == 2']);

        Http::assertSent(fn (ClientRequest $request): bool => str_contains(
            $request->data()['source_code'],
            'assert jumlah(2, 3) == 5',
        ) && ! array_key_exists('expected_output', $request->data())
            && $request->data()['language_id'] === 10
            && $request->data()['max_processes_and_or_threads'] === 8
            && $request->data()['enable_per_process_and_thread_time_limit'] === true
            && $request->data()['enable_per_process_and_thread_memory_limit'] === true);
    }

    public function test_submit_saves_weighted_assertion_results_without_storing_hidden_output(): void
    {
        Http::fake(function (ClientRequest $request) {
            $isPassingCase = str_contains($request->data()['source_code'], 'assert jumlah(2, 3) == 5');

            return Http::response([
                'status' => [
                    'id' => $isPassingCase ? 3 : 11,
                    'description' => $isPassingCase ? 'Accepted' : 'Runtime Error',
                ],
                'stdout' => $isPassingCase ? "ok\n" : "private output\n",
                'stderr' => $isPassingCase ? null : 'AssertionError: private assertion',
                'time' => '0.02',
            ]);
        });

        $exerciseId = $this->createExercise([
            [
                'assertion_code' => 'assert jumlah(2, 3) == 5',
                'is_hidden' => false,
                'weight' => 1,
            ],
            [
                'assertion_code' => 'assert jumlah(1, 1) == 3',
                'is_hidden' => true,
                'weight' => 3,
            ],
        ]);

        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson(route('submissions.submit'), [
            'exercise_id' => $exerciseId,
            'source_code' => "def jumlah(a, b):\n    return a + b",
        ]);

        $response->assertOk()
            ->assertJsonPath('score', 25)
            ->assertJsonPath('passed_tests', 1)
            ->assertJsonPath('total_tests', 2)
            ->assertJsonPath('results.1.error', 'Test case tersembunyi gagal.')
            ->assertJsonMissing(['actual' => "private output\n"])
            ->assertJsonMissing(['error' => 'AssertionError: private assertion']);

        $this->assertDatabaseHas('submissions', [
            'user_id' => $user->id,
            'exercise_id' => $exerciseId,
            'status' => 'failed',
            'score' => 25,
            'passed_tests' => 1,
            'total_tests' => 2,
        ]);

        $hiddenResult = DB::table('submission_results')
            ->where('submission_id', $response->json('submission_id'))
            ->where('test_case_id', DB::table('test_cases')->where('is_hidden', true)->value('id'))
            ->first();

        $this->assertNull($hiddenResult->actual_output);
        $this->assertSame('Test case tersembunyi gagal.', $hiddenResult->error_message);
    }

    public function test_run_keeps_supporting_stdin_and_expected_output_test_cases(): void
    {
        Http::fake(fn () => Http::response([
            'status' => ['id' => 3, 'description' => 'Accepted'],
            'stdout' => "Halo, Budi!\n",
            'time' => '0.01',
        ]));

        $exerciseId = $this->createExercise([
            [
                'input' => 'Budi',
                'expected_output' => 'Halo, Budi!',
                'assertion_code' => null,
                'is_hidden' => false,
            ],
        ]);

        $this->postJson(route('submissions.run'), [
            'exercise_id' => $exerciseId,
            'source_code' => 'nama = input()',
        ])->assertOk()->assertJsonPath('score', 100);

        Http::assertSent(fn (ClientRequest $request): bool => $request->data()['stdin'] === 'Budi'
            && $request->data()['expected_output'] === 'Halo, Budi!');
    }

    public function test_editor_displays_public_assertions_and_uses_the_grading_route(): void
    {
        $this->createExercise([
            [
                'assertion_code' => 'assert jumlah(2, 3) == 5',
                'is_hidden' => false,
            ],
        ]);

        $this->get(route('latihan.show', 'jumlah'))
            ->assertOk()
            ->assertSee('id="editor"', false)
            ->assertSee('assert jumlah(2, 3) == 5')
            ->assertSee('const RUN_URL =', false);
    }

    /**
     * @param  array<int, array<string, mixed>>  $testCases
     */
    private function createExercise(array $testCases): int
    {
        $now = now();
        $courseId = DB::table('courses')->insertGetId([
            'title' => 'Python',
            'slug' => 'python-'.uniqid(),
            'description' => 'Test course',
            'level' => 'beginner',
            'is_published' => true,
            'sort_order' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $moduleId = DB::table('modules')->insertGetId([
            'course_id' => $courseId,
            'title' => 'Dasar',
            'slug' => 'dasar',
            'description' => 'Test module',
            'sort_order' => 1,
            'is_published' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $lessonId = DB::table('lessons')->insertGetId([
            'module_id' => $moduleId,
            'title' => 'Fungsi',
            'slug' => 'fungsi',
            'content' => 'Test lesson',
            'sort_order' => 1,
            'is_published' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $exerciseId = DB::table('exercises')->insertGetId([
            'lesson_id' => $lessonId,
            'title' => 'Jumlah',
            'slug' => 'jumlah',
            'description' => 'Jumlahkan dua bilangan.',
            'instructions' => '',
            'starter_code' => '',
            'difficulty' => 'easy',
            'time_limit_ms' => 10000,
            'memory_limit_mb' => 128,
            'sort_order' => 1,
            'is_published' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        foreach ($testCases as $index => $testCase) {
            DB::table('test_cases')->insert([
                'exercise_id' => $exerciseId,
                'input' => $testCase['input'] ?? null,
                'expected_output' => $testCase['expected_output'] ?? '',
                'assertion_code' => $testCase['assertion_code'],
                'weight' => $testCase['weight'] ?? 1,
                'is_hidden' => $testCase['is_hidden'],
                'is_active' => true,
                'sort_order' => $index + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return $exerciseId;
    }
}
