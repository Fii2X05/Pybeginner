<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class AuthController extends Controller
{
    /**
     * Display the login view.
     */
    public function showLoginForm(): View
    {
        return view('login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(Request $request): RedirectResponse
    {
        // Validasi verifikasi captcha puzzle anti-bot
        if (! app()->environment('testing') || $request->filled('captcha_token') || $request->has('enforce_captcha')) {
            $submittedToken = (string) $request->input('captcha_token');
            $isValidCaptcha = false;

            // 1. Cek kesesuaian melalui Session
            /** @var array{token: string, verified_at: int}|null $verifiedCaptcha */
            $verifiedCaptcha = $request->session()->get('puzzle_captcha_verified');
            if ($verifiedCaptcha && ! empty($submittedToken) && hash_equals($verifiedCaptcha['token'], $submittedToken)) {
                $isValidCaptcha = true;
                $request->session()->forget('puzzle_captcha_verified');
            }

            // 2. Cek kesesuaian melalui Cryptographic HMAC Token (menghindari race-condition session file)
            if (! $isValidCaptcha && ! empty($submittedToken) && str_contains($submittedToken, '.')) {
                [$payload, $signature] = explode('.', $submittedToken, 2);
                $expectedSig = hash_hmac('sha256', $payload, (string) config('app.key'));

                if (hash_equals($expectedSig, $signature)) {
                    $data = json_decode((string) base64_decode($payload), true);
                    if (is_array($data) && isset($data['ts']) && (now()->timestamp - (int) $data['ts']) <= 600) {
                        $cacheKey = 'captcha_used:'.md5($submittedToken);
                        if (Cache::add($cacheKey, true, 600)) {
                            $isValidCaptcha = true;
                        }
                    }
                }
            }

            if (! $isValidCaptcha) {
                return back()->withErrors([
                    'captcha' => 'Silakan selesaikan verifikasi puzzle keamanan terlebih dahulu.',
                ])->withInput($request->except('password'));
            }
        }

        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            Auth::user()->forceFill(['last_login_at' => now()])->save();

            if (Auth::user()->role === 'admin') {
                $intended = $request->session()->pull('url.intended');
                if ($intended && str_contains($intended, '/admin')) {
                    return redirect()->to($intended)->with('status', 'Selamat datang di Panel Admin PyBeginner!');
                }

                return redirect()->route('admin.dashboard')->with('status', 'Selamat datang di Panel Admin PyBeginner!');
            }

            return redirect()->intended(route('beranda'))->with('status', 'Berhasil masuk ke PyBeginner!');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Display the registration view.
     */
    public function showRegisterForm(): View
    {
        return view('register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'learner',
        ]);

        Auth::login($user);
        $user->forceFill(['last_login_at' => now()])->save();

        $request->session()->regenerate();

        return redirect()->route('beranda')->with('status', 'Pendaftaran berhasil! Selamat datang di PyBeginner.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('beranda')->with('status', 'Anda telah berhasil keluar.');
    }

    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle(Request $request): RedirectResponse
    {
        if (blank(config('services.google.client_id')) || blank(config('services.google.client_secret'))) {
            return redirect()->route('login')->withErrors([
                'google_error' => 'Fitur Google Login belum dikonfigurasi. Harap isi GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET pada file .env.',
            ]);
        }

        $request->session()->put('remember_login', $request->boolean('remember'));

        return Socialite::driver('google')
            ->stateless()
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    /**
     * Obtain the user information from Google and authenticate.
     */
    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user !== null) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar() ?? $user->avatar,
                ]);
            } else {
                $user = User::create([
                    'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Pengguna Google',
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'role' => 'learner',
                    'email_verified_at' => now(),
                ]);
            }

            $user->forceFill(['last_login_at' => now()])->save();
            Auth::login($user, $request->session()->pull('remember_login', false));

            $request->session()->regenerate();

            if ($user->role === 'admin') {
                $intended = $request->session()->pull('url.intended');
                if ($intended && str_contains($intended, '/admin')) {
                    return redirect()->to($intended)->with('status', 'Selamat datang di Panel Admin PyBeginner!');
                }

                return redirect()->route('admin.dashboard')->with('status', 'Selamat datang di Panel Admin PyBeginner!');
            }

            return redirect()->intended(route('beranda'))->with('status', 'Berhasil masuk dengan akun Google!');
        } catch (Throwable $e) {
            Log::error('Google OAuth callback error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Sesi autentikasi Google tidak valid atau dibatalkan. Silakan coba kembali.';
            }

            return redirect()->route('login')->withErrors([
                'google_error' => 'Gagal masuk menggunakan Google: '.$message,
            ]);
        }
    }
}
