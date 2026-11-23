<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserProfileController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        if ($request->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('profile', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
        ]);

        $oldAvatar = $user->avatar;
        $newAvatar = null;

        if ($request->hasFile('avatar')) {
            $newAvatar = $request->file('avatar')->store('avatars', 'public');

            if ($newAvatar === false) {
                throw new RuntimeException('Foto profil gagal disimpan ke penyimpanan publik.');
            }
        }

        $avatar = $newAvatar ?? ($request->boolean('remove_avatar') ? null : $oldAvatar);

        $user->update([
            'name' => trim($validated['name']),
            'avatar' => $avatar,
        ]);

        if (($newAvatar !== null || $request->boolean('remove_avatar')) && $this->isLocalAvatar($oldAvatar)) {
            $disk = Storage::disk('public');

            if ($disk->exists($oldAvatar) && ! $disk->delete($oldAvatar)) {
                throw new RuntimeException('Foto profil lama gagal dihapus dari penyimpanan publik.');
            }
        }

        return redirect()->route('profile')->with('status', 'Profil berhasil diperbarui.');
    }

    private function isLocalAvatar(?string $avatar): bool
    {
        return $avatar !== null && filter_var($avatar, FILTER_VALIDATE_URL) === false;
    }
}
