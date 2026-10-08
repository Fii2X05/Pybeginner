<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Judge0Service
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('JUDGE0_URL');
    }

    public function evaluateCode($sourceCode, $expectedOutput = null, $stdin = null)
    {
        $response = Http::post("{$this->baseUrl}/submissions?base64_encoded=false&wait=true", [
            'source_code' => $sourceCode,
            'language_id' => 10,
            'expected_output' => $expectedOutput,
            'stdin' => $stdin,
            'cpu_time_limit' => 2.0,
            'memory_limit' => 128000,
        ]);

        return $response->json();
    }
}
