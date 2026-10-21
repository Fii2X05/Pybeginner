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

    public function test_unauthenticated_users_cannot_access_modules_or_exercises_directly(): void
    {
        $this->get('/modul')->assertRedirect(route('login'));
        $this->get('/latihan')->assertRedirect(route('login'));
    }
}
