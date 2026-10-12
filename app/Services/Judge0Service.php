<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class Judge0Service
{
    /**
     * @return array<string, mixed>
     *
     * @throws ConnectionException
     */
    public function evaluateCode(
        string $sourceCode,
        ?string $expectedOutput = null,
        ?string $stdin = null,
        ?float $cpuTimeLimit = null,
        ?int $memoryLimit = null,
    ): array {
        $baseUrl = rtrim((string) config('services.judge0.url'), '/');

        if ($baseUrl === '') {
            throw new RuntimeException('Judge0 URL is not configured.');
        }

        $headers = [];
        $token = config('services.judge0.token');

        if (is_string($token) && $token !== '') {
            $headers[(string) config('services.judge0.auth_header', 'X-Auth-Token')] = $token;
        }

        $payload = [
            'source_code' => $sourceCode,
            'language_id' => (int) config('services.judge0.language_id', 71),
            'stdin' => $stdin,
            'cpu_time_limit' => $cpuTimeLimit ?? (float) config('services.judge0.cpu_time_limit', 2),
            'memory_limit' => $memoryLimit ?? (int) config('services.judge0.memory_limit', 128000),
            'max_processes_and_or_threads' => (int) config('services.judge0.max_processes_and_or_threads', 8),
            'enable_per_process_and_thread_time_limit' => (bool) config('services.judge0.per_process_time_limit', true),
            'enable_per_process_and_thread_memory_limit' => (bool) config('services.judge0.per_process_memory_limit', true),
        ];

        if ($expectedOutput !== null) {
            $payload['expected_output'] = $expectedOutput;
        }

        $result = Http::timeout((int) config('services.judge0.timeout', 10))
            ->withHeaders($headers)
            ->post($baseUrl.'/submissions?base64_encoded=false&wait=true', $payload)
            ->throw()
            ->json();

        if (! is_array($result) || ! is_array($result['status'] ?? null) || ! isset($result['status']['id'])) {
            throw new RuntimeException('Judge0 returned an invalid submission result.');
        }

        return $result;
    }
}
