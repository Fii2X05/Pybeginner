<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Judge0Service;
use App\Models\TestCase;
use App\Models\Submission;
use App\Models\SubmissionResult;
use Illuminate\Support\Carbon;

class SubmissionController extends Controller
{
    protected $judge0;

    public function __construct(Judge0Service $judge0)
    {
        $this->judge0 = $judge0;
    }

    // Method untuk menampilkan halaman Riwayat Submission
    public function history(Request $request)
    {
        // Ambil data submission beserta relasi hasil test case (results)
        // Jika sistem auth/login sudah aktif, aktifkan baris where('user_id', auth()->id())
        $query = Submission::with('results')->latest('submitted_at');

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        }

        $submissions = $query->get();

        // Hitung statistik ringkasan
        $totalSubmissions = $submissions->count();
        $perfectScores = $submissions->where('score', 100)->count();
        $needsImprovement = $submissions->where('score', '<', 100)->count();
        $averageScore = $totalSubmissions > 0 ? round($submissions->avg('score'), 1) : 0;

        // Tentukan submission mana yang dipilih/dilihat detailnya (default: submission terbaru)
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

    // Method submit() milikmu yang sebelumnya sudah disesuaikan
    public function submit(Request $request)
    {
        $request->validate([
            'source_code' => 'required|string',
            'exercise_id' => 'required|integer'
        ]);

        $testCases = TestCase::where('task_id', $request->exercise_id)->get();
        
        if ($testCases->isEmpty()) {
            return response()->json([
                'success' => false, 
                'message' => 'Sistem gagal menemukan test case untuk modul ini.'
            ], 404);
        }

        $passedCount = 0;
        $totalTestCases = $testCases->count();
        $executionDetails = [];

        foreach ($testCases as $tc) {
            $result = $this->judge0->evaluateCode(
                $request->source_code,
                $tc->expected_output,
                $tc->stdin
            );

            $statusId = $result['status']['id'] ?? null;
            $statusDescription = $result['status']['description'] ?? 'Unknown Error';
            $isAccepted = ($statusId === 3);

            if ($isAccepted) {
                $passedCount++;
            }

            $executionDetails[] = [
                'test_case_id' => $tc->id,
                'status' => $statusDescription,
                'is_passed' => $isAccepted,
                'stdout' => $result['stdout'] ?? null,
                'stderr' => $result['stderr'] ?? null,
                'expected' => $tc->expected_output,
                'time' => $result['time'] ?? null,
            ];
        }

        $score = ($passedCount / $totalTestCases) * 100;
        $finalStatus = ($score == 100) ? 'accepted' : (($score > 0) ? 'partial' : 'failed');

        // Simpan ke database
        $submissionRecord = Submission::create([
            'user_id' => auth()->id() ?? 1,
            'exercise_id' => $request->exercise_id,
            'source_code' => $request->source_code,
            'status' => $finalStatus,
            'score' => $score,
            'passed_tests' => $passedCount,
            'total_tests' => $totalTestCases,
            'submitted_at' => Carbon::now(),
        ]);

        foreach ($executionDetails as $detail) {
            SubmissionResult::create([
                'submission_id' => $submissionRecord->id,
                'test_case_id' => $detail['test_case_id'],
                'status' => $detail['status'],
                'actual_output' => $detail['stdout'],
                'error_message' => $detail['stderr'],
                'execution_time_ms' => isset($detail['time']) ? (int)($detail['time'] * 1000) : null,
            ]);
        }

        return response()->json([
            'success' => true,
            'submission_id' => $submissionRecord->id,
            'score' => round($score, 2),
            'status' => $finalStatus,
            'message' => 'Submission berhasil dievaluasi.'
        ]);
    }
}