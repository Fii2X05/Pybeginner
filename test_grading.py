import pytest
from playwright.sync_api import APIRequestContext, Playwright

JUDGE0_URL = "http://localhost:2358"

@pytest.fixture(scope="session")
def api_context(playwright: Playwright):
    # Set timeout default HTTP Request ke 40 detik agar tidak terputus saat nunggu TLE 30s
    request_context = playwright.request.new_context(
        base_url=JUDGE0_URL,
        timeout=40000  # 40.000 ms = 40 detik
    )
    yield request_context
    request_context.dispose()


def test_judge0_extra_python_accepted(api_context: APIRequestContext):
    """Skenario 1: Kode Python valid (ID 10 di judge0-extra)"""
    payload = {
        "source_code": "print('Hello World')",
        "language_id": 10,  # ID Python untuk judge0-extra
        "expected_output": "Hello World\n"
    }

    response = api_context.post("/submissions?base64_encoded=false&wait=true", data=payload)
    assert response.ok, f"Gagal terhubung ke Judge0: {response.status}"

    data = response.json()
    assert data["status"]["id"] == 3  # Status ID 3 = Accepted
    assert data["stdout"] == "Hello World\n"

    grade = 100 if data["status"]["id"] == 3 else 0
    assert grade == 100


def test_judge0_extra_infinite_loop_tle(api_context: APIRequestContext):
    """Skenario 2: Kode Infinite Loop (Menunggu TLE 30s dari container)"""
    payload = {
        "source_code": "while True:\n    pass",
        "language_id": 10,
        "cpu_time_limit": 30.0  # Limit 30 detik sesuai konfigurasi kamu
    }

    # Request ini bakal hold selama ~30 detik sampai container menghentikan eksekusi
    response = api_context.post(
        "/submissions?base64_encoded=false&wait=true", 
        data=payload
    )
    assert response.ok

    data = response.json()
    
    # Assert bahwa Judge0 benar-benar menangkap TLE (Status ID 5 = Time Limit Exceeded)
    assert data["status"]["id"] == 5
    assert "Time Limit Exceeded" in data["status"]["description"]

    # Kode TLE otomatis dapat nilai 0
    grade = 100 if data["status"]["id"] == 3 else 0
    assert grade == 0