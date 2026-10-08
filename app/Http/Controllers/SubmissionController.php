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
            'success' => true,
            'score' => $score,
            'status_id' => $statusId,
            'status_description' => $statusDescription,
            'message' => $message,
            'stdout' => $result['stdout'] ?? null,
            'stderr' => $result['stderr'] ?? null,
            'compile_output' => $result['compile_output'] ?? null,
            'time' => $result['time'] ?? null,
            'memory' => $result['memory'] ?? null,
        ]);
    }
}
