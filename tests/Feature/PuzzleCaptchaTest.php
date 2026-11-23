<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PuzzleCaptchaTest extends TestCase
{
    use RefreshDatabase;

    public function test_challenge_endpoint_returns_json_and_stores_session_data(): void
    {
        $response = $this->get('/captcha/puzzle/challenge');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
            'image_url',
            'target_x',
            'target_y',
            'piece_width',
            'piece_radius',
            'canvas_width',
            'canvas_height',
        ]);

        $this->assertNotNull(session('puzzle_captcha_challenge'));
        $challenge = session('puzzle_captcha_challenge');
        $this->assertIsArray($challenge);
        $this->assertArrayHasKey('token', $challenge);
        $this->assertArrayHasKey('target_x', $challenge);
        $this->assertArrayHasKey('target_y', $challenge);
    }

    public function test_verify_fails_with_invalid_token(): void
    {
        $this->withSession([
            'puzzle_captcha_challenge' => [
                'token' => 'valid-token-12345678901234567890',
                'target_x' => 150,
                'target_y' => 50,
                'image' => 'images/captcha/puzzle-1.jpg',
                'created_at' => now()->timestamp,
                'attempts' => 0,
            ],
        ]);

        $response = $this->postJson('/captcha/puzzle/verify', [
            'token' => 'invalid-token',
            'position_x' => 150,
            'duration' => 600,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
    }

    public function test_verify_fails_with_too_fast_bot_like_duration(): void
    {
        $token = 'valid-token-12345678901234567890';
        $this->withSession([
            'puzzle_captcha_challenge' => [
                'token' => $token,
                'target_x' => 150,
                'target_y' => 50,
                'image' => 'images/captcha/puzzle-1.jpg',
                'created_at' => now()->timestamp,
                'attempts' => 0,
            ],
        ]);

        $response = $this->postJson('/captcha/puzzle/verify', [
            'token' => $token,
            'position_x' => 150,
            'duration' => 40, // Terlalu cepat (< 80ms)
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
        $response->assertJsonFragment([
            'message' => 'Gerakan terdeteksi terlalu cepat. Silakan geser secara alami.',
        ]);
    }

    public function test_verify_fails_when_position_is_out_of_tolerance(): void
    {
        $token = 'valid-token-12345678901234567890';
        $this->withSession([
            'puzzle_captcha_challenge' => [
                'token' => $token,
                'target_x' => 150,
                'target_y' => 50,
                'image' => 'images/captcha/puzzle-1.jpg',
                'created_at' => now()->timestamp,
                'attempts' => 0,
            ],
        ]);

        $response = $this->postJson('/captcha/puzzle/verify', [
            'token' => $token,
            'position_x' => 90, // Selisih 60px (jauh meleset)
            'duration' => 800,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Posisi puzzle belum pas. Silakan coba lagi.',
        ]);

        $updatedChallenge = session('puzzle_captcha_challenge');
        $this->assertEquals(1, $updatedChallenge['attempts']);
    }

    public function test_verify_succeeds_when_position_is_accurate(): void
    {
        $token = 'valid-token-12345678901234567890';
        $this->withSession([
            'puzzle_captcha_challenge' => [
                'token' => $token,
                'target_x' => 150,
                'target_y' => 50,
                'image' => 'images/captcha/puzzle-1.jpg',
                'created_at' => now()->timestamp,
                'attempts' => 0,
            ],
        ]);

        $response = $this->postJson('/captcha/puzzle/verify', [
            'token' => $token,
            'position_x' => 152, // Selisih 2px (dalam toleransi <= 6px)
            'duration' => 850,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Verifikasi berhasil!',
        ]);

        $this->assertNull(session('puzzle_captcha_challenge'));
        $verified = session('puzzle_captcha_verified');
        $this->assertNotNull($verified);
        $this->assertNotEmpty($verified['token']);
    }

    public function test_login_rejects_without_captcha_when_enforced(): void
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'password123',
            'enforce_captcha' => '1',
        ]);

        $response->assertSessionHasErrors(['captcha']);
        $this->assertGuest();
    }

    public function test_login_succeeds_with_valid_captcha_token(): void
    {
        User::factory()->create([
            'email' => 'sukma@example.com',
            'password' => Hash::make('password123'),
        ]);

        $verificationToken = 'verified-secret-token-abcdef';
        $this->withSession([
            'puzzle_captcha_verified' => [
                'token' => $verificationToken,
                'verified_at' => now()->timestamp,
            ],
        ]);

        $response = $this->post('/login', [
            'email' => 'sukma@example.com',
            'password' => 'password123',
            'captcha_token' => $verificationToken,
        ]);

        $response->assertRedirect(route('beranda'));
        $this->assertAuthenticated();
        // Token harus sudah dikonsumsi (single-use)
        $this->assertNull(session('puzzle_captcha_verified'));
    }
}
