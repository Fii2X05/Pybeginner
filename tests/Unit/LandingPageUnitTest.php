<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class LandingPageUnitTest extends TestCase
{
    /**
     * Memastikan route 'beranda' terdaftar dan mengarah ke path root ('/').
     */
    public function test_beranda_route_is_registered_and_resolves_to_root_path(): void
    {
        $this->assertTrue(Route::has('beranda'));
        $this->assertSame(url('/'), route('beranda'));
    }

    /**
     * Memastikan file template view landing page ('landing') ada dan dapat ditemukan.
     */
    public function test_landing_page_view_file_exists(): void
    {
        $this->assertTrue(View::exists('landing'));
    }

    /**
     * Memastikan semua route yang ditautkan di landing page (login, modul, latihan) terdaftar dengan benar.
     */
    public function test_landing_page_dependent_route_names_exist(): void
    {
        $requiredRoutes = [
            'login',
            'register',
            'modul.index',
            'latihan.index',
        ];

        foreach ($requiredRoutes as $routeName) {
            $this->assertTrue(
                Route::has($routeName),
                "Route '{$routeName}' yang dibutuhkan landing page belum terdaftar."
            );
        }
    }
}
