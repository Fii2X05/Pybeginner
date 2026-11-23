<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\AdminNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_registration_notifies_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->post(route('register.store'), [
            'name' => 'Pendaftar Baru',
            'email' => 'new-student@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('beranda'));

        $notification = $admin->notifications()->first();
        $this->assertSame('new_student', $notification->data['type']);
        $this->assertSame('Siswa baru mendaftar', $notification->data['title']);
    }

    public function test_support_report_notifies_admin_with_student_message(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create();

        $this->actingAs($student)->post(route('support.store'), [
            'category' => 'bug',
            'message' => 'Tombol latihan tidak merespons.',
        ])->assertRedirect();

        $notification = $admin->notifications()->first();
        $this->assertSame('student_feedback', $notification->data['type']);
        $this->assertSame('Laporan bug', $notification->data['title']);
        $this->assertStringContainsString('Tombol latihan tidak merespons.', $notification->data['message']);
    }

    public function test_authenticated_student_can_open_the_support_form(): void
    {
        $student = User::factory()->create();

        $this->actingAs($student)
            ->get(route('support.create'))
            ->assertOk()
            ->assertSee('Pusat Bantuan')
            ->assertSee('Laporan bug');
    }

    public function test_judge0_failure_alert_is_rate_limited(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $notifications = app(AdminNotificationService::class);

        $notifications->notifyJudge0Failure(429);
        $notifications->notifyJudge0Failure(429);

        $this->assertCount(1, $admin->notifications);
        $this->assertSame('system_warning', $admin->notifications->first()->data['type']);
    }

    public function test_admin_can_fetch_and_mark_notifications_read(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        app(AdminNotificationService::class)->notifyAdmins('new_student', 'Siswa baru', 'Pesan baru');
        $notification = $admin->notifications()->firstOrFail();

        $this->actingAs($admin)
            ->getJson(route('admin.notifications'))
            ->assertOk()
            ->assertJsonPath('unread_count', 1)
            ->assertJsonPath('notifications.0.data.title', 'Siswa baru');

        $this->postJson(route('admin.notifications.read', $notification->id))
            ->assertOk()
            ->assertJsonPath('success', true);
        $this->assertNotNull($notification->fresh()->read_at);
    }
}
