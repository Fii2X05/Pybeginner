<?php

namespace Tests\Unit;

use App\Services\Judge0Service;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

class Judge0ServiceTest extends TestCase
{
    public function test_it_sends_configured_submission_to_judge0(): void
    {
        config([
            'services.judge0.url' => 'http://judge0.test/',
            'services.judge0.token' => 'test-token',
            'services.judge0.auth_header' => 'X-Api-Key',
            'services.judge0.language_id' => 10,
            'services.judge0.max_processes_and_or_threads' => 4,
            'services.judge0.per_process_time_limit' => true,
            'services.judge0.per_process_memory_limit' => false,
        ]);
        Http::fake([
            'judge0.test/submissions*' => Http::response([
                'status' => ['id' => 3, 'description' => 'Accepted'],
                'stdout' => "42\n",
            ]),
        ]);

        $result = (new Judge0Service)->evaluateCode(
            'print(42)',
            '42',
            'input',
            3.5,
            64000,
        );

        $this->assertSame(3, $result['status']['id']);
        Http::assertSent(fn (ClientRequest $request): bool => $request->url()
            === 'http://judge0.test/submissions?base64_encoded=false&wait=true'
            && $request->hasHeader('X-Api-Key', 'test-token')
            && $request->data() === [
                'source_code' => 'print(42)',
                'language_id' => 10,
                'stdin' => 'input',
                'cpu_time_limit' => 3.5,
                'memory_limit' => 64000,
                'max_processes_and_or_threads' => 4,
                'enable_per_process_and_thread_time_limit' => true,
                'enable_per_process_and_thread_memory_limit' => false,
                'expected_output' => '42',
            ]);
    }

    public function test_it_rejects_an_unconfigured_judge0_url(): void
    {
        config(['services.judge0.url' => '']);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Judge0 URL is not configured.');

        (new Judge0Service)->evaluateCode('print(42)');
    }

    public function test_it_rejects_an_invalid_judge0_response(): void
    {
        config(['services.judge0.url' => 'http://judge0.test']);
        Http::fake([
            'judge0.test/submissions*' => Http::response([
                'status' => ['description' => 'Accepted'],
            ]),
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Judge0 returned an invalid submission result.');

        (new Judge0Service)->evaluateCode('print(42)');
    }
}
