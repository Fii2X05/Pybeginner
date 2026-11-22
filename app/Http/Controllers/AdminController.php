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

        // Platform Summary Analytics
        $avgScore = round((float) (Submission::avg('score') ?? 0), 1);
        $passedSubmissions = Submission::where(function ($q) {
            $q->where('status', 'accepted')
                ->orWhere('score', '>=', 70);
        })->count();
        $passRate = $totalSubmissions > 0 ? round(($passedSubmissions / $totalSubmissions) * 100, 1) : 0;
        $activeLearners = User::where('role', 'learner')->whereHas('submissions')->count();

        // 1. Penilaian Persoal (Statistik Autograder & Evaluasi Tiap Latihan)
        $exercisesStats = Exercise::query()
            ->with(['lesson.module'])
            ->withCount('submissions')
            ->withCount([
                'submissions as passed_count' => function ($q) {
                    $q->where('status', 'accepted')->orWhere('score', '>=', 70);
                },
            ])
            ->withAvg('submissions', 'score')
            ->orderBy('sort_order')
            ->get();

        // 2. Penilaian per Learner / Siswa Monitoring (Siswa Teraktif dan Performa)
        $learnerStats = User::where('role', 'learner')
            ->has('submissions')
            ->withCount('submissions')
            ->withCount([
                'submissions as passed_count' => function ($q) {
                    $q->where('status', 'accepted')->orWhere('score', '>=', 70);
                },
            ])
            ->withAvg('submissions', 'score')
            ->orderByDesc('submissions_count')
            ->take(8)
            ->get();

        // 3. Live Feed Evaluasi Submission Terbaru
        $recentSubmissions = Submission::with(['user', 'exercise'])
            ->latest('submitted_at')
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalModul',
            'totalLatihan',
            'totalSiswa',
            'totalSubmissions',
            'avgScore',
            'passRate',
            'activeLearners',
            'exercisesStats',
            'learnerStats',
            'recentSubmissions'
        ));
    }

    /**
     * Tampilkan Halaman Materi Belajar
     */
    public function materi(): View
    {
        $modules = Module::with(['lessons' => fn ($q) => $q->orderBy('sort_order')])
            ->withCount('lessons')
            ->orderBy('sort_order')
            ->get();

        return view('admin.materi', compact('modules'));
    }

    /**
     * Tampilkan Halaman Latihan & Soal
     */
    public function latihan(): View
    {
        $duplicateSlugs = Exercise::select('slug')
            ->groupBy('slug')
            ->havingRaw('count(*) > 1')
            ->pluck('slug')
            ->toArray();

        $exercises = Exercise::with('lesson.module')
            ->withCount('testCases')
            ->withCount('submissions')
            ->orderBy('sort_order')
            ->get();

        return view('admin.latihan', compact('exercises', 'duplicateSlugs'));
    }

    /**
     * Update Detail Latihan (Slug, Limit Eksekusi, Urutan)
     */
    public function updateExercise(Request $request, Exercise $exercise): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:exercises,slug,'.$exercise->id],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'time_limit_ms' => ['required', 'integer', 'min:500', 'max:30000'],
            'memory_limit_mb' => ['required', 'integer', 'min:16', 'max:1024'],
            'sort_order' => ['required', 'integer', 'min:1'],
        ]);

        $exercise->update($validated);

        return back()->with('status', "Latihan '{$exercise->title}' berhasil diperbarui!");
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
            ->latest('submitted_at')
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
