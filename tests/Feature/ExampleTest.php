<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_landing_page_buttons_redirect_guests_to_login(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $loginUrl = route('login');

        // All 4 CTA buttons point to login when user is guest
        $response->assertSee('href="'.$loginUrl.'"', false);
    }

    public function test_landing_page_buttons_redirect_authenticated_user_to_modules_and_exercises(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('href="'.route('modul.index').'"', false);
        $response->assertSee('href="'.route('latihan.index').'"', false);
    }

    public function test_admin_avatar_links_to_admin_dashboard_in_landing_and_learner_navigation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $dashboardUrl = route('admin.dashboard');

        $this->actingAs($admin)
            ->get(route('beranda'))
            ->assertOk()
            ->assertSee('href="'.$dashboardUrl.'" title="Dashboard Admin"', false)
            ->assertDontSee('href="'.route('profile').'" title="Profil"', false);

        $this->get(route('latihan.index'))
            ->assertOk()
            ->assertSee('href="'.$dashboardUrl.'" title="Dashboard Admin"', false)
            ->assertDontSee('href="'.route('profile').'" title="Profil"', false);
    }

    public function test_learner_avatar_keeps_linking_to_profile(): void
    {
        $learner = User::factory()->create(['role' => 'learner']);

        $this->actingAs($learner)
            ->get(route('beranda'))
            ->assertOk()
            ->assertSee('href="'.route('profile').'" title="Profil"', false);
    }

    public function test_learner_logout_controls_open_confirmation_modal(): void
    {
        $learner = User::factory()->create(['role' => 'learner']);

        $this->actingAs($learner)
            ->get(route('beranda'))
            ->assertOk()
            ->assertSee('logout-confirmation-modal')
            ->assertSee('Keluar dari akun?')
            ->assertSee('Ya, keluar')
            ->assertSee('showModal()', false)
            ->assertSee('action="'.route('logout').'"', false);
    }

    public function test_admin_logout_control_opens_confirmation_modal(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('logout-confirmation-modal')
            ->assertSee('Keluar dari akun?')
            ->assertSee('Ya, keluar')
            ->assertSee('showModal()', false)
            ->assertSee('action="'.route('logout').'"', false);
    }

    public function test_unauthenticated_users_cannot_access_modules_or_exercises_directly(): void
    {
        $this->get('/modul')->assertRedirect(route('login'));
        $this->get('/latihan')->assertRedirect(route('login'));
    }
}
