<?php

namespace App\Http\Controllers;

use App\Services\Judge0Service;
use Illuminate\Http\Request;

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
            'task_id' => 'nullable|integer',
        ]);

        $expectedOutput = "Halo Dunia\n";
        $stdin = null;
        $result = $this->judge0->evaluateCode(
            $request->source_code,
            $expectedOutput,
            $stdin
        );
        $statusId = $result['status']['id'] ?? null;
        $score = 0;
        $statusDescription = $result['status']['description'] ?? 'Unknown Error';

        if ($statusId === 3) {
            $score = 100;
            $message = 'Selamat! Solusi kamu benar.';
        } elseif ($statusId === 4) {
            $score = 0;
            $message = 'Jawaban belum sesuai dengan kriteria output.';
        } else {
            $score = 0;
            $message = 'Terjadi Error pada kode kamu.';
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
}
