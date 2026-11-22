<?php

namespace Tests\Unit;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use App\Services\Judge0Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewInstance;
use Tests\TestCase;

class UserAdminSettingsUnitTest extends TestCase
{
    /**
     * Memastikan route pengaturan profil user terdaftar dengan metode HTTP dan middleware auth.
     */
    public function test_user_profile_routes_are_registered_and_protected_by_auth_middleware(): void
    {
        $routes = [
            'profile' => 'GET',
            'profile.update' => 'PUT',
            'profile.avatar' => 'POST',
        ];

        foreach ($routes as $name => $method) {
            $this->assertTrue(Route::has($name), "Route '{$name}' belum terdaftar.");

            $route = Route::getRoutes()->getByName($name);
            $this->assertContains($method, $route->methods());
            $this->assertContains('auth', $route->gatherMiddleware());
        }
    }

    /**
     * Memastikan route pengaturan admin terdaftar dengan metode HTTP serta middleware auth dan admin.
     */
    public function test_admin_settings_routes_are_registered_and_protected_by_auth_and_admin_middleware(): void
    {
        $routes = [
            'admin.pengaturan' => 'GET',
            'admin.pengaturan.profile' => 'PUT',
            'admin.pengaturan.password' => 'PUT',
        ];

        foreach ($routes as $name => $method) {
            $this->assertTrue(Route::has($name), "Route '{$name}' belum terdaftar.");

            $route = Route::getRoutes()->getByName($name);
            $this->assertContains($method, $route->methods());
            $this->assertContains('auth', $route->gatherMiddleware());
            $this->assertContains('admin', $route->gatherMiddleware());
        }
    }

    /**
     * Memastikan template view pengaturan profil user dan admin tersedia.
     */
    public function test_user_profile_and_admin_settings_views_exist(): void
    {
        $this->assertTrue(View::exists('profile'));
        $this->assertTrue(View::exists('admin.pengaturan'));
    }

    /**
     * Memastikan ProfileController::show mengembalikan view 'profile' dengan data user yang login.
     */
    public function test_profile_controller_show_returns_profile_view_with_authenticated_user(): void
    {
        $learner = User::factory()->make([
            'name' => 'Siswa Pengaturan',
            'email' => 'siswa@example.com',
            'role' => 'learner',
        ]);
        Auth::setUser($learner);

        $controller = new ProfileController;
        $view = $controller->show();

        $this->assertInstanceOf(ViewInstance::class, $view);
        $this->assertSame('profile', $view->name());
        $this->assertSame($learner, $view->getData()['user']);
    }

    /**
     * Memastikan AdminController::pengaturan mengembalikan view 'admin.pengaturan' dengan data admin yang login.
     */
    public function test_admin_controller_pengaturan_returns_admin_settings_view_with_authenticated_admin(): void
    {
        $admin = User::factory()->make([
            'name' => 'Admin Pengaturan',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);
        Auth::setUser($admin);

        $controller = new AdminController(new Judge0Service);
        $view = $controller->pengaturan();

        $this->assertInstanceOf(ViewInstance::class, $view);
        $this->assertSame('admin.pengaturan', $view->name());
        $this->assertSame($admin, $view->getData()['admin']);
    }

    /**
     * Memastikan atribut fillable model User mendukung semua kolom pengaturan profil user dan admin.
     */
    public function test_user_fillable_attributes_support_profile_and_admin_settings_fields(): void
    {
        $fillable = (new User)->getFillable();

        foreach (['name', 'email', 'password', 'phone', 'bio', 'avatar'] as $attribute) {
            $this->assertContains($attribute, $fillable, "Kolom '{$attribute}' harus bersifat fillable pada model User.");
        }
    }
}
