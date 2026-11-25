<?php

namespace App\Services;

use App\Models\TestCase;
use Illuminate\Support\Collection;
use JsonException;

class PythonGradingWrapperService
{
    private const TOKEN_PREFIX = 'PYB_RESULT_';

    /**
     * Menghasilkan token acak unik untuk satu sesi eksekusi grading.
     */
    public function generateToken(): string
    {
        return self::TOKEN_PREFIX.bin2hex(random_bytes(16));
    }

    /**
     * Membungkus kode Python siswa dengan runner terisolasi yang menangkap
     * stdout/stderr debugging, memproteksi sys.exit(), menjalankan setiap test case
     * dalam try-except terpisah, dan mencetak payload JSON bertoken unik.
     *
     * @param  Collection<int, TestCase>  $testCases
     */
    public function wrap(string $userCode, Collection $testCases, string $token): string
    {
        $serializedCases = $testCases->values()->map(function (TestCase $tc): array {
            $assertionCode = is_string($tc->assertion_code) ? trim($tc->assertion_code) : '';

            return [
                'id' => $tc->id,
                'mode' => $assertionCode !== '' ? 'assertion' : 'stdin',
                'assertion_code' => $assertionCode !== '' ? $assertionCode : null,
                'input' => $tc->input_data !== null
                    ? (is_array($tc->input_data) ? ($tc->input_data['stdin'] ?? json_encode($tc->input_data)) : (string) $tc->input_data)
                    : (string) ($tc->input ?? ''),
                'expected_output' => (string) ($tc->expected_output ?? ''),
                'is_hidden' => (bool) $tc->is_hidden,
                'weight' => max(0, (int) $tc->weight),
            ];
        })->all();

        $encodedUserCode = json_encode($userCode, JSON_THROW_ON_ERROR);
        $encodedCases = json_encode($serializedCases, JSON_THROW_ON_ERROR);
        $encodedToken = json_encode($token, JSON_THROW_ON_ERROR);

        return <<<PYTHON
import contextlib
import io
import json
import sys
import traceback

_USER_CODE = {$encodedUserCode}
_TEST_CASES = {$encodedCases}
_RESULT_TOKEN = {$encodedToken}
_MAX_STDOUT_LEN = 10000


def _truncate(text, limit=_MAX_STDOUT_LEN):
    if text is None:
        return ""
    if len(text) <= limit:
        return text
    return text[:limit] + "\\n... [output dipotong karena terlalu panjang]"


def _normalize_output(text):
    if text is None:
        return ""
    lines = [line.rstrip() for line in str(text).replace("\\r\\n", "\\n").split("\\n")]
    return "\\n".join(lines).rstrip("\\n")


def _run_grading():
    stdout_buffer = io.StringIO()
    stderr_buffer = io.StringIO()
    results = []

    try:
        compiled = compile(_USER_CODE, "<solution.py>", "exec")
    except SyntaxError as exc:
        return {
            "compile_error": f"SyntaxError pada baris {exc.lineno}: {exc.msg}",
            "runtime_error": None,
            "user_stdout": "",
            "user_stderr": "",
            "results": [],
        }

    has_assertion_cases = any(tc["mode"] == "assertion" for tc in _TEST_CASES)
    shared_namespace = {"__name__": "__main__"}

    if has_assertion_cases:
        try:
            with contextlib.redirect_stdout(stdout_buffer), contextlib.redirect_stderr(stderr_buffer):
                exec(compiled, shared_namespace)
        except SystemExit as exc:
            return {
                "compile_error": None,
                "runtime_error": f"Program dihentikan lebih awal oleh sys.exit({exc.code}).",
                "user_stdout": _truncate(stdout_buffer.getvalue()),
                "user_stderr": _truncate(stderr_buffer.getvalue()),
                "results": [],
            }
        except BaseException:
            return {
                "compile_error": None,
                "runtime_error": traceback.format_exc(limit=5),
                "user_stdout": _truncate(stdout_buffer.getvalue()),
                "user_stderr": _truncate(stderr_buffer.getvalue()),
                "results": [],
            }

    for tc in _TEST_CASES:
        tc_id = tc["id"]
        mode = tc["mode"]
        case_stdout = io.StringIO()
        case_stderr = io.StringIO()
        is_passed = False
        actual_output = ""
        error_msg = None

        try:
            if mode == "assertion":
                case_ns = dict(shared_namespace)
                with contextlib.redirect_stdout(case_stdout), contextlib.redirect_stderr(case_stderr):
                    exec(tc["assertion_code"], case_ns)
                actual_output = case_stdout.getvalue()
                is_passed = True
            else:
                original_stdin = sys.stdin
                sys.stdin = io.StringIO(tc["input"])
                case_ns = {"__name__": "__main__"}
                try:
                    with contextlib.redirect_stdout(case_stdout), contextlib.redirect_stderr(case_stderr):
                        exec(compiled, case_ns)
                finally:
                    sys.stdin = original_stdin

                actual_output = case_stdout.getvalue()
                is_passed = _normalize_output(actual_output) == _normalize_output(tc["expected_output"])
        except SystemExit as exc:
            actual_output = case_stdout.getvalue()
            error_msg = f"Kode memanggil sys.exit({exc.code}) pada test case ini."
        except AssertionError as exc:
            actual_output = case_stdout.getvalue()
            error_msg = f"AssertionError: {exc}" if str(exc) else "AssertionError"
        except BaseException:
            actual_output = case_stdout.getvalue()
            error_msg = traceback.format_exc(limit=4)

        if mode == "assertion" and case_stdout.getvalue():
            stdout_buffer.write(case_stdout.getvalue())
        elif mode == "stdin" and tc == _TEST_CASES[0] and case_stdout.getvalue():
            stdout_buffer.write(case_stdout.getvalue())

        if case_stderr.getvalue():
            stderr_buffer.write(case_stderr.getvalue())

        results.append({
            "test_case_id": tc_id,
            "is_hidden": tc["is_hidden"],
            "weight": tc["weight"],
            "passed": bool(is_passed),
            "actual_output": _truncate(actual_output, 4000),
            "error": error_msg,
        })

    return {
        "compile_error": None,
        "runtime_error": None,
        "user_stdout": _truncate(stdout_buffer.getvalue()),
        "user_stderr": _truncate(stderr_buffer.getvalue()),
        "results": results,
    }


def _main():
    payload = _run_grading()
    sys.__stdout__.write("\\n" + _RESULT_TOKEN + ":" + json.dumps(payload, ensure_ascii=False) + "\\n")
    sys.__stdout__.flush()


if __name__ == "__main__":
    _main()
PYTHON;
    }

    /**
     * Mem-parsing baris payload JSON berdasarkan token unik dari stdout Judge0.
     *
     * @return array<string, mixed>|null
     */
    public function parseOutput(?string $stdout, string $token): ?array
    {
        if (! is_string($stdout) || $stdout === '') {
            return null;
        }

        $prefix = $token.':';
        $lines = preg_split('/\r\n|\r|\n/', $stdout) ?: [];

        foreach (array_reverse($lines) as $line) {
            if (str_starts_with($line, $prefix)) {
                $jsonPart = substr($line, strlen($prefix));

                try {
                    $decoded = json_decode($jsonPart, true, 512, JSON_THROW_ON_ERROR);

                    return is_array($decoded) ? $decoded : null;
                } catch (JsonException) {
                    return null;
                }
            }
        }

        return null;
    }
}
