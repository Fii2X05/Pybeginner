<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\ModulSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModulProgressTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ModulSeeder::class);
    }

    public function test_guest_can_view_modul_list_with_default_belum_mulai_status(): void
    {
        $response = $this->get(route('modul.index'));

        $response->assertStatus(200);
        $response->assertSee('Belum Mulai');
        $response->assertSee('Mulai Modul');
    }

    public function test_authenticated_user_initial_status_is_belum_mulai(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('modul.index'));

        $response->assertStatus(200);
        $response->assertSee('Belum Mulai');
        $response->assertSee('Mulai Modul');
    }

    public function test_visiting_detail_sets_module_to_in_progress(): void
    {
        $user = User::factory()->create();

        $detailResponse = $this->actingAs($user)->get(route('modul.detail.01'));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Sedang Berjalan');

        $indexResponse = $this->actingAs($user)->get(route('modul.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Sedang Berjalan');
        $indexResponse->assertSee('Lanjutkan Belajar');
    }

    public function test_toggling_complete_marks_module_as_selesai_and_allows_toggling_back(): void
    {
        $user = User::factory()->create();

        // 1. Visit detail to initiate
        $this->actingAs($user)->get(route('modul.detail.01'));

        // 2. Toggle complete
        $postResponse = $this->actingAs($user)->post(route('modul.toggle-complete', ['nomor' => 1]));
        $postResponse->assertRedirect();

        // 3. Check index shows 'Tinjau Ulang' and 'Selesai'
        $indexResponse = $this->actingAs($user)->get(route('modul.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Selesai');
        $indexResponse->assertSee('Tinjau Ulang');

        // 4. Toggle back to in_progress
        $toggleBackResponse = $this->actingAs($user)->post(route('modul.toggle-complete', ['nomor' => 1]));
        $toggleBackResponse->assertRedirect();

        $indexResponse2 = $this->actingAs($user)->get(route('modul.index'));
        $indexResponse2->assertStatus(200);
        $indexResponse2->assertSee('Lanjutkan Belajar');
    }
}
