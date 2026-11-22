<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginLogoutIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Memastikan pengunjung dapat membuka halaman login dan melihat komponen form yang lengkap.
     */
    public function test_login_page_can_be_accessed_by_guests_with_required_form_elements(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSee('Selamat Datang Kembali');
        $response->assertSee('name="email"', false);
        $response->assertSee('name="password"', false);
        $response->assertSee('name="remember"', false);
        $response->assertSee('Masuk dengan Google');
        $response->assertSee(route('register'));
    }

    /**
     * Memastikan learner dapat login dengan kredensial valid dan diarahkan ke beranda.
     */
    public function test_learner_can_login_with_valid_credentials_and_is_redirected_to_home(): void
    {
        $learner = User::factory()->create([
            'email' => 'sukma@example.com',
            'password' => Hash::make('PasswordAman123!'),
            'role' => 'learner',
            'last_login_at' => null,
        ]);

        $response = $this->post(route('login'), [
            'email' => 'sukma@example.com',
            'password' => 'PasswordAman123!',
        ]);

        $response->assertRedirect(route('beranda'));
        $response->assertSessionHas('status', 'Berhasil masuk ke PyBeginner!');
        $this->assertAuthenticatedAs($learner);

        // Memastikan last_login_at diperbarui
        $learner->refresh();
        $this->assertNotNull($learner->last_login_at);
    }

    /**
     * Memastikan admin diarahkan ke dashboard admin setelah berhasil login.
     */
    public function test_admin_is_redirected_to_admin_dashboard_upon_successful_login(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@pybeginner.com',
            'password' => Hash::make('AdminSecret123!'),
            'role' => 'admin',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'admin@pybeginner.com',
            'password' => 'AdminSecret123!',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('status', 'Selamat datang di Panel Admin PyBeginner!');
        $this->assertAuthenticatedAs($admin);
    }

    /**
     * Memastikan login gagal ketika kata sandi yang dimasukkan salah.
     */
    public function test_login_fails_when_password_is_incorrect(): void
    {
        User::factory()->create([
            'email' => 'sukma@example.com',
            'password' => Hash::make('PasswordBenar123!'),
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'sukma@example.com',
            'password' => 'PasswordSalah123!',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * Memastikan login gagal ketika email belum terdaftar di database.
     */
    public function test_login_fails_when_email_is_not_registered(): void
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'belumterdaftar@example.com',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * Memastikan validasi mewajibkan input email dan kata sandi diisi.
     */
    public function test_login_validation_requires_email_and_password(): void
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => '',
            'password' => '',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    /**
     * Memastikan validasi menolak format email yang tidak valid.
     */
    public function test_login_validation_rejects_invalid_email_format(): void
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'bukan-format-email',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * Memastikan pengguna yang sudah login dialihkan dari halaman login oleh middleware guest.
     */
    public function test_authenticated_user_is_redirected_away_from_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('login'));

        $response->assertRedirect(route('beranda'));
    }

    /**
     * Memastikan fitur 'Ingat Saya' (remember me) menyertakan cookie recaller sesi panjang.
     */
    public function test_login_with_remember_me_sets_persistent_recaller_cookie(): void
    {
        $user = User::factory()->create([
            'email' => 'remember@example.com',
            'password' => Hash::make('PasswordAman123!'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'remember@example.com',
            'password' => 'PasswordAman123!',
            'remember' => 'on',
        ]);

        $response->assertRedirect(route('beranda'));
        $this->assertAuthenticatedAs($user);

        $recallerName = Auth::guard()->getRecallerName();
        $response->assertCookieNotExpired($recallerName);
    }

    /**
     * Memastikan pengguna yang login dapat logout dan sesi pengguna dibersihkan.
     */
    public function test_authenticated_user_can_logout_successfully(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('beranda'));
        $response->assertSessionHas('status', 'Anda telah berhasil keluar.');
        $this->assertGuest();
    }

    /**
     * Memastikan pengguna yang belum login (guest) tidak dapat mengakses endpoint logout.
     */
    public function test_unauthenticated_guest_cannot_execute_logout(): void
    {
        $response = $this->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    /**
     * Memastikan proses logout menghapus cookie 'remember me' yang sebelumnya aktif.
     */
    public function test_logout_expires_existing_remember_me_cookie(): void
    {
        $user = User::factory()->create([
            'email' => 'remember-test@example.com',
            'password' => Hash::make('PasswordAman123!'),
        ]);

        $loginResponse = $this->post(route('login'), [
            'email' => 'remember-test@example.com',
            'password' => 'PasswordAman123!',
            'remember' => 'on',
        ]);

        $recallerName = Auth::guard()->getRecallerName();
        $cookieValue = $loginResponse->getCookie($recallerName)?->getValue();

        $this->assertNotNull($cookieValue, 'Cookie recaller tidak ditemukan.');

        $logoutResponse = $this
            ->withCookie($recallerName, $cookieValue)
            ->post(route('logout'));

        $logoutResponse->assertRedirect(route('beranda'));
        $logoutResponse->assertCookieExpired($recallerName);
        $this->assertGuest();
    }
}
