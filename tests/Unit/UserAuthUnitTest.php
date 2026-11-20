<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAuthUnitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Memastikan password pengguna otomatis di-hash ketika di-set ke model User.
     */
    public function test_user_password_is_automatically_hashed(): void
    {
        $user = new User([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secret-password-123',
        ]);

        $this->assertNotSame('secret-password-123', $user->password);
        $this->assertTrue(Hash::check('secret-password-123', $user->password));
    }

    /**
     * Memastikan atribut sensitif (password dan remember_token) disembunyikan saat serialisasi array/JSON.
     */
    public function test_sensitive_attributes_are_hidden_during_serialization(): void
    {
        $user = User::factory()->make([
            'password' => 'password123',
            'remember_token' => 'test-token-123',
        ]);

        $userArray = $user->toArray();

        $this->assertArrayNotHasKey('password', $userArray);
        $this->assertArrayNotHasKey('remember_token', $userArray);
    }

    /**
     * Memastikan accessor avatar_url mengembalikan null jika avatar kosong.
     */
    public function test_avatar_url_returns_null_when_avatar_is_empty(): void
    {
        $user = new User(['avatar' => null]);

        $this->assertNull($user->avatar_url);
    }

    /**
     * Memastikan accessor avatar_url mengembalikan URL eksternal (misal Google avatar) secara langsung.
     */
    public function test_avatar_url_returns_external_url_directly(): void
    {
        $externalUrl = 'https://lh3.googleusercontent.com/a/sample-avatar';
        $user = new User(['avatar' => $externalUrl]);

        $this->assertSame($externalUrl, $user->avatar_url);
    }

    /**
     * Memastikan accessor avatar_url menghasilkan URL storage lokal untuk file avatar yang diunggah.
     */
    public function test_avatar_url_prepends_storage_path_for_local_avatars(): void
    {
        $localPath = 'avatars/profile-123.jpg';
        $user = new User(['avatar' => $localPath]);

        $this->assertSame(asset('storage/'.$localPath), $user->avatar_url);
    }

    /**
     * Memastikan atribut tanggal (email_verified_at, last_login_at) di-cast ke instance Carbon.
     */
    public function test_date_attributes_are_cast_to_carbon_instances(): void
    {
        $user = new User([
            'email_verified_at' => '2026-01-01 10:00:00',
            'last_login_at' => '2026-01-02 15:30:00',
        ]);

        $this->assertInstanceOf(Carbon::class, $user->email_verified_at);
        $this->assertInstanceOf(Carbon::class, $user->last_login_at);
    }

    /**
     * Memastikan peran (role) pengguna dapat di-assign dan divalidasi dengan tepat.
     */
    public function test_user_roles_can_be_assigned_correctly(): void
    {
        $learner = User::factory()->make(['role' => 'learner']);
        $admin = User::factory()->make(['role' => 'admin']);

        $this->assertSame('learner', $learner->role);
        $this->assertSame('admin', $admin->role);
    }
}
