<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Memastikan landing page dapat diakses dengan status HTTP 200 dan memuat konten utama.
     */
    public function test_landing_page_can_be_rendered_successfully_with_http_ok(): void
    {
        $response = $this->get(route('beranda'));

        $response->assertOk();
        $response->assertViewIs('landing');
        $response->assertSee('Belajar Python Interaktif & Gratis untuk Pemula');
        $response->assertSee('Dirancang Khusus untuk Memudahkan Pemula');
        $response->assertSee('3 Langkah Mudah Menguasai Python');
        $response->assertSee('Siap Menulis Kode Python Pertamamu Hari Ini?');
    }

    /**
     * Memastikan pengunjung (guest) melihat tombol Masuk dan tautan login di landing page.
     */
    public function test_landing_page_displays_guest_navbar_and_login_links_for_unauthenticated_visitors(): void
    {
        $response = $this->get(route('beranda'));

        $response->assertOk();
        $response->assertSee(route('login'));
        $response->assertSee('Masuk');
        $response->assertSee('Daftar Gratis');
        $response->assertDontSee('data-confirm-logout', false);
    }

    /**
     * Memastikan pengguna yang login melihat navbar learner, nama profil, dan link belajar.
     */
    public function test_landing_page_displays_learner_navbar_and_dashboard_links_for_authenticated_users(): void
    {
        $learner = User::factory()->create([
            'name' => 'Adinda Luluk',
            'role' => 'learner',
        ]);

        $response = $this->actingAs($learner)->get(route('beranda'));

        $response->assertOk();
        $response->assertSee('Adinda Luluk');
        $response->assertSee(route('modul.index'));
        $response->assertSee(route('latihan.index'));
        $response->assertSee(route('submissions.history'));
    }

    /**
     * Memastikan tombol CTA mengarah ke halaman login bagi guest dan ke modul/latihan bagi user yang login.
     */
    public function test_landing_page_cta_buttons_direct_appropriately_based_on_authentication_state(): void
    {
        $loginUrl = route('login');

        // Saat berstatus guest: tombol CTA mengarahkan ke halaman login
        $guestResponse = $this->get(route('beranda'));
        $guestResponse->assertOk();
        $guestResponse->assertSee('href="'.$loginUrl.'"', false);

        // Saat berstatus authenticated: tombol CTA mengarahkan ke modul dan latihan
        $user = User::factory()->create();
        $authResponse = $this->actingAs($user)->get(route('beranda'));
        $authResponse->assertOk();
        $authResponse->assertSee('href="'.route('modul.index').'"', false);
        $authResponse->assertSee('href="'.route('latihan.index').'"', false);
    }

    /**
     * Memastikan tombol form logout hanya ditampilkan ketika pengguna sudah terautentikasi.
     */
    public function test_logout_form_button_is_only_rendered_for_authenticated_users(): void
    {
        $guestResponse = $this->get(route('beranda'));
        $guestResponse->assertDontSee('data-confirm-logout', false);

        $user = User::factory()->create();
        $authResponse = $this->actingAs($user)->get(route('beranda'));
        $authResponse->assertSee('data-confirm-logout', false);
        $authResponse->assertSee('id="logout-confirmation"', false);
    }
}
