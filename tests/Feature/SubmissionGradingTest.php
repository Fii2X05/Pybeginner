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
        $sourceCode = "def jumlah(a, b):\n    return a + b";

        $this->actingAs($user)->postJson(route('submissions.test-case.run', [
            'exerciseId' => $exerciseId,
            'testCaseId' => DB::table('test_cases')->where('is_hidden', false)->value('id'),
        ]), [
            'source_code' => $sourceCode,
        ])->assertOk()->assertJsonPath('is_passed', true);

        $response = $this->actingAs($user)->postJson(route('submissions.submit'), [
            'exercise_id' => $exerciseId,
            'source_code' => $sourceCode,
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

    public function test_test_case_progress_is_isolated_per_user_and_saved_per_case(): void
    {
        Http::fake(fn (ClientRequest $request) => Http::response([
            'status' => ['id' => 3, 'description' => 'Accepted'],
            'stdout' => $request->data()['stdin'] === "2\n3" ? "5\n" : "6\n",
            'time' => '0.01',
        ]));
        $exerciseId = $this->createExercise([
            [
                'input' => "2\n3",
                'expected_output' => '5',
                'assertion_code' => null,
                'is_hidden' => false,
            ],
            [
                'input' => "10\n-4",
                'expected_output' => '6',
                'assertion_code' => null,
                'is_hidden' => false,
            ],
        ]);
        $testCaseIds = DB::table('test_cases')
            ->where('exercise_id', $exerciseId)
            ->orderBy('sort_order')
            ->pluck('id');
        $sourceCode = "a = int(input())\nb = int(input())\nprint(a + b)";
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $this->actingAs($userA)
            ->postJson(route('submissions.test-case.run', [
                'exerciseId' => $exerciseId,
                'testCaseId' => $testCaseIds[0],
            ]), ['source_code' => $sourceCode])
            ->assertOk()
            ->assertJsonPath('is_passed', true)
            ->assertJsonPath('passed_tests', 1)
            ->assertJsonPath('total_tests', 2);

        $this->assertDatabaseHas('test_case_progress', [
            'user_id' => $userA->id,
            'exercise_id' => $exerciseId,
            'test_case_id' => $testCaseIds[0],
            'code_hash' => hash('sha256', $sourceCode),
            'status' => 'passed',
        ]);

        $this->actingAs($userB)
            ->postJson(route('submissions.test-case.progress', ['exerciseId' => $exerciseId]), [
                'source_code' => $sourceCode,
            ])
            ->assertOk()
            ->assertJsonPath('passed_tests', 0)
            ->assertJsonPath('total_tests', 2);
    }

    public function test_manual_runtime_error_does_not_change_reference_test_case_progress(): void
    {
        Http::fake(function (ClientRequest $request) {
            if ($request->data()['stdin'] === '0.5') {
                return Http::response([
                    'status' => ['id' => 11, 'description' => 'Runtime Error'],
                    'stdout' => null,
                    'stderr' => "ValueError: invalid literal for int() with base 10: '0.5'",
                    'time' => '0.01',
                ]);
            }

            return Http::response([
                'status' => ['id' => 3, 'description' => 'Accepted'],
                'stdout' => "Genap\n",
                'time' => '0.01',
            ]);
        });

        $exerciseId = $this->createExercise([
            [
                'input' => '4',
                'expected_output' => 'Genap',
                'assertion_code' => null,
                'is_hidden' => false,
            ],
        ]);
        $user = User::factory()->create();
        $sourceCode = "angka = int(input())\nprint('Genap' if angka % 2 == 0 else 'Ganjil')";
        $testCaseId = DB::table('test_cases')->where('exercise_id', $exerciseId)->value('id');

        $this->actingAs($user)
            ->postJson(route('submissions.test-case.run', [
                'exerciseId' => $exerciseId,
                'testCaseId' => $testCaseId,
            ]), ['source_code' => $sourceCode])
            ->assertOk()
            ->assertJsonPath('is_passed', true)
            ->assertJsonPath('input', '4');

        $this->postJson(route('submissions.execute'), [
            'exercise_id' => $exerciseId,
            'source_code' => $sourceCode,
            'stdin' => '0.5',
        ])->assertOk()
            ->assertJsonPath('status.id', 11)
            ->assertJsonPath('stderr', "ValueError: invalid literal for int() with base 10: '0.5'");

        $this->assertDatabaseHas('test_case_progress', [
            'user_id' => $user->id,
            'exercise_id' => $exerciseId,
            'test_case_id' => $testCaseId,
            'status' => 'passed',
        ]);
    }

    public function test_wrong_answer_from_judge0_marks_test_case_as_failed(): void
    {
        Http::fake(function (ClientRequest $request) {
            $this->assertSame('4', $request->data()['stdin']);
            $this->assertSame('Genap', $request->data()['expected_output']);

            return Http::response([
                'status' => ['id' => 4, 'description' => 'Wrong Answer'],
                'stdout' => "Ganjil\n",
                'time' => '0.01',
            ]);
        });

        $exerciseId = $this->createExercise([
            [
                'input' => '4',
                'expected_output' => 'Genap',
                'assertion_code' => null,
                'is_hidden' => false,
            ],
        ]);
        $user = User::factory()->create();
        $testCaseId = DB::table('test_cases')->where('exercise_id', $exerciseId)->value('id');

        $this->actingAs($user)
            ->postJson(route('submissions.test-case.run', [
                'exerciseId' => $exerciseId,
                'testCaseId' => $testCaseId,
            ]), ['source_code' => "print('Ganjil')"])
            ->assertOk()
            ->assertJsonPath('is_passed', false)
            ->assertJsonPath('expected', 'Genap')
            ->assertJsonPath('actual', "Ganjil\n")
            ->assertJsonPath('passed_tests', 0);

        $this->assertDatabaseHas('test_case_progress', [
            'user_id' => $user->id,
            'exercise_id' => $exerciseId,
            'test_case_id' => $testCaseId,
            'status' => 'failed',
        ]);
    }

    public function test_submit_requires_each_visible_case_to_pass_for_the_current_code(): void
    {
        Http::fake(fn (ClientRequest $request) => Http::response([
            'status' => ['id' => 3, 'description' => 'Accepted'],
            'stdout' => $request->data()['stdin'] === "2\n3" ? "5\n" : "6\n",
            'time' => '0.01',
        ]));
        $exerciseId = $this->createExercise([
            [
                'input' => "2\n3",
                'expected_output' => '5',
                'assertion_code' => null,
                'is_hidden' => false,
            ],
            [
                'input' => "10\n-4",
                'expected_output' => '6',
                'assertion_code' => null,
                'is_hidden' => false,
            ],
        ]);
        $testCaseIds = DB::table('test_cases')
            ->where('exercise_id', $exerciseId)
            ->orderBy('sort_order')
            ->pluck('id');
        $user = User::factory()->create();
        $sourceCode = "a = int(input())\nb = int(input())\nprint(a + b)";

        $this->actingAs($user)
            ->postJson(route('submissions.test-case.run', [
                'exerciseId' => $exerciseId,
                'testCaseId' => $testCaseIds[0],
            ]), ['source_code' => $sourceCode])
            ->assertOk();

        $this->postJson(route('submissions.submit'), [
            'exercise_id' => $exerciseId,
            'source_code' => $sourceCode,
        ])->assertUnprocessable()
            ->assertJsonPath('passed_tests', 1)
            ->assertJsonPath('total_tests', 2);

        $this->postJson(route('submissions.submit'), [
            'exercise_id' => $exerciseId,
            'source_code' => $sourceCode."\n# changed",
        ])->assertUnprocessable()
            ->assertJsonPath('passed_tests', 0);

        $this->postJson(route('submissions.test-case.run', [
            'exerciseId' => $exerciseId,
            'testCaseId' => $testCaseIds[1],
        ]), ['source_code' => $sourceCode])->assertOk();

        $this->postJson(route('submissions.submit'), [
            'exercise_id' => $exerciseId,
            'source_code' => $sourceCode,
        ])->assertOk()
            ->assertJsonPath('passed_tests', 2)
            ->assertJsonPath('total_tests', 2);

        $this->assertDatabaseCount('submissions', 1);
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

    public function test_execute_sends_custom_stdin_to_judge0_and_returns_execution_output(): void
    {
        Http::fake(fn () => Http::response([
            'status' => ['id' => 3, 'description' => 'Accepted'],
            'stdout' => "Genap\n",
            'stderr' => null,
            'compile_output' => null,
            'time' => '0.01',
        ]));

        $exerciseId = $this->createExercise([
            [
                'input' => '4',
                'expected_output' => 'Genap',
                'assertion_code' => null,
                'is_hidden' => false,
            ],
        ]);

        $this->postJson(route('submissions.execute'), [
            'exercise_id' => $exerciseId,
            'source_code' => 'angka = int(input()); print("Genap" if angka % 2 == 0 else "Ganjil")',
            'stdin' => '4',
        ])->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('status.description', 'Accepted')
            ->assertJsonPath('stdout', "Genap\n")
            ->assertJsonPath('stderr', null);

        Http::assertSent(fn (ClientRequest $request): bool => $request->data()['source_code'] === 'angka = int(input()); print("Genap" if angka % 2 == 0 else "Ganjil")'
            && $request->data()['stdin'] === '4'
            && ! array_key_exists('expected_output', $request->data())
            && $request->data()['language_id'] === 10);
    }

    public function test_execute_returns_judge0_errors_for_display(): void
    {
        Http::fake(fn () => Http::response([
            'status' => ['id' => 11, 'description' => 'Runtime Error'],
            'stdout' => '',
            'stderr' => 'ValueError: invalid input',
            'compile_output' => null,
            'time' => '0.02',
        ]));

        $exerciseId = $this->createExercise([
            [
                'assertion_code' => 'assert True',
                'is_hidden' => false,
            ],
        ]);

        $this->postJson(route('submissions.execute'), [
            'exercise_id' => $exerciseId,
            'source_code' => 'raise ValueError("invalid input")',
            'stdin' => '',
        ])->assertOk()
            ->assertJsonPath('status.id', 11)
            ->assertJsonPath('status.description', 'Runtime Error')
            ->assertJsonPath('stderr', 'ValueError: invalid input');
    }

    public function test_editor_displays_public_assertions_and_uses_the_grading_route(): void
    {
        $this->createExercise([
            [
                'assertion_code' => 'assert jumlah(2, 3) == 5',
                'is_hidden' => false,
            ],
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('latihan.show', 'jumlah'))
            ->assertOk()
            ->assertSee('id="editor"', false)
            ->assertSee('placeholder="Masukkan input : "', false)
            ->assertSee('Input Program (Jalankan Kode)')
            ->assertSee('Input manual:')
            ->assertSee('Hasil ini terpisah dari test case acuan')
            ->assertSee('Penilaian test case menggunakan input acuan')
            ->assertSee('Input Program hanya untuk Jalankan Kode dan tidak mengubah status')
            ->assertSee('Uji test case ini')
            ->assertSee('assert jumlah(2, 3) == 5')
            ->assertSee('const RUN_URL =', false);
    }

    public function test_history_displays_passed_test_results_as_successful(): void
    {
        $exerciseId = $this->createExercise([
            [
                'input' => "2\n3",
                'expected_output' => '5',
                'assertion_code' => null,
                'is_hidden' => false,
            ],
        ]);
        DB::table('exercises')->where('id', $exerciseId)->update([
            'title' => 'Jumlah Dua Bilangan',
            'slug' => 'jumlah-dua-bilangan',
        ]);
        $user = User::factory()->create();
        $now = now();
        $submissionId = DB::table('submissions')->insertGetId([
            'user_id' => $user->id,
            'exercise_id' => $exerciseId,
            'source_code' => "a = int(input())\nb = int(input())\nprint(a + b)",
            'status' => 'accepted',
            'score' => 100,
            'passed_tests' => 1,
            'total_tests' => 1,
            'execution_time_ms' => 34,
            'submitted_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $testCaseId = DB::table('test_cases')
            ->where('exercise_id', $exerciseId)
            ->value('id');

        DB::table('submission_results')->insert([
            'submission_id' => $submissionId,
            'test_case_id' => $testCaseId,
            'status' => 'passed',
            'actual_output' => "5\n",
            'execution_time_ms' => 34,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $this->actingAs($user)
            ->get(route('submissions.history', ['selected' => $submissionId]))
            ->assertOk()
            ->assertSee('border-emerald-600 font-semibold text-slate-900')
            ->assertSee('Jumlah Dua Bilangan')
            ->assertSee('print(a + b)')
            ->assertSee("2\n3")
            ->assertSee('"5"')
            ->assertSee('Test Case 1: Input Sesuai Ekspektasi')
            ->assertSee('Lolos • 34ms')
            ->assertDontSee('Output Tidak Cocok')
            ->assertDontSee('Cek Bilangan Genap atau Ganjil')
            ->assertDontSee('"Genap"');
    }

    public function test_history_paginates_submissions_five_per_page_and_keeps_global_statistics(): void
    {
        $exerciseId = $this->createExercise([
            [
                'assertion_code' => 'assert True',
                'is_hidden' => false,
            ],
        ]);
        $user = User::factory()->create();
        $submissionIds = [];
        $now = now();

        foreach (range(1, 7) as $number) {
            $submissionIds[] = DB::table('submissions')->insertGetId([
                'user_id' => $user->id,
                'exercise_id' => $exerciseId,
                'source_code' => "submission-code-{$number}",
                'status' => 'accepted',
                'score' => 100,
                'passed_tests' => 1,
                'total_tests' => 1,
                'execution_time_ms' => 34,
                'submitted_at' => $now->copy()->addSeconds($number),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->actingAs($user)
            ->get(route('submissions.history'))
            ->assertOk()
            ->assertSee('1–5 dari 7')
            ->assertSee('submission-code-7')
            ->assertSee(route('submissions.history', ['selected' => $submissionIds[2], 'page' => 1]))
            ->assertDontSee(route('submissions.history', ['selected' => $submissionIds[1], 'page' => 1]));

        $this->get(route('submissions.history', ['page' => 2]))
            ->assertOk()
            ->assertSee('6–7 dari 7')
            ->assertSee('submission-code-2')
            ->assertSee(route('submissions.history', ['selected' => $submissionIds[0], 'page' => 2]))
            ->assertDontSee(route('submissions.history', ['selected' => $submissionIds[2], 'page' => 2]));
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
