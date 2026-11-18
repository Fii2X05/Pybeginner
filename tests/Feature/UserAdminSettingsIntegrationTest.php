<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserAdminSettingsIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Memastikan guest dialihkan ke halaman login saat mengakses pengaturan profil user.
     */
    public function test_guest_is_redirected_to_login_when_accessing_user_profile_page(): void
    {
        $this->get(route('profile'))->assertRedirect(route('login'));
    }

    /**
     * Memastikan pengguna yang login dapat melihat halaman pengaturan profil dan datanya.
     */
    public function test_authenticated_user_can_view_profile_settings_page(): void
    {
        $user = User::factory()->create([
            'name' => 'Sukma Learner',
            'email' => 'sukma.learner@example.com',
            'role' => 'learner',
        ]);

        $response = $this->actingAs($user)->get(route('profile'));

        $response->assertOk();
        $response->assertViewIs('profile');
        $response->assertSee('Sukma Learner');
        $response->assertSee('sukma.learner@example.com');
    }

    /**
     * Memastikan pengguna dapat memperbarui informasi pribadi (nama, email, nomor HP, bio).
     */
    public function test_authenticated_user_can_update_personal_profile_information(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Lama',
            'email' => 'lama@example.com',
            'role' => 'learner',
        ]);

        $response = $this->actingAs($user)
            ->from(route('profile'))
            ->put(route('profile.update'), [
                'name' => 'Nama Baru',
                'email' => 'baru@example.com',
                'phone' => '081234567890',
                'bio' => 'Sedang belajar dasar Python di PyBeginner.',
            ]);

        $response->assertRedirect(route('profile'));
        $response->assertSessionHas('status', 'Profil berhasil diperbarui!');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Baru',
            'email' => 'baru@example.com',
            'phone' => '081234567890',
            'bio' => 'Sedang belajar dasar Python di PyBeginner.',
        ]);
    }

    /**
     * Memastikan pembaruan profil menolak email yang sudah digunakan oleh pengguna lain.
     */
    public function test_user_profile_update_rejects_duplicate_email_owned_by_another_user(): void
    {
        User::factory()->create(['email' => 'dipakai@example.com']);
        $user = User::factory()->create(['email' => 'asli@example.com']);

        $response = $this->actingAs($user)
            ->from(route('profile'))
            ->put(route('profile.update'), [
                'name' => $user->name,
                'email' => 'dipakai@example.com',
            ]);

        $response->assertRedirect(route('profile'));
        $response->assertSessionHasErrors(['email']);

        $user->refresh();
        $this->assertSame('asli@example.com', $user->email);
    }

    /**
     * Memastikan pengguna dapat mengunggah avatar baru dan avatar lokal lama ikut terhapus.
     */
    public function test_authenticated_user_can_upload_profile_avatar_and_replace_old_local_avatar(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('avatars/old-avatar.png', 'old-binary-content');

        $user = User::factory()->create([
            'avatar' => 'avatars/old-avatar.png',
        ]);

        // 1x1 PNG valid agar lulus validasi image tanpa bergantung pada ekstensi GD
        $tinyPng = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');
        $file = UploadedFile::fake()->createWithContent('avatar-baru.png', $tinyPng);

        $response = $this->actingAs($user)
            ->from(route('profile'))
            ->post(route('profile.avatar'), [
                'avatar' => $file,
            ]);

        $response->assertRedirect(route('profile'));
        $response->assertSessionHas('status', 'Foto profil berhasil diperbarui!');

        $user->refresh();
        $this->assertNotNull($user->avatar);
        $this->assertNotSame('avatars/old-avatar.png', $user->avatar);
        Storage::disk('public')->assertMissing('avatars/old-avatar.png');
        Storage::disk('public')->assertExists($user->avatar);
    }

    /**
     * Memastikan unggah avatar menolak berkas non-gambar.
     */
    public function test_user_avatar_upload_rejects_invalid_file_type(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $invalidFile = UploadedFile::fake()->create('catatan.txt', 10, 'text/plain');

        $response = $this->actingAs($user)
            ->from(route('profile'))
            ->post(route('profile.avatar'), [
                'avatar' => $invalidFile,
            ]);

        $response->assertRedirect(route('profile'));
        $response->assertSessionHasErrors(['avatar']);
    }

    /**
     * Memastikan admin dapat membuka halaman pengaturan admin.
     */
    public function test_admin_can_view_admin_settings_page(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Utama',
            'email' => 'admin.utama@example.com',
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.pengaturan'));

        $response->assertOk();
        $response->assertViewIs('admin.pengaturan');
        $response->assertSee('Admin Utama');
        $response->assertSee('admin.utama@example.com');
    }

    /**
     * Memastikan admin dapat memperbarui nama dan email pada halaman pengaturan admin.
     */
    public function test_admin_can_update_admin_profile_name_and_email(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Lama',
            'email' => 'admin.lama@example.com',
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.pengaturan'))
            ->put(route('admin.pengaturan.profile'), [
                'name' => 'Admin Diperbarui',
                'email' => 'admin.baru@example.com',
            ]);

        $response->assertRedirect(route('admin.pengaturan'));
        $response->assertSessionHas('status', 'Profil berhasil diperbarui!');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'name' => 'Admin Diperbarui',
            'email' => 'admin.baru@example.com',
        ]);
    }

    /**
     * Memastikan admin dapat mengganti kata sandi ketika kata sandi saat ini cocok.
     */
    public function test_admin_can_change_password_when_current_password_is_correct(): void
    {
        $admin = User::factory()->create([
            'password' => Hash::make('PasswordLama123!'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.pengaturan'))
            ->put(route('admin.pengaturan.password'), [
                'current_password' => 'PasswordLama123!',
                'password' => 'PasswordBaru456!',
                'password_confirmation' => 'PasswordBaru456!',
            ]);

        $response->assertRedirect(route('admin.pengaturan'));
        $response->assertSessionHas('status', 'Kata sandi berhasil diubah!');

        $admin->refresh();
        $this->assertTrue(Hash::check('PasswordBaru456!', $admin->password));
    }

    /**
     * Memastikan perubahan kata sandi admin gagal jika kata sandi saat ini salah.
     */
    public function test_admin_password_change_fails_when_current_password_is_incorrect(): void
    {
        $admin = User::factory()->create([
            'password' => Hash::make('PasswordLama123!'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.pengaturan'))
            ->put(route('admin.pengaturan.password'), [
                'current_password' => 'PasswordSalah999!',
                'password' => 'PasswordBaru456!',
                'password_confirmation' => 'PasswordBaru456!',
            ]);

        $response->assertRedirect(route('admin.pengaturan'));
        $response->assertSessionHasErrors(['current_password']);

        $admin->refresh();
        $this->assertTrue(Hash::check('PasswordLama123!', $admin->password));
    }
}

