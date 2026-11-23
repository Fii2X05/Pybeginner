<?php

namespace Tests\Unit;

use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\Exercise;
use App\Models\User;
use App\Services\Judge0Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewInstance;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class AdminPageUnitTest extends TestCase
{
    /**
     * Memastikan AdminMiddleware mengalihkan pengguna yang belum login (guest) ke halaman login.
     */
    public function test_admin_middleware_redirects_guest_users_to_login(): void
    {
        Auth::logout();

        $middleware = new AdminMiddleware;
        $request = Request::create('/admin/dashboard', 'GET');

        $response = $middleware->handle($request, fn () => response('OK'));

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame(route('login'), $response->getTargetUrl());
    }

    /**
     * Memastikan AdminMiddleware menolak akses dengan HTTP 403 untuk pengguna biasa (learner).
     */
    public function test_admin_middleware_denies_access_with_403_for_learner_users(): void
    {
        $learner = User::factory()->make(['role' => 'learner']);
        Auth::setUser($learner);

        $middleware = new AdminMiddleware;
        $request = Request::create('/admin/dashboard', 'GET');

        try {
            $middleware->handle($request, fn () => response('OK'));
            $this->fail('Seharusnya AdminMiddleware melemparkan HttpException 403.');
        } catch (HttpException $exception) {
            $this->assertSame(403, $exception->getStatusCode());
        }
    }

    /**
     * Memastikan AdminMiddleware meneruskan request untuk pengguna dengan role admin.
     */
    public function test_admin_middleware_allows_request_to_proceed_for_admin_users(): void
    {
        $admin = User::factory()->make(['role' => 'admin']);
        Auth::setUser($admin);

        $middleware = new AdminMiddleware;
        $request = Request::create('/admin/dashboard', 'GET');

        $response = $middleware->handle($request, fn () => response('OK', 200));

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('OK', $response->getContent());
    }

    /**
     * Memastikan semua route utama panel admin terdaftar dan dilindungi middleware auth serta admin.
     */
    public function test_core_admin_routes_are_registered_and_protected_by_auth_and_admin_middleware(): void
    {
        $adminRouteNames = [
            'admin.index',
            'admin.dashboard',
            'admin.materi',
            'admin.latihan',
            'admin.siswa',
            'admin.penilaian',
            'admin.pengaturan',
        ];

        foreach ($adminRouteNames as $routeName) {
            $this->assertTrue(Route::has($routeName), "Route '{$routeName}' belum terdaftar.");

            $middleware = Route::getRoutes()->getByName($routeName)->gatherMiddleware();
            $this->assertContains('auth', $middleware, "Route '{$routeName}' harus memakai middleware auth.");
            $this->assertContains('admin', $middleware, "Route '{$routeName}' harus memakai middleware admin.");
        }
    }

    /**
     * Memastikan semua file view utama panel admin tersedia.
     */
    public function test_admin_page_view_templates_exist(): void
    {
        $adminViews = [
            'admin.dashboard',
            'admin.materi',
            'admin.latihan',
            'admin.siswa',
            'admin.penilaian',
            'admin.pengaturan',
        ];

        foreach ($adminViews as $viewName) {
            $this->assertTrue(View::exists($viewName), "View '{$viewName}' tidak ditemukan.");
        }
    }

    /**
     * Memastikan AdminController::answerKey mengembalikan view dan struktur data yang benar.
     */
    public function test_admin_controller_answer_key_returns_expected_view_and_data(): void
    {
        $exercise = new Exercise([
            'title' => 'Latihan Uji',
            'starter_code' => "print('Halo')\n",
        ]);

        $controller = new AdminController(new Judge0Service);
        $view = $controller->answerKey($exercise);

        $this->assertInstanceOf(ViewInstance::class, $view);
        $this->assertSame('admin.answer-key', $view->name());
        $this->assertSame($exercise, $view->getData()['exercise']);
        $this->assertSame("print('Halo')\n", $view->getData()['sourceCode']);
        $this->assertNull($view->getData()['results']);
        $this->assertNull($view->getData()['error']);
    }
}
