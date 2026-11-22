<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_learner_can_view_profile_without_password_settings(): void
    {
        $learner = User::factory()->create(['name' => 'Nadia Learner']);

        $this->actingAs($learner)
            ->get(route('profile'))
            ->assertOk()
            ->assertSee('Profil Saya')
            ->assertSee('Nadia Learner')
            ->assertSee('Foto profil')
            ->assertDontSee('Ubah Kata Sandi');
    }

    public function test_non_admin_can_update_name_and_upload_profile_photo(): void
    {
        Storage::fake('public');
        $learner = User::factory()->create([
            'name' => 'Old Name',
            'avatar' => 'https://example.com/old-avatar.jpg',
        ]);
        $avatar = UploadedFile::fake()->image('profile.png');

        $this->actingAs($learner)
            ->put(route('profile.update'), [
                'name' => 'New Name',
                'avatar' => $avatar,
            ])
            ->assertRedirect(route('profile'))
            ->assertSessionHas('status', 'Profil berhasil diperbarui.');

        $learner->refresh();
        $this->assertSame('New Name', $learner->name);
        $this->assertStringStartsWith('avatars/', $learner->avatar);
        Storage::disk('public')->assertExists($learner->avatar);
        $this->assertSame(Storage::disk('public')->url($learner->avatar), $learner->avatar_url);
    }

    public function test_learner_can_remove_a_locally_uploaded_profile_photo(): void
    {
        Storage::fake('public');
        $avatarPath = 'avatars/profile.png';
        Storage::disk('public')->put($avatarPath, 'image contents');
        $learner = User::factory()->create(['avatar' => $avatarPath]);

        $this->actingAs($learner)
            ->put(route('profile.update'), [
                'name' => $learner->name,
                'remove_avatar' => 1,
            ])
            ->assertRedirect(route('profile'));

        $this->assertNull($learner->fresh()->avatar);
        Storage::disk('public')->assertMissing($avatarPath);
    }

    public function test_invalid_profile_photo_does_not_change_profile(): void
    {
        Storage::fake('public');
        $learner = User::factory()->create(['name' => 'Original Name']);

        $this->actingAs($learner)
            ->put(route('profile.update'), [
                'name' => 'Changed Name',
                'avatar' => UploadedFile::fake()->create('document.pdf', 20, 'application/pdf'),
            ])
            ->assertSessionHasErrors('avatar');

        $this->assertSame('Original Name', $learner->fresh()->name);
        $this->assertNull($learner->fresh()->avatar);
        $this->assertSame(0, count(Storage::disk('public')->allFiles('avatars')));
    }

    public function test_admin_profile_link_redirects_to_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('profile'))
            ->assertRedirect(route('admin.dashboard'));
    }
}
