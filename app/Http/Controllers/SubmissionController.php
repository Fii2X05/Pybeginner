<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\Judge0Service;
use App\Models\TestCase;
use App\Models\Submission;
use App\Models\SubmissionResult;
use Illuminate\Support\Carbon;
   use Illuminate\Support\Facades\Auth;
class SubmissionController extends Controller
{
    protected $judge0;

    public function __construct(Judge0Service $judge0)
    {
        $this->judge0 = $judge0;
    }

    // Halaman Riwayat Submission (tidak diubah)
    public function history(Request $request)
    {
        $query = Submission::with('results')->latest('submitted_at');

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        }

        $submissions = $query->get();

        $totalSubmissions = $submissions->count();
        $perfectScores = $submissions->where('score', 100)->count();
        $needsImprovement = $submissions->where('score', '<', 100)->count();
        $averageScore = $totalSubmissions > 0 ? round($submissions->avg('score'), 1) : 0;

        $selectedSubmissionId = $request->query('selected', $submissions->first()?->id);
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

    /**
     * Tombol "Jalankan Kode": hanya menjalankan test case yang TIDAK tersembunyi.
     * Tidak disimpan ke database.
     */
    public function run(Request $request)
    {
        $request->validate([
            'source_code' => 'required|string',
            'exercise_id' => 'required|integer',
        ]);

        $testCases = TestCase::where('exercise_id', $request->exercise_id)->get()
            ->reject(fn ($tc) => (bool) ($tc->is_hidden ?? false))
            ->values();

        if ($testCases->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Belum ada contoh uji coba untuk soal ini.'], 404);
        }

        try {
            $evaluated = $this->evaluate($request->source_code, $testCases);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['success' => false, 'message' => 'Layanan penilai (Judge0) tidak dapat dihubungi.'], 502);
        }

        return response()->json([
            'success'      => true,
            'score'        => round($evaluated['score'], 2),
            'passed_tests' => $evaluated['passed'],
            'total_tests'  => $evaluated['total'],
            'results'      => $evaluated['results'],
        ]);
    }

    /**
     * Tombol "Kirim & Nilai Jawaban": semua test case, hasil disimpan.
     */
    public function submit(Request $request)
    {
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
        }

        return response()->json([
            'success'       => true,
            'submission_id' => $submissionRecord->id,
            'status'        => $finalStatus,
            'score'         => round($score, 2),
            'passed_tests'  => $evaluated['passed'],
            'total_tests'   => $evaluated['total'],
            'results'       => $evaluated['results'],
            'message'       => 'Submission berhasil dievaluasi.',
        ]);
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