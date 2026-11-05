<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Module;
use App\Models\Submission;
use App\Models\TestCase;
use App\Models\TestCaseProgress;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminContentController extends Controller
{
    public function createModule(): View
    {
        return view('admin.materi-form', [
            'module' => null,
            'courses' => Course::orderBy('sort_order')->get(),
        ]);
    }

    public function editModule(Module $module): View
    {
        return view('admin.materi-form', [
            'module' => $module,
            'courses' => Course::orderBy('sort_order')->get(),
        ]);
    }

    public function createExercise(): View
    {
        return view('admin.latihan-form', [
            'exercise' => null,
            'lessons' => $this->lessonOptions(),
        ]);
    }

    public function storeExercise(Request $request): RedirectResponse
    {
        $validated = $this->validateExercise($request);

        if ($request->boolean('is_published')) {
            throw ValidationException::withMessages([
                'is_published' => 'Simpan latihan sebagai draft, tambahkan test case publik, lalu terbitkan.',
            ]);
        }

        $slug = $this->uniqueExerciseSlug(
            Str::slug(($validated['slug'] ?? '') ?: $validated['title']) ?: 'latihan',
        );

        $exercise = Exercise::create([
            'lesson_id' => $validated['lesson_id'],
            'title' => trim($validated['title']),
            'slug' => $slug,
            'description' => $this->normalizeText($validated['description']),
            'instructions' => $this->normalizeText($validated['instructions'] ?? null),
            'starter_code' => $this->normalizeStarterCode($validated['starter_code'] ?? null),
            'difficulty' => $validated['difficulty'],
            'time_limit_ms' => $validated['time_limit_ms'],
            'memory_limit_mb' => $validated['memory_limit_mb'],
            'sort_order' => $validated['sort_order'] ?? ((Exercise::max('sort_order') ?? 0) + 1),
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()
            ->route('admin.latihan.test-cases', $exercise)
            ->with('status', 'Latihan berhasil dibuat. Tambahkan test case sebelum diterbitkan.');
    }

    public function editExercise(Exercise $exercise): View
    {
        return view('admin.latihan-form', [
            'exercise' => $exercise,
            'lessons' => $this->lessonOptions(),
        ]);
    }

    public function updateExercise(Request $request, Exercise $exercise): RedirectResponse
    {
        $validated = $this->validateExercise($request, $exercise);
        $isPublished = $request->boolean('is_published');

        if ($isPublished && ! $this->hasPublicTestCase($exercise)) {
            throw ValidationException::withMessages([
                'is_published' => 'Tambahkan minimal satu test case aktif sebelum menerbitkan latihan.',
            ]);
        }

        $exercise->update([
            'lesson_id' => $validated['lesson_id'],
            'title' => trim($validated['title']),
            'slug' => filled($validated['slug'] ?? null)
                ? $this->uniqueExerciseSlug(Str::slug($validated['slug']), $exercise->id)
                : $exercise->slug,
            'description' => $this->normalizeText($validated['description']),
            'instructions' => $this->normalizeText($validated['instructions'] ?? null),
            'starter_code' => $this->normalizeStarterCode($validated['starter_code'] ?? null),
            'difficulty' => $validated['difficulty'],
            'time_limit_ms' => $validated['time_limit_ms'],
            'memory_limit_mb' => $validated['memory_limit_mb'],
            'sort_order' => $validated['sort_order'] ?? $exercise->sort_order,
            'is_published' => $isPublished,
        ]);

        return redirect()->route('admin.latihan')->with('status', 'Latihan berhasil diperbarui.');
    }

    public function updateExerciseStatus(Request $request, Exercise $exercise): RedirectResponse
    {
        $validated = $request->validate([
            'is_published' => ['required', 'boolean'],
        ]);

        if ((bool) $validated['is_published'] && ! $this->hasPublicTestCase($exercise)) {
            throw ValidationException::withMessages([
                'is_published' => 'Tambahkan minimal satu test case aktif sebelum menerbitkan latihan.',
            ]);
        }

        $exercise->update(['is_published' => (bool) $validated['is_published']]);

        return back()->with('status', $exercise->is_published
            ? 'Latihan berhasil diterbitkan.'
            : 'Latihan dipindahkan ke draft.');
    }

    public function destroyExercise(Exercise $exercise): RedirectResponse
    {
        if (
            Submission::where('exercise_id', $exercise->id)->exists()
            || TestCaseProgress::where('exercise_id', $exercise->id)->exists()
        ) {
            throw ValidationException::withMessages([
                'exercise' => 'Latihan tidak dapat dihapus karena sudah memiliki riwayat pengerjaan siswa. Pindahkan ke draft agar tidak tampil.',
            ]);
        }

        $exercise->delete();

        return redirect()->route('admin.latihan')->with('status', 'Latihan berhasil dihapus.');
    }

    public function testCases(Exercise $exercise): View
    {
        $testCases = TestCase::where('exercise_id', $exercise->id)
            ->withCount(['results', 'progress'])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.test-cases', compact('exercise', 'testCases'));
    }

    public function createTestCase(Exercise $exercise): View
    {
        return view('admin.test-case-form', [
            'exercise' => $exercise,
            'testCase' => null,
        ]);
    }

    public function storeTestCase(Request $request, Exercise $exercise): RedirectResponse
    {
        $validated = $this->validateTestCase($request);

        $exercise->testCases()->create([
            'input' => $validated['mode'] === 'input_output'
                ? $this->normalizeText($validated['input'] ?? null)
                : null,
            'expected_output' => $validated['mode'] === 'input_output'
                ? $this->normalizeText($validated['expected_output'])
                : '',
            'assertion_code' => $validated['mode'] === 'assertion'
                ? $this->normalizeText($validated['assertion_code'])
                : null,
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

    public function editTestCase(TestCase $testCase): View
    {
        return view('admin.test-case-form', [
            'exercise' => $testCase->exercise,
            'testCase' => $testCase,
        ]);
    }

    public function updateTestCase(Request $request, TestCase $testCase): RedirectResponse
    {
        $validated = $this->validateTestCase($request);

        $testCase->update([
            'input' => $validated['mode'] === 'input_output'
                ? $this->normalizeText($validated['input'] ?? null)
                : null,
            'expected_output' => $validated['mode'] === 'input_output'
                ? $this->normalizeText($validated['expected_output'])
                : '',
            'assertion_code' => $validated['mode'] === 'assertion'
                ? $this->normalizeText($validated['assertion_code'])
                : null,
            'weight' => $validated['weight'],
            'is_hidden' => $request->boolean('is_hidden'),
            'sort_order' => $validated['sort_order'] ?? $testCase->sort_order,
        ]);

        return redirect()
            ->route('admin.latihan.test-cases', $testCase->exercise_id)
            ->with('status', 'Test case berhasil diperbarui.');
    }

    public function destroyTestCase(TestCase $testCase): RedirectResponse
    {
        if (
            DB::table('submission_results')->where('test_case_id', $testCase->id)->exists()
            || TestCaseProgress::where('test_case_id', $testCase->id)->exists()
        ) {
            throw ValidationException::withMessages([
                'test_case' => 'Test case tidak dapat dihapus karena sudah digunakan dalam pengerjaan siswa.',
            ]);
        }

        $exerciseId = $testCase->exercise_id;
        $testCase->delete();

        return redirect()
            ->route('admin.latihan.test-cases', $exerciseId)
            ->with('status', 'Test case berhasil dihapus.');
    }

    public function storeModule(Request $request): RedirectResponse
    {
        $validated = $this->validateModule($request);
        $module = Module::create([
            'course_id' => $validated['course_id'],
            'title' => trim($validated['title']),
            'slug' => $this->uniqueModuleSlug(
                (int) $validated['course_id'],
                Str::slug(($validated['slug'] ?? '') ?: $validated['title']) ?: 'modul',
            ),
            'description' => $this->normalizeText($validated['description'] ?? null),
            'sort_order' => $validated['sort_order'] ?? ((Module::where('course_id', $validated['course_id'])->max('sort_order') ?? 0) + 1),
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()
            ->route('admin.materi.lessons', $module)
            ->with('status', 'Modul berhasil dibuat. Tambahkan materi pelajaran untuk melengkapinya.');
    }

    public function updateModule(Request $request, Module $module): RedirectResponse
    {
        $validated = $this->validateModule($request);
        $courseChanged = (int) $validated['course_id'] !== $module->course_id;
        $slug = $module->slug;

        if (filled($validated['slug'] ?? null)) {
            $slug = $this->uniqueModuleSlug(
                (int) $validated['course_id'],
                Str::slug($validated['slug']),
                $module->id,
            );
        } elseif ($courseChanged) {
            $slug = $this->uniqueModuleSlug(
                (int) $validated['course_id'],
                $module->slug,
                $module->id,
            );
        }

        $module->update([
            'course_id' => $validated['course_id'],
            'title' => trim($validated['title']),
            'slug' => $slug,
            'description' => $this->normalizeText($validated['description'] ?? null),
            'sort_order' => $validated['sort_order'] ?? $module->sort_order,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('admin.materi')->with('status', 'Modul berhasil diperbarui.');
    }

    public function destroyModule(Module $module): RedirectResponse
    {
        if ($module->lessons()->exists()) {
            throw ValidationException::withMessages([
                'module' => 'Modul yang masih memiliki pelajaran tidak dapat dihapus. Hapus atau pindahkan pelajarannya terlebih dahulu.',
            ]);
        }

        $module->delete();

        return redirect()->route('admin.materi')->with('status', 'Modul berhasil dihapus.');
    }

    public function moduleLessons(Module $module): View
    {
        $module->load(['course', 'lessons' => fn ($query) => $query->withCount(['exercises', 'progress'])]);

        return view('admin.materi-lessons', compact('module'));
    }

    public function storeLesson(Request $request, Module $module): RedirectResponse
    {
        $validated = $this->validateLesson($request);

        $module->lessons()->create([
            'title' => trim($validated['title']),
            'slug' => $this->uniqueLessonSlug(
                $module->id,
                Str::slug(($validated['slug'] ?? '') ?: $validated['title']) ?: 'pelajaran',
            ),
            'content' => $this->normalizeText($validated['content']),
            'sort_order' => $validated['sort_order'] ?? (($module->lessons()->max('sort_order') ?? 0) + 1),
            'is_published' => $request->boolean('is_published'),
        ]);

        return back()->with('status', 'Pelajaran berhasil ditambahkan.');
    }

    public function editLesson(Lesson $lesson): View
    {
        $lesson->load('module.course');

        return view('admin.lesson-form', [
            'lesson' => $lesson,
            'module' => $lesson->module,
        ]);
    }

    public function updateLesson(Request $request, Lesson $lesson): RedirectResponse
    {
        $validated = $this->validateLesson($request);

        $lesson->update([
            'title' => trim($validated['title']),
            'slug' => filled($validated['slug'] ?? null)
                ? $this->uniqueLessonSlug($lesson->module_id, Str::slug($validated['slug']), $lesson->id)
                : $lesson->slug,
            'content' => $this->normalizeText($validated['content']),
            'sort_order' => $validated['sort_order'] ?? $lesson->sort_order,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()
            ->route('admin.materi.lessons', $lesson->module_id)
            ->with('status', 'Pelajaran berhasil diperbarui.');
    }

    public function updateLessonStatus(Request $request, Lesson $lesson): RedirectResponse
    {
        $validated = $request->validate([
            'is_published' => ['required', 'boolean'],
        ]);

        $lesson->update(['is_published' => (bool) $validated['is_published']]);

        return back()->with('status', 'Status pelajaran berhasil diperbarui.');
    }

    public function destroyLesson(Lesson $lesson): RedirectResponse
    {
        if (
            $lesson->exercises()->exists()
            || LessonProgress::where('lesson_id', $lesson->id)->exists()
        ) {
            throw ValidationException::withMessages([
                'lesson' => 'Pelajaran tidak dapat dihapus karena memiliki latihan atau riwayat belajar siswa.',
            ]);
        }

        $moduleId = $lesson->module_id;
        $lesson->delete();

        return redirect()
            ->route('admin.materi.lessons', $moduleId)
            ->with('status', 'Pelajaran berhasil dihapus.');
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
            'description' => ['required', 'string', 'max:10000'],
            'instructions' => ['nullable', 'string', 'max:10000'],
            'starter_code' => ['nullable', 'string', 'max:50000'],
            'difficulty' => ['required', Rule::in(['easy', 'medium', 'hard'])],
            'time_limit_ms' => ['required', 'integer', 'between:1000,10000'],
            'memory_limit_mb' => ['required', 'integer', 'between:16,250'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function validateTestCase(Request $request): array
    {
        return $request->validate([
            'mode' => ['required', Rule::in(['input_output', 'assertion'])],
            'input' => ['nullable', 'string', 'max:10000', 'exclude_if:mode,assertion'],
            'expected_output' => ['required_if:mode,input_output', 'nullable', 'string', 'max:10000', 'exclude_if:mode,assertion'],
            'assertion_code' => ['required_if:mode,assertion', 'nullable', 'string', 'max:10000', 'exclude_if:mode,input_output'],
            'weight' => ['required', 'integer', 'between:1,100'],
            'is_hidden' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function validateModule(Request $request): array
    {
        return $request->validate([
            'course_id' => ['required', 'integer', Rule::exists('courses', 'id')],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'description' => ['nullable', 'string', 'max:5000'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function validateLesson(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'content' => ['required', 'string', 'max:100000'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],
        ]);
    }

    /**
     * @return Collection<int, Lesson>
     */
    private function lessonOptions(): Collection
    {
        return Lesson::with('module')
            ->orderBy('module_id')
            ->orderBy('sort_order')
            ->get();
    }

    private function uniqueExerciseSlug(string $baseSlug, ?int $ignoreId = null): string
    {
        $slug = $baseSlug;
        $suffix = 1;

        while (Exercise::where('slug', $slug)->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->exists()) {
            $slug = $baseSlug.'-'.(++$suffix);
        }

        return $slug;
    }

    private function uniqueModuleSlug(int $courseId, string $baseSlug, ?int $ignoreId = null): string
    {
        $slug = $baseSlug;
        $suffix = 1;

        while (
            Module::where('course_id', $courseId)
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug.'-'.(++$suffix);
        }

        return $slug;
    }

    private function uniqueLessonSlug(int $moduleId, string $baseSlug, ?int $ignoreId = null): string
    {
        $slug = $baseSlug;
        $suffix = 1;

        while (
            Lesson::where('module_id', $moduleId)
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug.'-'.(++$suffix);
        }

        return $slug;
    }

    private function normalizeStarterCode(?string $code): ?string
    {
        $code = $this->normalizeText($code);

        return $code === null ? null : rtrim($code)."\n";
    }

    private function normalizeText(?string $text): ?string
    {
        return $text === null ? null : str_replace(["\r\n", "\r"], "\n", $text);
    }

    private function hasPublicTestCase(Exercise $exercise): bool
    {
        return TestCase::where('exercise_id', $exercise->id)
            ->where('is_active', true)
            ->where('is_hidden', false)
            ->exists();
    }
}
