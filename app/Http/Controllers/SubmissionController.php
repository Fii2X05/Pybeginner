<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Module;
use App\Models\Submission;
use App\Models\TestCase;
use App\Models\TestCaseProgress;
use App\Services\AdminNotificationService;
use App\Services\Judge0Service;
use App\Services\PythonGradingWrapperService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class SubmissionController extends Controller
{
    public function __construct(
        private Judge0Service $judge0,
        private AdminNotificationService $adminNotifications,
        private PythonGradingWrapperService $wrapper,
    ) {}

    public function history(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['perfect', 'improvement'])],
            'module_id' => ['nullable', 'integer'],
        ]);
        $userSubmissions = Submission::where('user_id', Auth::id());
        $totalSubmissions = (clone $userSubmissions)->count();
        $perfectScores = (clone $userSubmissions)->where('score', 100)->count();
        $needsImprovement = (clone $userSubmissions)->where('score', '<', 100)->count();
        $averageScore = round((float) ((clone $userSubmissions)->avg('score') ?? 0), 1);
        $submissionsThisWeek = (clone $userSubmissions)
            ->where('submitted_at', '>=', now()->subDays(7))
            ->count();

        $filteredSubmissions = (clone $userSubmissions);

        if (filled($filters['search'] ?? null)) {
            $search = trim($filters['search']);
            $normalizedSearch = mb_strtolower($search);
            preg_match('/^(?:#?SUB-)?(\d+)$/i', $search, $submissionNumber);
            $filteredSubmissions->where(function ($query) use ($normalizedSearch, $submissionNumber): void {
                if (isset($submissionNumber[1])) {
                    $query->where('id', (int) $submissionNumber[1]);
                }

                $query->orWhereHas('exercise', function ($exerciseQuery) use ($normalizedSearch): void {
                    $exerciseQuery
                        ->whereRaw('LOWER(title) LIKE ?', ['%'.$normalizedSearch.'%'])
                        ->orWhereRaw('LOWER(slug) LIKE ?', ['%'.$normalizedSearch.'%']);
                });
            });
        }

        if (($filters['status'] ?? null) === 'perfect') {
            $filteredSubmissions->where('score', 100);
        } elseif (($filters['status'] ?? null) === 'improvement') {
            $filteredSubmissions->whereNotNull('score')->where('score', '<', 100);
        }

        if (filled($filters['module_id'] ?? null)) {
            $filteredSubmissions->whereHas('exercise.lesson', fn ($query) => $query
                ->where('module_id', $filters['module_id']));
        }

        $submissions = $filteredSubmissions
            ->with(['exercise.lesson.module', 'results.testCase'])
            ->latest('submitted_at')
            ->paginate(5)
            ->withQueryString();

        $modules = Module::query()
            ->whereHas('lessons.exercises.submissions', fn ($query) => $query->where('user_id', Auth::id()))
            ->orderBy('sort_order')
            ->get(['id', 'title']);

        $selectedSubmissionId = (int) $request->query('selected', $submissions->first()?->id);
        $selectedSubmission = (clone $filteredSubmissions)
            ->with(['exercise.lesson.module', 'results.testCase'])
            ->find($selectedSubmissionId) ?? $submissions->first();

        return view('history', compact(
            'submissions',
            'totalSubmissions',
            'perfectScores',
            'needsImprovement',
            'averageScore',
            'submissionsThisWeek',
            'modules',
            'filters',
            'selectedSubmission'
        ));
    }

    public function run(Request $request)
    {
        $data = $request->validate([
            'source_code' => 'required|string|max:50000',
            'exercise_id' => 'required|integer',
        ]);
        $exercise = Exercise::where('is_published', true)->findOrFail($data['exercise_id']);
        $testCases = $exercise->publicTestCases()->get();

        if ($testCases->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Belum ada contoh uji coba untuk soal ini.'], 404);
        }

        try {
            $evaluated = $this->evaluate($data['source_code'], $testCases, $exercise);
        } catch (Throwable $e) {
            report($e);
            $this->notifyJudge0Failure($e);

            return response()->json(['success' => false, 'message' => 'Layanan penilai (Judge0) tidak dapat dihubungi.'], 502);
        }

        return response()->json([
            'success' => true,
            'status' => $evaluated['status'],
            'score' => round($evaluated['score'], 2),
            'passed_tests' => $evaluated['passed'],
            'total_tests' => $evaluated['total'],
            'user_stdout' => $evaluated['user_stdout'],
            'error_message' => $evaluated['error_message'],
            'results' => $evaluated['results'],
        ]);
    }

    public function execute(Request $request)
    {
        $data = $request->validate([
            'source_code' => 'required|string|max:50000',
            'stdin' => 'nullable|string|max:50000',
            'exercise_id' => 'nullable|integer|exists:exercises,id',
        ]);
        $exercise = isset($data['exercise_id'])
            ? Exercise::where('is_published', true)->findOrFail($data['exercise_id'])
            : null;
        $timeLimitSeconds = min(5, max(1, ($exercise?->time_limit_ms ?? 2000) / 1000));
        $memoryLimitKb = min(256000, max(16384, ($exercise?->memory_limit_mb ?? 128) * 1024));

        try {
            $execution = $this->judge0->evaluateCode(
                $data['source_code'],
                null,
                $data['stdin'] ?? '',
                $timeLimitSeconds,
                $memoryLimitKb,
            );
        } catch (Throwable $e) {
            report($e);
            $this->notifyJudge0Failure($e);

            return response()->json(['success' => false, 'message' => 'Layanan penilai (Judge0) tidak dapat dihubungi.'], 502);
        }

        return response()->json([
            'success' => true,
            'status' => $execution['status'],
            'stdout' => $execution['stdout'] ?? null,
            'stderr' => $execution['stderr'] ?? null,
            'compile_output' => $execution['compile_output'] ?? null,
            'message' => $execution['message'] ?? null,
            'time' => $execution['time'] ?? null,
        ]);
    }

    public function runTestCase(Request $request, int $exerciseId, int $testCaseId)
    {
        $data = $request->validate([
            'source_code' => 'required|string|max:50000',
        ]);
        $exercise = Exercise::where('is_published', true)->findOrFail($exerciseId);
        $testCase = $exercise->publicTestCases()->whereKey($testCaseId)->firstOrFail();
        $isAssertionTest = is_string($testCase->assertion_code) && trim($testCase->assertion_code) !== '';
        $code = $isAssertionTest
            ? rtrim($data['source_code'])."\n\n".$testCase->assertion_code."\n"
            : $data['source_code'];

        try {
            $execution = $this->judge0->evaluateCode(
                $code,
                $isAssertionTest ? null : $testCase->expected_output,
                $isAssertionTest ? null : $testCase->input,
                min(5, max(1, $exercise->time_limit_ms / 1000)),
                min(256000, max(16384, $exercise->memory_limit_mb * 1024)),
            );
        } catch (Throwable $e) {
            report($e);
            $this->notifyJudge0Failure($e);

            return response()->json(['success' => false, 'message' => 'Layanan penilai (Judge0) tidak dapat dihubungi.'], 502);
        }

        $isPassed = (int) $execution['status']['id'] === 3;
        $output = (string) ($execution['stdout'] ?? '');
        $error = $this->executionError($execution);
        $codeHash = hash('sha256', $data['source_code']);

        TestCaseProgress::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'exercise_id' => $exercise->id,
                'test_case_id' => $testCase->id,
            ],
            [
                'code_hash' => $codeHash,
                'status' => $isPassed ? 'passed' : 'failed',
                'actual_output' => $output,
                'error_message' => $error !== '' ? $error : null,
                'execution_time_ms' => (int) round(((float) ($execution['time'] ?? 0)) * 1000),
            ],
        );

        $publicTestCaseIds = $exercise->publicTestCases()->pluck('test_cases.id');
        $passedTests = TestCaseProgress::where('user_id', Auth::id())
            ->where('exercise_id', $exercise->id)
            ->where('code_hash', $codeHash)
            ->where('status', 'passed')
            ->whereIn('test_case_id', $publicTestCaseIds)
            ->count();

        return response()->json([
            'success' => true,
            'test_case_id' => $testCase->id,
            'is_passed' => $isPassed,
            'input' => $testCase->input,
            'expected' => $isAssertionTest ? 'Semua assertion harus lolos' : $testCase->expected_output,
            'actual' => $isAssertionTest ? ($isPassed ? 'Semua assertion lolos' : $output) : $output,
            'error' => $error !== '' ? $error : null,
            'passed_tests' => $passedTests,
            'total_tests' => $publicTestCaseIds->count(),
        ]);
    }

    public function testCaseProgress(Request $request, int $exerciseId)
    {
        $data = $request->validate([
            'source_code' => 'required|string|max:50000',
        ]);
        $exercise = Exercise::where('is_published', true)->findOrFail($exerciseId);
        $publicTestCaseIds = $exercise->publicTestCases()->pluck('test_cases.id');
        $progress = TestCaseProgress::where('user_id', Auth::id())
            ->where('exercise_id', $exercise->id)
            ->where('code_hash', hash('sha256', $data['source_code']))
            ->whereIn('test_case_id', $publicTestCaseIds)
            ->get(['test_case_id', 'status'])
            ->keyBy('test_case_id');

        return response()->json([
            'success' => true,
            'progress' => $progress->map(fn (TestCaseProgress $testCaseProgress): string => $testCaseProgress->status),
            'passed_tests' => $progress->where('status', 'passed')->count(),
            'total_tests' => $publicTestCaseIds->count(),
        ]);
    }

    public function submit(Request $request)
    {
        $data = $request->validate([
            'source_code' => 'required|string|max:50000',
            'exercise_id' => 'required|integer',
        ]);
        $exercise = Exercise::where('is_published', true)->findOrFail($data['exercise_id']);
        $testCases = $exercise->testCases()->get();

        if ($testCases->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Soal ini belum memiliki test case aktif.'], 404);
        }

        $userId = (int) Auth::id();
        $publicTestCaseIds = $exercise->publicTestCases()->pluck('test_cases.id');
        $codeHash = hash('sha256', $data['source_code']);
        $passedTestCases = TestCaseProgress::where('user_id', $userId)
            ->where('exercise_id', $exercise->id)
            ->where('code_hash', $codeHash)
            ->where('status', 'passed')
            ->whereIn('test_case_id', $publicTestCaseIds)
            ->count();

        if ($passedTestCases < $publicTestCaseIds->count()) {
            return response()->json([
                'success' => false,
                'message' => 'Uji semua test case yang terlihat dengan kode saat ini sebelum mengirim jawaban.',
                'passed_tests' => $passedTestCases,
                'total_tests' => $publicTestCaseIds->count(),
            ], 422);
        }

        $lockKey = "submission_lock:{$userId}:{$exercise->id}";
        $lock = Cache::lock($lockKey, 15);

        if (! $lock->get()) {
            return response()->json([
                'success' => false,
                'message' => 'Submission sedang diproses, silakan tunggu beberapa saat.',
            ], 429);
        }

        try {
            $recentSubmission = Submission::where('user_id', $userId)
                ->where('exercise_id', $exercise->id)
                ->where('source_code', $data['source_code'])
                ->where('submitted_at', '>=', now()->subSeconds(5))
                ->latest('submitted_at')
                ->first();

            if ($recentSubmission) {
                $testCasesById = $testCases->keyBy('id');
                $recentResults = $recentSubmission->results()->get()->map(function ($res) use ($testCasesById) {
                    $tc = $testCasesById->get($res->test_case_id);
                    $isHidden = (bool) ($tc?->is_hidden ?? false);

                    return [
                        'is_hidden' => $isHidden,
                        'is_passed' => $res->status === 'passed',
                        'input' => $isHidden ? null : $tc?->input,
                        'expected' => $isHidden ? null : ($tc?->assertion_code ? 'Semua assertion harus lolos' : $tc?->expected_output),
                        'actual' => $res->actual_output,
                        'error' => $res->error_message,
                    ];
                })->all();

                return response()->json([
                    'success' => true,
                    'submission_id' => $recentSubmission->id,
                    'status' => $recentSubmission->status,
                    'score' => (float) $recentSubmission->score,
                    'passed_tests' => $recentSubmission->passed_tests,
                    'total_tests' => $recentSubmission->total_tests,
                    'user_stdout' => $recentSubmission->user_stdout,
                    'error_message' => $recentSubmission->error_message,
                    'results' => $recentResults,
                    'message' => $recentSubmission->status === 'accepted'
                        ? 'Selamat! Semua test case lolos.'
                        : 'Belum tepat. Periksa hasil test case dan coba lagi.',
                ]);
            }

            $evaluated = $this->evaluate($data['source_code'], $testCases, $exercise);

            $submission = DB::transaction(function () use ($data, $evaluated, $userId): Submission {
                $submission = Submission::create([
                    'user_id' => $userId,
                    'exercise_id' => $data['exercise_id'],
                    'source_code' => $data['source_code'],
                    'status' => $evaluated['status'],
                    'score' => $evaluated['score'],
                    'passed_tests' => $evaluated['passed'],
                    'total_tests' => $evaluated['total'],
                    'execution_time_ms' => $evaluated['execution_time_ms'],
                    'judge0_status_id' => $evaluated['judge0_status_id'],
                    'judge0_status_description' => $evaluated['judge0_status_description'],
                    'user_stdout' => $evaluated['user_stdout'],
                    'error_message' => $evaluated['error_message'],
                    'details' => $evaluated['stored_results'],
                    'submitted_at' => now(),
                ]);

                if ($evaluated['stored_results'] !== []) {
                    $submission->results()->createMany($evaluated['stored_results']);
                }

                return $submission;
            });

            if ($evaluated['score'] === 100.0 && Auth::user()->role === 'learner') {
                $this->adminNotifications->notifyAdmins(
                    'perfect_score',
                    'Siswa meraih skor sempurna',
                    Auth::user()->name.' mendapatkan skor 100 pada latihan '.$exercise->title.'.',
                    route('admin.penilaian'),
                );
            }

            return response()->json([
                'success' => true,
                'submission_id' => $submission->id,
                'status' => $submission->status,
                'score' => $evaluated['score'],
                'passed_tests' => $evaluated['passed'],
                'total_tests' => $evaluated['total'],
                'user_stdout' => $submission->user_stdout,
                'error_message' => $submission->error_message,
                'results' => $evaluated['results'],
                'message' => match ($submission->status) {
                    'accepted' => 'Selamat! Semua test case lolos.',
                    'partial' => 'Sebagian test case lolos. Periksa hasil test case dan coba lagi.',
                    default => 'Belum tepat. Periksa hasil test case dan coba lagi.',
                },
            ]);
        } catch (Throwable $e) {
            report($e);
            $this->notifyJudge0Failure($e);

            return response()->json(['success' => false, 'message' => 'Layanan penilai (Judge0) tidak dapat dihubungi.'], 502);
        } finally {
            $lock->release();
        }
    }

    /**
     * Mengevaluasi kode siswa menggunakan Isolated Token-Based JSON Wrapper
     * dalam satu panggilan Judge0 dengan dukungan partial scoring berbobot.
     *
     * @param  Collection<int, TestCase>  $testCases
     * @return array{
     *     status: string,
     *     score: float,
     *     passed: int,
     *     total: int,
     *     execution_time_ms: int,
     *     judge0_status_id: int,
     *     judge0_status_description: string,
     *     user_stdout: string,
     *     error_message: ?string,
     *     results: array<int, array<string, mixed>>,
     *     stored_results: array<int, array<string, mixed>>
     * }
     */
    private function evaluate(string $sourceCode, Collection $testCases, Exercise $exercise): array
    {
        $token = $this->wrapper->generateToken();
        $wrappedCode = $this->wrapper->wrap($sourceCode, $testCases, $token);

        $execution = $this->judge0->evaluateCode(
            $wrappedCode,
            null,
            null,
            min(5, max(1, $exercise->time_limit_ms / 1000)),
            min(256000, max(16384, $exercise->memory_limit_mb * 1024)),
        );

        $statusId = (int) $execution['status']['id'];
        $statusDescription = (string) ($execution['status']['description'] ?? 'Unknown');
        $timeMs = (int) round(((float) ($execution['time'] ?? 0)) * 1000);
        $totalCount = $testCases->count();

        if ($this->judge0->isTimeLimitExceeded($execution)) {
            return $this->buildFailureEvaluation(
                'tle',
                'Time Limit Exceeded: Eksekusi kode melebihi batas waktu.',
                $statusId,
                $statusDescription,
                $timeMs,
                $totalCount,
                $testCases,
            );
        }

        $parsed = $this->wrapper->parseOutput($execution['stdout'] ?? null, $token);

        if ($parsed === null || $this->judge0->isCompilationError($execution)) {
            $rawError = $this->executionError($execution) ?? "Eksekusi gagal dengan status Judge0: {$statusDescription}.";
            $failureStatus = $this->judge0->isCompilationError($execution) ? 'compile_error' : 'runtime_error';

            return $this->buildFailureEvaluation(
                $failureStatus,
                $rawError,
                $statusId,
                $statusDescription,
                $timeMs,
                $totalCount,
                $testCases,
            );
        }

        if (! empty($parsed['compile_error']) || ! empty($parsed['runtime_error'])) {
            $isCompile = ! empty($parsed['compile_error']);

            return $this->buildFailureEvaluation(
                $isCompile ? 'compile_error' : 'runtime_error',
                (string) ($parsed['compile_error'] ?? $parsed['runtime_error']),
                $statusId,
                $statusDescription,
                $timeMs,
                $totalCount,
                $testCases,
                (string) ($parsed['user_stdout'] ?? ''),
            );
        }

        $casesById = $testCases->keyBy('id');
        $results = [];
        $storedResults = [];
        $passedWeight = 0;
        $totalWeight = 0;
        $passedCount = 0;

        foreach ($parsed['results'] ?? [] as $item) {
            /** @var TestCase|null $testCase */
            $testCase = $casesById->get($item['test_case_id'] ?? null);
            if (! $testCase) {
                continue;
            }

            $isPassed = (bool) ($item['passed'] ?? false);
            $isHidden = (bool) $testCase->is_hidden;
            $isAssertionTest = is_string($testCase->assertion_code) && trim($testCase->assertion_code) !== '';
            $weight = max(0, (int) $testCase->weight);
            $output = (string) ($item['actual_output'] ?? '');
            $error = is_string($item['error'] ?? null) ? trim($item['error']) : '';

            $passedCount += $isPassed ? 1 : 0;
            $passedWeight += $isPassed ? $weight : 0;
            $totalWeight += $weight;

            $results[] = [
                'is_hidden' => $isHidden,
                'is_passed' => $isPassed,
                'input' => $isHidden || $isAssertionTest ? null : $testCase->input,
                'expected' => $isHidden ? null : ($isAssertionTest ? 'Semua assertion harus lolos' : $testCase->expected_output),
                'actual' => $isHidden ? null : ($isAssertionTest ? ($isPassed ? 'Semua assertion lolos' : $output) : $output),
                'error' => $isHidden
                    ? ($isPassed ? null : 'Test case tersembunyi gagal.')
                    : ($error !== '' ? $error : null),
            ];

            $storedResults[] = [
                'test_case_id' => $testCase->id,
                'status' => $isPassed ? 'passed' : 'failed',
                'actual_output' => $isHidden ? null : $output,
                'error_message' => $isHidden
                    ? ($isPassed ? null : 'Test case tersembunyi gagal.')
                    : ($error !== '' ? $error : null),
                'execution_time_ms' => $timeMs,
            ];
        }

        $score = $totalWeight > 0 ? round(($passedWeight / $totalWeight) * 100, 2) : 0.0;
        $status = match (true) {
            $score === 100.0 && $totalCount > 0 => 'accepted',
            $passedCount > 0 => 'partial',
            default => 'failed',
        };

        $userStderr = is_string($parsed['user_stderr'] ?? null) ? trim($parsed['user_stderr']) : '';

        return [
            'status' => $status,
            'score' => $score,
            'passed' => $passedCount,
            'total' => $totalCount,
            'execution_time_ms' => $timeMs,
            'judge0_status_id' => $statusId,
            'judge0_status_description' => $statusDescription,
            'user_stdout' => (string) ($parsed['user_stdout'] ?? ''),
            'error_message' => $userStderr !== '' ? $userStderr : null,
            'results' => $results,
            'stored_results' => $storedResults,
        ];
    }

    /**
     * @param  Collection<int, TestCase>  $testCases
     * @return array{
     *     status: string,
     *     score: float,
     *     passed: int,
     *     total: int,
     *     execution_time_ms: int,
     *     judge0_status_id: int,
     *     judge0_status_description: string,
     *     user_stdout: string,
     *     error_message: string,
     *     results: array<int, array<string, mixed>>,
     *     stored_results: array<int, array<string, mixed>>
     * }
     */
    private function buildFailureEvaluation(
        string $status,
        string $errorMessage,
        int $judge0StatusId,
        string $judge0StatusDescription,
        int $executionTimeMs,
        int $totalCount,
        Collection $testCases,
        string $userStdout = '',
    ): array {
        $results = [];
        $storedResults = [];

        foreach ($testCases as $testCase) {
            $isHidden = (bool) $testCase->is_hidden;
            $isAssertionTest = is_string($testCase->assertion_code) && trim($testCase->assertion_code) !== '';

            $results[] = [
                'is_hidden' => $isHidden,
                'is_passed' => false,
                'input' => $isHidden || $isAssertionTest ? null : $testCase->input,
                'expected' => $isHidden ? null : ($isAssertionTest ? 'Semua assertion harus lolos' : $testCase->expected_output),
                'actual' => null,
                'error' => $isHidden ? 'Test case tersembunyi gagal.' : $errorMessage,
            ];

            $storedResults[] = [
                'test_case_id' => $testCase->id,
                'status' => 'failed',
                'actual_output' => null,
                'error_message' => $isHidden ? 'Test case tersembunyi gagal.' : $errorMessage,
                'execution_time_ms' => $executionTimeMs,
            ];
        }

        return [
            'status' => $status,
            'score' => 0.0,
            'passed' => 0,
            'total' => $totalCount,
            'execution_time_ms' => $executionTimeMs,
            'judge0_status_id' => $judge0StatusId,
            'judge0_status_description' => $judge0StatusDescription,
            'user_stdout' => $userStdout,
            'error_message' => $errorMessage,
            'results' => $results,
            'stored_results' => $storedResults,
        ];
    }

    private function notifyJudge0Failure(Throwable $exception): void
    {
        $statusCode = $exception instanceof RequestException
            ? $exception->response->status()
            : null;

        $this->adminNotifications->notifyJudge0Failure($statusCode);
    }

    private function executionError(array $execution): ?string
    {
        $details = trim(implode("\n", array_filter([
            $execution['stderr'] ?? null,
            $execution['compile_output'] ?? null,
            $execution['message'] ?? null,
        ])));

        if ($details !== '') {
            return $details;
        }

        if ((int) $execution['status']['id'] === 3) {
            return null;
        }

        return (string) ($execution['status']['description'] ?? 'Eksekusi gagal.');
    }
}
