import pytest
from playwright.sync_api import APIRequestContext, Playwright

JUDGE0_URL = "http://localhost:2358"

@pytest.fixture(scope="session")
def api_context(playwright: Playwright):
    request_context = playwright.request.new_context(
        base_url=JUDGE0_URL,
        timeout=40000  # Timeout 40 detik untuk mengantisipasi TLE 30s
    )
    yield request_context
    request_context.dispose()


def test_judge0_extra_python_accepted(api_context: APIRequestContext):
    """Skenario 1: Kode Python valid (ID 10 di judge0-extra)"""
    payload = {
        "source_code": "print('Hello World')",
        "language_id": 10,
        "expected_output": "Hello World\n",
        "max_processes_and_or_threads": 8
    }

    # Playwright serializes data as JSON and sets the application/json content type.
    response = api_context.post(
        "/submissions?base64_encoded=false&wait=true", 
        data=payload
    )
    assert response.ok, f"Gagal terhubung ke Judge0: {response.status}"

    data = response.json()
    assert data["status"]["id"] == 3  # Status ID 3 = Accepted
    assert data["stdout"] == "Hello World\n"

    grade = 100 if data["status"]["id"] == 3 else 0
    assert grade == 100


def test_judge0_extra_infinite_loop_tle(api_context: APIRequestContext):
    """Skenario 2: Kode Infinite Loop (Batas CPU maksimum Judge0)"""
    payload = {
        "source_code": "while True:\n    pass",
        "language_id": 10,
        "cpu_time_limit": 20.0,
        "max_processes_and_or_threads": 8
    }

    response = api_context.post(
        "/submissions?base64_encoded=false&wait=true", 
        data=payload
    )
    assert response.ok, f"Gagal terhubung ke Judge0: {response.status}"

    data = response.json()
    assert data["status"]["id"] == 5  # Status ID 5 = Time Limit Exceeded
    
    grade = 100 if data["status"]["id"] == 3 else 0
    assert grade == 0