<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Submission;
use App\Models\TestCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
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

        $testCaseCounts = TestCase::selectRaw('exercise_id, count(*) as total')
            ->groupBy('exercise_id')
            ->pluck('total', 'exercise_id');

        return view('admin.latihan', compact('exercises', 'testCaseCounts'));
    }

    /**
     * Tampilkan Form Tambah Latihan Baru
     */
    public function createExercise(): View
    {
        return view('admin.latihan-form', [
            'exercise' => null,
            'lessons' => $this->lessonOptions(),
            'action' => route('admin.latihan.store'),
            'method' => 'POST',
            'heading' => 'Tambah Latihan',
        ]);
    }

    /**
     * Simpan Latihan Baru (default draft, bisa langsung diterbitkan)
     */
    public function storeExercise(Request $request): RedirectResponse
    {
        $validated = $this->validateExercise($request);

        $exercise = Exercise::create([
            'lesson_id' => $validated['lesson_id'],
            'title' => trim($validated['title']),
            'slug' => $this->uniqueExerciseSlug(
                Str::slug($validated['slug'] ?? '') ?: (Str::slug($validated['title']) ?: 'latihan')
            ),
            'description' => $this->normalizeText($validated['description']),
            'instructions' => $this->normalizeText($validated['instructions'] ?? null),
            'starter_code' => $this->normalizeStarterCode($validated['starter_code'] ?? null),
            'difficulty' => $validated['difficulty'],
            'time_limit_ms' => $validated['time_limit_ms'],
            'memory_limit_mb' => $validated['memory_limit_mb'],
            'sort_order' => $validated['sort_order'] ?? ((Exercise::max('sort_order') ?? 0) + 1),
            'is_published' => $request->boolean('is_published'),
        ]);

        $message = $exercise->is_published
            ? "Latihan \"{$exercise->title}\" berhasil disimpan dan sudah tampil untuk siswa."
            : "Latihan \"{$exercise->title}\" berhasil disimpan sebagai draft. Siswa belum bisa melihatnya.";

        return redirect()
            ->route('admin.latihan')
            ->with('status', $message.$this->missingTestCaseNote($exercise));
    }

    /**
     * Tampilkan Form Edit Latihan
     */
    public function editExercise(Exercise $exercise): View
    {
        return view('admin.latihan-form', [
            'exercise' => $exercise,
            'lessons' => $this->lessonOptions(),
            'action' => route('admin.latihan.update', $exercise),
            'method' => 'PUT',
            'heading' => 'Edit Latihan',
        ]);
    }

    /**
     * Update Latihan. Slug yang dikosongkan tidak diubah agar link siswa tetap berlaku.
     */
    public function updateExercise(Request $request, Exercise $exercise): RedirectResponse
    {
        $validated = $this->validateExercise($request, $exercise);

        $slug = $exercise->slug;
        if (! empty($validated['slug'])) {
            $slug = $this->uniqueExerciseSlug(Str::slug($validated['slug']), $exercise->id);
        }

        $exercise->update([
            'lesson_id' => $validated['lesson_id'],
            'title' => trim($validated['title']),
            'slug' => $slug,
            'description' => $this->normalizeText($validated['description']),
            'instructions' => $this->normalizeText($validated['instructions'] ?? null),
            'starter_code' => $this->normalizeStarterCode($validated['starter_code'] ?? null),
            'difficulty' => $validated['difficulty'],
            'time_limit_ms' => $validated['time_limit_ms'],
            'memory_limit_mb' => $validated['memory_limit_mb'],
            'sort_order' => $validated['sort_order'] ?? $exercise->sort_order,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()
            ->route('admin.latihan')
            ->with('status', "Latihan \"{$exercise->title}\" berhasil diperbarui.".$this->missingTestCaseNote($exercise));
    }

    /**
     * Terbit / tarik latihan langsung dari daftar.
     */
    public function updateExerciseStatus(Request $request, Exercise $exercise): RedirectResponse
    {
        $validated = $request->validate([
            'is_published' => ['required', 'boolean'],
        ]);

        $exercise->update(['is_published' => (bool) $validated['is_published']]);

        $message = $exercise->is_published
            ? "Latihan \"{$exercise->title}\" sekarang tampil untuk siswa."
            : "Latihan \"{$exercise->title}\" ditarik menjadi draft.";

        return back()->with('status', $message.$this->missingTestCaseNote($exercise));
    }

    /**
     * Hapus Latihan (test case dan submission terkait ikut terhapus oleh database)
     */
    public function destroyExercise(Exercise $exercise): RedirectResponse
    {
        $title = $exercise->title;
        $exercise->delete();

        return redirect()
            ->route('admin.latihan')
            ->with('status', "Latihan \"{$title}\" berhasil dihapus.");
    }

    /**
     * Daftar test case milik satu latihan
     */
    public function testCases(Exercise $exercise): View
    {
        $testCases = TestCase::where('exercise_id', $exercise->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.test-cases', compact('exercise', 'testCases'));
    }

    /**
     * Tampilkan Form Tambah Test Case
     */
    public function createTestCase(Exercise $exercise): View
    {
        return view('admin.test-case-form', [
            'exercise' => $exercise,
            'testCase' => null,
            'action' => route('admin.latihan.test-cases.store', $exercise),
            'method' => 'POST',
            'heading' => 'Tambah Test Case',
        ]);
    }

    /**
     * Simpan Test Case Baru
     */
    public function storeTestCase(Request $request, Exercise $exercise): RedirectResponse
    {
        $validated = $this->validateTestCase($request);

        TestCase::create([
            'exercise_id' => $exercise->id,
            'input' => $this->normalizeText($validated['input'] ?? null),
            'expected_output' => $this->normalizeText($validated['expected_output'] ?? null) ?? '',
            'assertion_code' => $this->normalizeText($validated['assertion_code'] ?? null),
            'weight' => $validated['weight'],
            'is_hidden' => $request->boolean('is_hidden'),
            'is_active' => true,
            'sort_order' => $validated['sort_order']
                ?? ((TestCase::where('exercise_id', $exercise->id)->max('sort_order') ?? 0) + 1),
        ]);

        return redirect()
            ->route('admin.latihan.test-cases', $exercise)
            ->with('status', 'Test case berhasil ditambahkan.');
    }

    /**
     * Tampilkan Form Edit Test Case
     */
    public function editTestCase(TestCase $testCase): View
    {
        return view('admin.test-case-form', [
            'exercise' => $testCase->exercise,
            'testCase' => $testCase,
            'action' => route('admin.test-cases.update', $testCase),
            'method' => 'PUT',
            'heading' => 'Edit Test Case',
        ]);
    }

    /**
     * Update Test Case
     */
    public function updateTestCase(Request $request, TestCase $testCase): RedirectResponse
    {
        $validated = $this->validateTestCase($request);

        $testCase->update([
            'input' => $this->normalizeText($validated['input'] ?? null),
            'expected_output' => $this->normalizeText($validated['expected_output'] ?? null) ?? '',
            'assertion_code' => $this->normalizeText($validated['assertion_code'] ?? null),
            'weight' => $validated['weight'],
            'is_hidden' => $request->boolean('is_hidden'),
            'sort_order' => $validated['sort_order'] ?? $testCase->sort_order,
        ]);

        return redirect()
            ->route('admin.latihan.test-cases', $testCase->exercise_id)
            ->with('status', 'Test case berhasil diperbarui.');
    }

    /**
     * Hapus Test Case (hasil pengerjaan siswa untuk test case ini ikut terhapus)
     */
    public function destroyTestCase(TestCase $testCase): RedirectResponse
    {
        $exerciseId = $testCase->exercise_id;
        $testCase->delete();

        return redirect()
            ->route('admin.latihan.test-cases', $exerciseId)
            ->with('status', 'Test case berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateTestCase(Request $request): array
    {
        return $request->validate([
            'input' => ['nullable', 'string', 'max:10000'],
            'expected_output' => ['required_without:assertion_code', 'nullable', 'string', 'max:10000'],
            'assertion_code' => ['nullable', 'string', 'max:10000'],
            'weight' => ['required', 'integer', 'between:1,100'],
            'is_hidden' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function validateExercise(Request $request, ?Exercise $exercise = null): array
    {
        return $request->validate([
            'lesson_id' => ['required', 'integer', Rule::exists('lessons', 'id')],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('exercises', 'slug')->ignore($exercise?->id)],
            'description' => ['required', 'string'],
            'instructions' => ['nullable', 'string'],
            'starter_code' => ['nullable', 'string'],
            'difficulty' => ['required', Rule::in(['easy', 'medium', 'hard'])],
            'time_limit_ms' => ['required', 'integer', 'between:1000,5000'],
            'memory_limit_mb' => ['required', 'integer', 'between:16,250'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],
        ]);
    }

    /**
     * URL siswa /latihan/{slug} mencari berdasarkan slug saja, jadi slug harus unik di seluruh tabel.
     */
    private function uniqueExerciseSlug(string $baseSlug, ?int $ignoreId = null): string
    {
        $slug = $baseSlug;
        $count = 1;

        while (Exercise::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$baseSlug}-".(++$count);
        }

        return $slug;
    }

    /**
     * @return Collection<int, Lesson>
     */
    private function lessonOptions()
    {
        return Lesson::with('module')->orderBy('module_id')->orderBy('sort_order')->get();
    }

    /**
     * Catatan tambahan bila latihan terbit tetapi belum punya test case.
     */
    private function missingTestCaseNote(Exercise $exercise): string
    {
        if (! $exercise->is_published || $exercise->testCases()->exists()) {
            return '';
        }

        return ' Catatan: soal ini belum punya test case, jadi jawaban siswa belum bisa dinilai otomatis.';
    }

    private function normalizeStarterCode(?string $code): ?string
    {
        $code = $this->normalizeText($code);

        return $code === null ? null : rtrim($code)."\n";
    }

    /**
     * Samakan akhir baris (\r\n dan \r menjadi \n).
     */
    private function normalizeText(?string $text): ?string
    {
        if ($text === null) {
            return null;
        }

        $text = str_replace(["\r\n", "\r"], "\n", $text);

        return $text === '' ? null : $text;
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
