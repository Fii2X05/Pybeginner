<?php

namespace Tests\Unit;

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewInstance;
use Tests\TestCase;

class LoginLogoutUnitTest extends TestCase
{
    /**
     * Memastikan seluruh route login dan logout terdaftar dengan metode HTTP yang sesuai.
     */
    public function test_login_and_logout_routes_are_registered_with_expected_http_methods(): void
    {
        $this->assertTrue(Route::has('login'));
        $this->assertTrue(Route::has('login.store'));
        $this->assertTrue(Route::has('logout'));
        $this->assertTrue(Route::has('auth.google'));
        $this->assertTrue(Route::has('auth.google.callback'));

        $this->assertContains('GET', Route::getRoutes()->getByName('login')->methods());
        $this->assertContains('POST', Route::getRoutes()->getByName('login.store')->methods());
        $this->assertContains('POST', Route::getRoutes()->getByName('logout')->methods());
    }

    /**
     * Memastikan route halaman dan proses login dilindungi oleh middleware guest.
     */
    public function test_login_routes_are_protected_by_guest_middleware(): void
    {
        $loginRoute = Route::getRoutes()->getByName('login');
        $loginStoreRoute = Route::getRoutes()->getByName('login.store');

        $this->assertContains('guest', $loginRoute->gatherMiddleware());
        $this->assertContains('guest', $loginStoreRoute->gatherMiddleware());
    }

    /**
     * Memastikan route logout dilindungi oleh middleware auth.
     */
    public function test_logout_route_is_protected_by_auth_middleware(): void
    {
        $logoutRoute = Route::getRoutes()->getByName('logout');

        $this->assertContains('auth', $logoutRoute->gatherMiddleware());
    }

    /**
     * Memastikan template view login tersedia.
     */
    public function test_login_view_template_exists(): void
    {
        $this->assertTrue(View::exists('login'));
    }

    /**
     * Memastikan AuthController::showLoginForm mengembalikan view 'login'.
     */
    public function test_auth_controller_show_login_form_returns_login_view(): void
    {
        $controller = new AuthController;
        $view = $controller->showLoginForm();

        $this->assertInstanceOf(ViewInstance::class, $view);
        $this->assertSame('login', $view->name());
    }
}
