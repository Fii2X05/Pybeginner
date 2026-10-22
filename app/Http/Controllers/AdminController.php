<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Module;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Tampilkan Halaman Dashboard Admin
     */
    public function dashboard(): View
    {
        $totalModul = Module::count();
        $totalLatihan = Exercise::count();
        $totalSiswa = User::where('role', 'learner')->count();
        $totalSubmissions = Submission::count();

        // Ambil aktivitas dari submission terbaru jika ada, atau gunakan mock aktivitas sesuai desain
        $submissions = Submission::with(['user', 'exercise'])
            ->latest()
            ->take(5)
            ->get();

        $recentActivities = [];

        if ($submissions->isNotEmpty()) {
            foreach ($submissions as $sub) {
                $recentActivities[] = [
                    'name' => $sub->user->name ?? 'Siswa',
                    'action' => 'Mengumpulkan solusi '.($sub->exercise->title ?? 'Latihan'),
                    'time' => $sub->created_at ? $sub->created_at->diffForHumans() : 'Baru saja',
                ];
            }
        }

        return view('admin.dashboard', compact(
            'totalModul',
            'totalLatihan',
            'totalSiswa',
            'totalSubmissions',
            'recentActivities'
        ));
    }

    /**
     * Tampilkan Halaman Materi Belajar
     */
    public function materi(): View
    {
        $modules = Module::withCount('lessons')->orderBy('sort_order')->get();

        return view('admin.materi', compact('modules'));
    }

    /**
     * Tampilkan Halaman Latihan & Soal
     */
    public function latihan(): View
    {
        $exercises = Exercise::with('lesson.module')
            ->orderBy('sort_order')
            ->get();

        return view('admin.latihan', compact('exercises'));
    }

    /**
     * Tampilkan Halaman Daftar Siswa
     */
    public function siswa(): View
    {
        $students = User::where('role', 'learner')
            ->latest()
            ->paginate(15);

        return view('admin.siswa', compact('students'));
    }

    /**
     * Tampilkan Halaman Penilaian / Hasil Submission
     */
    public function penilaian(): View
    {
        $submissions = Submission::with(['user', 'exercise'])
            ->latest()
            ->paginate(15);

        return view('admin.penilaian', compact('submissions'));
    }

    /**
     * Tampilkan Halaman Pengaturan
     */
    public function pengaturan(): View
    {
        $admin = Auth::user();

        return view('admin.pengaturan', compact('admin'));
    }

    /**
     * Update Profil Admin
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        $user->update($validated);

        return back()->with('status', 'Profil berhasil diperbarui!');
    }

    /**
     * Update Password Admin
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'Kata sandi berhasil diubah!');
    }
}
