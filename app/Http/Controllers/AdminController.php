<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Module;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
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
        $courses = Course::orderBy('sort_order')->get();

        return view('admin.materi', compact('modules', 'courses'));
    }

    /**
     * Tampilkan Halaman Latihan & Soal
     */
    public function latihan(): View
    {
        $exercises = Exercise::with('lesson.module')
            ->withCount(['testCases', 'submissions', 'testCaseProgress'])
            ->orderBy('sort_order')
            ->get();

        return view('admin.latihan', compact('exercises'));
    }

    /**
     * Tampilkan halaman pengelolaan semua pengguna.
     */
    public function siswa(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', 'in:learner,mentor,admin'],
            'registration' => ['nullable', 'in:google,email'],
            'status' => ['nullable', 'in:active,suspended'],
            'joined' => ['nullable', 'in:newest,oldest'],
        ]);

        $query = User::query();

        if (isset($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        if (filled($filters['search'] ?? null)) {
            $query->where(function (Builder $query) use ($filters): void {
                $query->where('name', 'like', '%'.$filters['search'].'%')
                    ->orWhere('email', 'like', '%'.$filters['search'].'%');
            });
        }

        if (isset($filters['registration'])) {
            $filters['registration'] === 'google'
                ? $query->whereNotNull('google_id')
                : $query->whereNull('google_id');
        }

        if (isset($filters['status'])) {
            $query->where('is_suspended', $filters['status'] === 'suspended');
        }

        $query->orderBy('created_at', ($filters['joined'] ?? 'newest') === 'oldest' ? 'asc' : 'desc');

        $users = $query->orderBy('id')->paginate(15);

        return view('admin.siswa', compact('users', 'filters'));
    }

    public function siswaShow(User $user): View
    {
        abort_unless($user->role === 'learner', 404);

        $totalLessons = Lesson::where('is_published', true)->count();
        $completedLessons = LessonProgress::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereHas('lesson', fn (Builder $query) => $query->where('is_published', true))
            ->count();
        $submissionStats = Submission::where('user_id', $user->id)
            ->selectRaw('COUNT(*) as total, AVG(score) as average_score, SUM(CASE WHEN score = 100 THEN 1 ELSE 0 END) as perfect_scores')
            ->first();
        $recentSubmissions = Submission::with('exercise')
            ->where('user_id', $user->id)
            ->latest('submitted_at')
            ->take(10)
            ->get();

        return view('admin.siswa-detail', compact(
            'user',
            'totalLessons',
            'completedLessons',
            'submissionStats',
            'recentSubmissions',
        ));
    }

    public function updateStudentRole(Request $request, User $user): RedirectResponse
    {
        abort_if($user->role === 'admin' || $user->is(Auth::user()), 403);

        $validated = $request->validate([
            'role' => ['required', 'in:learner,mentor,admin'],
        ]);

        $user->update(['role' => $validated['role']]);

        return back()->with('status', 'Peran pengguna berhasil diperbarui.');
    }

    public function toggleStudentSuspension(User $user): RedirectResponse
    {
        abort_if($user->role === 'admin' || $user->is(Auth::user()), 403);

        $user->update(['is_suspended' => ! $user->is_suspended]);

        return back()->with(
            'status',
            $user->is_suspended ? 'Akun pengguna berhasil dinonaktifkan.' : 'Akun pengguna berhasil diaktifkan kembali.',
        );
    }

    public function deleteStudent(Request $request, User $user): RedirectResponse
    {
        abort_if($user->role === 'admin', 403);

        if ($user->is(Auth::user())) {
            abort(403, 'Akun yang sedang digunakan tidak dapat dihapus.');
        }

        $validated = $request->validate([
            'confirm_name' => ['required', 'string', 'max:255'],
        ]);

        if (! hash_equals($user->name, $validated['confirm_name'])) {
            throw ValidationException::withMessages([
                'confirm_name' => 'Nama yang diketik tidak sesuai. Akun belum dihapus.',
            ]);
        }

        $user->delete();

        return redirect()->route('admin.siswa')->with('status', 'Akun pengguna dan riwayat belajarnya berhasil dihapus.');
    }

    public function notifications(): JsonResponse
    {
        $admin = Auth::user();

        return response()->json([
            'unread_count' => $admin->unreadNotifications()->count(),
            'notifications' => $admin->notifications()->latest()->limit(10)->get()->map(fn ($notification): array => [
                'id' => $notification->id,
                'data' => $notification->data,
                'read_at' => $notification->read_at?->toISOString(),
                'created_at' => $notification->created_at?->diffForHumans(),
            ]),
        ]);
    }

    public function markNotificationRead(string $notification): JsonResponse|RedirectResponse
    {
        $adminNotification = Auth::user()->notifications()->whereKey($notification)->firstOrFail();
        $adminNotification->markAsRead();

        return request()->expectsJson()
            ? response()->json(['success' => true])
            : back();
    }

    public function markAllNotificationsRead(): JsonResponse|RedirectResponse
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);

        return request()->expectsJson()
            ? response()->json(['success' => true])
            : back()->with('status', 'Semua notifikasi telah ditandai sudah dibaca.');
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
