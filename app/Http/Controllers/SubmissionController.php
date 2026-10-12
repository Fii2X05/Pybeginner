<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\Exercise;
use App\Models\Submission;
use App\Models\TestCase;
use App\Services\Judge0Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;
=======
>>>>>>> origin/main

use Illuminate\Http\Request;
use App\Services\Judge0Service;
use App\Models\TestCase;
use App\Models\Submission;
use App\Models\SubmissionResult;
use Illuminate\Support\Carbon;
   use Illuminate\Support\Facades\Auth;
class SubmissionController extends Controller
{
    public function __construct(private Judge0Service $judge0) {}

    public function history(Request $request)
    {
        $submissions = Submission::with('results')
            ->where('user_id', Auth::id())
            ->latest('submitted_at')
            ->get();

        $totalSubmissions = $submissions->count();
        $perfectScores = $submissions->where('score', 100)->count();
        $needsImprovement = $submissions->where('score', '<', 100)->count();
        $averageScore = $totalSubmissions > 0 ? round($submissions->avg('score'), 1) : 0;

        $selectedSubmissionId = (int) $request->query('selected', $submissions->first()?->id);
        $selectedSubmission = $submissions->firstWhere('id', $selectedSubmissionId) ?? $submissions->first();

        return view('history', compact(
            'submissions',
            'totalSubmissions',
            'perfectScores',
            'needsImprovement',
            'averageScore',
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

            return response()->json(['success' => false, 'message' => 'Layanan penilai (Judge0) tidak dapat dihubungi.'], 502);
        }

        return response()->json([
            'success' => true,
            'score' => round($evaluated['score'], 2),
            'passed_tests' => $evaluated['passed'],
            'total_tests' => $evaluated['total'],
            'results' => $evaluated['results'],
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

        try {
            $evaluated = $this->evaluate($data['source_code'], $testCases, $exercise);
        } catch (Throwable $e) {
            report($e);

            return response()->json(['success' => false, 'message' => 'Layanan penilai (Judge0) tidak dapat dihubungi.'], 502);
        }

        $submission = DB::transaction(function () use ($data, $evaluated): Submission {
            $submission = Submission::create([
                'user_id' => Auth::id(),
                'exercise_id' => $data['exercise_id'],
                'source_code' => $data['source_code'],
                'status' => $evaluated['score'] === 100.0 ? 'accepted' : 'failed',
                'score' => $evaluated['score'],
                'passed_tests' => $evaluated['passed'],
                'total_tests' => $evaluated['total'],
                'execution_time_ms' => $evaluated['execution_time_ms'],
                'submitted_at' => now(),
            ]);

            $submission->results()->createMany($evaluated['stored_results']);

            return $submission;
        });

        return response()->json([
            'success' => true,
            'submission_id' => $submission->id,
            'status' => $submission->status,
            'score' => $evaluated['score'],
            'passed_tests' => $evaluated['passed'],
            'total_tests' => $evaluated['total'],
            'results' => $evaluated['results'],
            'message' => $submission->status === 'accepted'
                ? 'Selamat! Semua test case lolos.'
                : 'Belum tepat. Periksa hasil test case dan coba lagi.',
        ]);
    }

    /**
     * @param  Collection<int, TestCase>  $testCases
     * @return array{
     *     score: float,
     *     passed: int,
     *     total: int,
     *     execution_time_ms: int,
     *     results: array<int, array<string, mixed>>,
     *     stored_results: array<int, array<string, mixed>>
     * }
     */
    private function evaluate(string $sourceCode, Collection $testCases, Exercise $exercise): array
    {
<<<<<<< HEAD
        $results = [];
        $storedResults = [];
        $passedWeight = 0;
        $totalWeight = 0;
        $passedCount = 0;
        $executionTimeMs = 0;

        foreach ($testCases as $testCase) {
            $isAssertionTest = is_string($testCase->assertion_code) && trim($testCase->assertion_code) !== '';
            $code = $isAssertionTest
                ? rtrim($sourceCode)."\n\n".$testCase->assertion_code."\n"
                : $sourceCode;
            $execution = $this->judge0->evaluateCode(
                $code,
                $isAssertionTest ? null : $testCase->expected_output,
                $isAssertionTest ? null : $testCase->input,
                min(5, max(1, $exercise->time_limit_ms / 1000)),
                min(256000, max(16384, $exercise->memory_limit_mb * 1024)),
            );

            $statusId = (int) $execution['status']['id'];
            $isPassed = $statusId === 3;
            $isHidden = (bool) $testCase->is_hidden;
            $weight = max(0, (int) $testCase->weight);
            $timeMs = (int) round(((float) ($execution['time'] ?? 0)) * 1000);
            $output = (string) ($execution['stdout'] ?? '');
            $error = trim(implode("\n", array_filter([
                $execution['stderr'] ?? null,
                $execution['compile_output'] ?? null,
                $execution['message'] ?? null,
            ])));

            $passedCount += $isPassed ? 1 : 0;
            $passedWeight += $isPassed ? $weight : 0;
            $totalWeight += $weight;
            $executionTimeMs += $timeMs;

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
=======
        $request->validate([
            'source_code' => 'required|string',
            'exercise_id' => 'required|integer',
        ]);

        $testCases = TestCase::where('exercise_id', $request->exercise_id)->get();

        if ($testCases->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Sistem gagal menemukan test case untuk modul ini.',
            ], 404);
        }

        try {
            $evaluated = $this->evaluate($request->source_code, $testCases);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['success' => false, 'message' => 'Layanan penilai (Judge0) tidak dapat dihubungi.'], 502);
        }

        $score = $evaluated['score'];
        $finalStatus = ($score == 100) ? 'accepted' : (($score > 0) ? 'partial' : 'failed');

        $submissionRecord = Submission::create([
            'user_id'      => auth()->id() ?? 1,
            'exercise_id'  => $request->exercise_id,
            'source_code'  => $request->source_code,
            'status'       => $finalStatus,
            'score'        => $score,
            'passed_tests' => $evaluated['passed'],
            'total_tests'  => $evaluated['total'],
            'submitted_at' => Carbon::now(),
        ]);

        foreach ($evaluated['raw'] as $detail) {
            SubmissionResult::create([
                'submission_id'     => $submissionRecord->id,
                'test_case_id'      => $detail['test_case_id'],
                'status'            => $detail['status'],
                'actual_output'     => $detail['stdout'],
                'error_message'     => $detail['stderr'],
                'execution_time_ms' => isset($detail['time']) ? (int) ($detail['time'] * 1000) : null,
            ]);
>>>>>>> origin/main
        }

        return [
            'score' => $totalWeight > 0 ? round(($passedWeight / $totalWeight) * 100, 2) : 0.0,
            'passed' => $passedCount,
            'total' => $testCases->count(),
            'execution_time_ms' => $executionTimeMs,
            'results' => $results,
            'stored_results' => $storedResults,
        ];
    }

    /**
     * Jalankan kode terhadap sekumpulan test case lewat Judge0.
     * Mengembalikan: skor, jumlah lolos, hasil untuk browser ('results'),
     * dan data lengkap untuk database ('raw').
     */
    private function evaluate(string $sourceCode, $testCases): array
    {
        $passed = 0;
        $results = [];
        $raw = [];

        foreach ($testCases as $tc) {
            $r = $this->judge0->evaluateCode($sourceCode, $tc->expected_output, $tc->stdin);

            $statusId = $r['status']['id'] ?? null;
            $statusDesc = $r['status']['description'] ?? 'Unknown Error';
            $isPassed = ($statusId === 3);
            $isHidden = (bool) ($tc->is_hidden ?? false);

            if ($isPassed) {
                $passed++;
            }

            // Pesan error: stderr, error kompilasi, atau status selain Accepted/Wrong Answer
            $error = $r['stderr'] ?? $r['compile_output'] ?? $r['message'] ?? null;
            if (!$error && !in_array($statusId, [3, 4], true)) {
                $error = $statusDesc; // contoh: Time Limit Exceeded
            }

            $raw[] = [
                'test_case_id' => $tc->id,
                'status'       => $statusDesc,
                'stdout'       => $r['stdout'] ?? null,
                'stderr'       => $error,
                'time'         => $r['time'] ?? null,
            ];

            // Test case tersembunyi: JANGAN kirim input / expected / actual ke browser
            $item = ['is_hidden' => $isHidden, 'is_passed' => $isPassed, 'error' => $error];
            if (!$isHidden) {
                $item += [
                    'input'    => $tc->stdin,
                    'expected' => $tc->expected_output,
                    'actual'   => rtrim($r['stdout'] ?? ''),
                ];
            }
            $results[] = $item;
        }

        $total = $testCases->count();

        return [
            'score'   => $total > 0 ? ($passed / $total) * 100 : 0,
            'passed'  => $passed,
            'total'   => $total,
            'results' => $results,
            'raw'     => $raw,
        ];
    }
}