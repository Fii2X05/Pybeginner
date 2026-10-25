<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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
     * Simpan Modul Belajar Baru
     */
    public function storeModule(Request $request): RedirectResponse
    {
        $courseId = Course::where('slug', 'python-dasar')->value('id');
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('modules', 'title')->where('course_id', $courseId)],
            'level' => ['required', Rule::in(['Pemula', 'Menengah Bawah', 'Menengah'])],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],
            'lesson_title' => ['required', 'string', 'max:255'],
            'lesson_content' => ['required', 'string'],
        ]);

        $course = Course::firstOrCreate(
            ['slug' => 'python-dasar'],
            [
                'title' => 'Python Dasar',
                'description' => 'Kursus utama Python Dasar',
                'level' => 'beginner',
                'is_published' => true,
                'sort_order' => 1,
            ]
        );

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $count = 1;
        while (Module::where('course_id', $course->id)->where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-".(++$count);
        }

        $nextSort = $validated['sort_order'] ?? ((Module::where('course_id', $course->id)->max('sort_order') ?? 0) + 1);

        $module = Module::create([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'slug' => $slug,
            'level' => $validated['level'] ?? 'Pemula',
            'description' => $validated['description'] ?? null,
            'sort_order' => $nextSort,
            'is_published' => $request->boolean('is_published', true),
        ]);

        $module->lessons()->create([
            'title' => $validated['lesson_title'],
            'slug' => Str::slug($validated['lesson_title']),
            'content' => $validated['lesson_content'],
            'sort_order' => 1,
            'is_published' => $request->boolean('is_published', true),
            'is_content_edited' => true,
        ]);

        return redirect()
            ->route('admin.materi.lessons', $module)
            ->with('status', "Modul #{$nextSort} berhasil dibuat beserta bagian materi pertamanya. Tambahkan bagian lain atau edit isinya di halaman ini.");
    }

    /**
     * Update Modul Belajar
     */
    public function updateModule(Request $request, Module $module): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('modules', 'title')->where('course_id', $module->course_id)->ignore($module->id)],
            'level' => ['required', Rule::in(['Pemula', 'Menengah Bawah', 'Menengah'])],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['required', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $module->update([
            'title' => $validated['title'],
            'level' => $validated['level'] ?? $module->level ?? 'Pemula',
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'],
            'is_published' => $request->boolean('is_published'),
        ]);

        return back()->with('status', 'Modul berhasil diperbarui!');
    }

    /**
     * Update tingkat kesulitan modul langsung dari tabel admin.
     */
    public function updateModuleLevel(Request $request, Module $module): RedirectResponse
    {
        $validated = $request->validate([
            'level' => ['required', Rule::in(['Pemula', 'Menengah Bawah', 'Menengah'])],
        ]);

        $module->update(['level' => $validated['level']]);

        return back()->with('status', 'Tingkat kesulitan modul berhasil diperbarui.');
    }

    /**
     * Hapus Modul Belajar
     */
    public function destroyModule(Module $module): RedirectResponse
    {
        $title = $module->title;
        $module->delete();

        return redirect()
            ->route('admin.materi')
            ->with('status', "Modul \"{$title}\" beserta seluruh materi pembelajarannya berhasil dihapus!");
    }

    /**
     * Kelola Seluruh Pelajaran / Materi di Dalam Modul
     */
    public function moduleLessons(Module $module): View
    {
        $module->load(['course', 'lessons' => fn ($q) => $q->orderBy('sort_order')]);

        return view('admin.materi-lessons', compact('module'));
    }

    /**
     * Tampilkan Halaman Editor Penuh untuk Satu Pelajaran
     */
    public function editLesson(Lesson $lesson): View
    {
        $lesson->load('module.course');

        return view('admin.lesson-editor', compact('lesson'));
    }

    /**
     * Simpan Pelajaran Baru ke Dalam Modul
     */
    public function storeLesson(Request $request, Module $module): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $count = 1;
        while (Lesson::where('module_id', $module->id)->where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-".(++$count);
        }

        $nextSort = $validated['sort_order'] ?? (($module->lessons()->max('sort_order') ?? 0) + 1);

        $module->lessons()->create([
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'sort_order' => $nextSort,
            'is_published' => $request->boolean('is_published', true),
            'is_content_edited' => true,
        ]);

        return back()->with('status', 'Pelajaran materi baru berhasil ditambahkan!');
    }

    /**
     * Update Pelajaran
     */
    public function updateLesson(Request $request, Lesson $lesson): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'sort_order' => ['required', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $count = 1;
        while (Lesson::where('module_id', $lesson->module_id)->where('slug', $slug)->where('id', '!=', $lesson->id)->exists()) {
            $slug = "{$originalSlug}-".(++$count);
        }

        $lesson->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'sort_order' => $validated['sort_order'],
            'is_published' => $request->boolean('is_published'),
            'is_content_edited' => true,
        ]);

        return redirect()
            ->route('admin.lessons.edit', $lesson)
            ->with('status', 'Materi pelajaran berhasil diperbarui!');
    }

    /**
     * Update status terbit pelajaran langsung dari daftar isi modul.
     */
    public function updateLessonStatus(Request $request, Lesson $lesson): RedirectResponse
    {
        $validated = $request->validate([
            'is_published' => ['required', 'boolean'],
        ]);

        $lesson->update(['is_published' => $validated['is_published']]);

        return back()->with('status', 'Status bagian materi berhasil diperbarui.');
    }

    /**
     * Hapus Pelajaran
     */
    public function destroyLesson(Lesson $lesson): RedirectResponse
    {
        $title = $lesson->title;
        $lesson->delete();

        return back()->with('status', "Pelajaran materi \"{$title}\" berhasil dihapus!");
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
