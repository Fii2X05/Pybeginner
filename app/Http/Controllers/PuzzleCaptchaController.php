<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PuzzleCaptchaController extends Controller
{
    /**
     * Daftar gambar statis ringan untuk latar puzzle (berukuran 320x160).
     *
     * @var array<int, string>
     */
    protected array $images = [
        'images/captcha/puzzle-1.png',
        'images/captcha/puzzle-2.png',
        'images/captcha/puzzle-3.png',
    ];

    /**
     * Generate token dan koordinat target puzzle yang baru.
     * Tidak membebani server karena gambar bersifat statis dan pemotongan puzzle
     * dilakukan secara instan di sisi klien melalui HTML5 Canvas.
     */
    public function challenge(Request $request): JsonResponse
    {
        $selectedImage = $this->images[array_rand($this->images)];

        // Tentukan koordinat target acak yang aman di dalam kanvas 320x160
        // Ukuran keping puzzle adalah 44x44
        $targetX = random_int(70, 240);
        $targetY = random_int(20, 95);
        $token = Str::random(40);

        $request->session()->put('puzzle_captcha_challenge', [
            'token' => $token,
            'target_x' => $targetX,
            'target_y' => $targetY,
            'image' => $selectedImage,
            'created_at' => now()->timestamp,
            'attempts' => 0,
        ]);

        return response()->json([
            'token' => $token,
            'image_url' => asset($selectedImage),
            'target_x' => $targetX,
            'target_y' => $targetY,
            'piece_width' => 44,
            'piece_radius' => 8,
            'canvas_width' => 320,
            'canvas_height' => 160,
        ]);
    }

    /**
     * Verifikasi hasil geseran puzzle dari pengguna.
     */
    public function verify(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'position_x' => ['required', 'numeric'],
            'duration' => ['required', 'numeric'],
            'trail' => ['nullable', 'array'],
        ]);

        /** @var array{token: string, target_x: int, target_y: int, image: string, created_at: int, attempts: int}|null $challenge */
        $challenge = $request->session()->get('puzzle_captcha_challenge');

        if (! $challenge || ! hash_equals($challenge['token'], (string) $validated['token'])) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi verifikasi tidak valid atau telah kedaluwarsa. Silakan muat ulang.',
            ], 422);
        }

        // Kedaluwarsa dalam 5 menit (300 detik)
        if (now()->timestamp - $challenge['created_at'] > 300) {
            $request->session()->forget('puzzle_captcha_challenge');

            return response()->json([
                'success' => false,
                'message' => 'Waktu verifikasi telah habis (kedaluwarsa). Silakan muat ulang.',
            ], 422);
        }

        // Batas maksimal percobaan salah
        if ($challenge['attempts'] >= 5) {
            $request->session()->forget('puzzle_captcha_challenge');

            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak percobaan gagal. Silakan muat ulang puzzle baru.',
            ], 422);
        }

        // Pemeriksaan bot dasar: durasi geser manusiawi (minimal 200 milidetik)
        if ($validated['duration'] < 200) {
            $challenge['attempts']++;
            $request->session()->put('puzzle_captcha_challenge', $challenge);

            return response()->json([
                'success' => false,
                'message' => 'Gerakan terdeteksi terlalu cepat. Silakan geser secara alami.',
            ], 422);
        }

        // Toleransi selisih posisi: maksimal 6 piksel
        $diff = abs((float) $validated['position_x'] - (float) $challenge['target_x']);

        if ($diff > 6.0) {
            $challenge['attempts']++;
            $request->session()->put('puzzle_captcha_challenge', $challenge);

            return response()->json([
                'success' => false,
                'message' => 'Posisi puzzle belum pas. Silakan coba lagi.',
            ], 422);
        }

        // Verifikasi sukses: buat token verifikasi sekali pakai
        $verificationToken = Str::random(48);

        $request->session()->put('puzzle_captcha_verified', [
            'token' => $verificationToken,
            'verified_at' => now()->timestamp,
        ]);

        // Hapus tantangan setelah berhasil
        $request->session()->forget('puzzle_captcha_challenge');

        return response()->json([
            'success' => true,
            'verification_token' => $verificationToken,
            'message' => 'Verifikasi berhasil!',
        ]);
    }
}
