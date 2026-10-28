<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ModulController extends Controller
{
    /**
     * Halaman daftar modul (/modul).
     * Data modul + progres user disiapkan di sini supaya view menampilkan status dinamis.
     */
    public function index()
    {
        $metaMap = [];
        for ($i = 1; $i <= 7; $i++) {
            $m = $this->getModuleMeta($i);
            if ($m) {
                $metaMap[$m['slug']] = array_merge($m, ['meta_no' => $i]);
            }
        }

        $allModules = Module::query()
            ->where('is_published', true)
            ->with([
                'course',
                'lessons' => fn ($q) => $q->where('is_published', true)->orderBy('sort_order'),
            ])
            ->orderBy('sort_order')
            ->get();

        // Jumlah latihan per modul dari tabel exercises
        $latihanPerModul = DB::table('exercises')
            ->join('lessons', 'lessons.id', '=', 'exercises.lesson_id')
            ->where('exercises.is_published', true)
            ->groupBy('lessons.module_id')
            ->selectRaw('lessons.module_id as module_id, COUNT(*) as total')
            ->pluck('total', 'module_id');

        // Progres user yang sedang login (kosong kalau belum login).
        $progress = auth()->check()
            ? LessonProgress::where('user_id', auth()->id())->get()->keyBy('lesson_id')
            : collect();

        $modulList = [];

        foreach ($allModules as $index => $module) {
            $lessons = $module->lessons;
            $total = $lessons->count();

            $completedCount = $lessons
                ->filter(fn ($lesson) => ($progress[$lesson->id]->progress_percent ?? (($progress[$lesson->id]->status ?? null) === 'completed' ? 100 : 0)) >= 100)
                ->count();
            $readCount = $lessons
                ->filter(fn ($lesson) => ($progress[$lesson->id]->progress_percent ?? (($progress[$lesson->id]->status ?? null) === 'completed' ? 100 : 0)) > 0)
                ->count();
            $progressPercent = $total > 0
                ? (int) round($lessons->sum(fn ($lesson) => $progress[$lesson->id]->progress_percent ?? (($progress[$lesson->id]->status ?? null) === 'completed' ? 100 : 0)) / $total)
                : 0;

            $sudahMulai = $lessons->contains(fn ($l) => isset($progress[$l->id]));

            $status = match (true) {
                $total > 0 && $completedCount === $total => 'selesai',
                $readCount > 0 || $sudahMulai => 'berjalan',
                default => 'belum',
            };

            // Materi yang dituju tombol: yang pertama belum selesai (atau pertama kalau semua selesai).
            $target = $lessons->first(fn ($lesson) => ($progress[$lesson->id]->progress_percent ?? (($progress[$lesson->id]->status ?? null) === 'completed' ? 100 : 0)) < 100)
                ?? $lessons->first();

            $meta = $metaMap[$module->slug] ?? null;

            $no = $meta['meta_no'] ?? (int) ($module->sort_order ?: ($index + 1));
            $level = ! empty($module->level) ? $module->level : ($meta['level'] ?? ($no <= 3 ? 'Pemula' : ($no <= 6 ? 'Menengah' : 'Lanjutan')));
            $descHtml = $module->description
                ? e($module->description)
                : ($meta['desc_html'] ?? e('Modul pembelajaran materi pemrograman Python.'));
            $descText = strip_tags($descHtml);
            $searchKeywords = Str::lower($module->title.' '.$descText.' '.($meta['search_keywords'] ?? ''));
            $materiCount = $meta['materiCount'] ?? max($total, 1);

            $detailUrl = $module->course
                ? ($meta
                    ? route(sprintf('modul.detail.%02d', $meta['meta_no']))
                    : route('module.show', [$module->course, $module]))
                : '#';

            $modulList[] = [
                'no' => $no,
                'level' => $level,
                'status' => $status,
                'title' => $module->title,
                'desc' => $descText,
                'desc_html' => $descHtml,
                'search_keywords' => $searchKeywords,
                'materi' => $total > 0 ? $total : $materiCount,
                'materiActual' => $total,
                'latihan' => (int) ($latihanPerModul[$module->id] ?? 0),
                'menit' => max($total, 1) * 10,
                'progress' => $progressPercent,
                'selesaiCount' => $readCount,
                'materiAktif' => $status === 'berjalan' && $target ? 'Materi: '.$target->title : null,
                'url' => $detailUrl,
                'href' => $detailUrl,
            ];
        }

        return view('modul', compact('modulList'));
    }

    public function detail01()
    {
        return $this->showDetail(1);
    }

    public function detail02()
    {
        return $this->showDetail(2);
    }

    public function detail03()
    {
        return $this->showDetail(3);
    }

    public function detail04()
    {
        return $this->showDetail(4);
    }

    public function detail05()
    {
        return $this->showDetail(5);
    }

    public function detail06()
    {
        return $this->showDetail(6);
    }

    public function detail07()
    {
        return $this->showDetail(7);
    }

    /**
     * Tampilkan detail pembelajaran modul sesuai nomor urut (1-7).
     */
    public function showDetail(int $no)
    {
        $meta = $this->getModuleMeta($no);
        abort_unless($meta, 404);

        $module = Module::where('slug', $meta['slug'])
            ->with(['course', 'lessons' => fn ($q) => $q->where('is_published', true)->orderBy('sort_order')])
            ->first();

        $status = 'belum';
        $selesaiCount = 0;
        $totalMateri = max($meta['materiCount'], $module?->lessons->count() ?? 0);
        $progress = 0;
        $lessonProgressUrls = $module
            ? $module->lessons->mapWithKeys(fn ($lesson) => [$lesson->sort_order => route('lesson.progress', $lesson)])->all()
            : [];
        $lessonProgressValues = [];
        $additionalLessons = $module
            ? $module->lessons->filter(fn ($lesson) => $lesson->sort_order > $meta['materiCount'])->values()
            : collect();
        $renderedAdditionalLessons = $additionalLessons->mapWithKeys(fn ($lesson) => [
            $lesson->id => Str::markdown($lesson->content, [
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]),
        ]);
        $editedLessons = $module
            ? $module->lessons->where('is_content_edited', true)->map(fn ($lesson) => [
                'sort_order' => $lesson->sort_order,
                'title' => $lesson->title,
                'html' => Str::markdown($lesson->content, [
                    'html_input' => 'strip',
                    'allow_unsafe_links' => false,
                ]),
            ])->values()
            : collect();

        if (auth()->check() && $module) {
            $lessons = $module->lessons;
            $userProgress = LessonProgress::where('user_id', auth()->id())
                ->whereIn('lesson_id', $lessons->pluck('id'))
                ->get()
                ->keyBy('lesson_id');

            if ($userProgress->isEmpty() && $lessons->isNotEmpty()) {
                LessonProgress::create([
                    'user_id' => auth()->id(),
                    'lesson_id' => $lessons->first()->id,
                    'status' => 'in_progress',
                    'last_accessed_at' => now(),
                ]);
                $userProgress = LessonProgress::where('user_id', auth()->id())
                    ->whereIn('lesson_id', $lessons->pluck('id'))
                    ->get()
                    ->keyBy('lesson_id');
            }

            $actualTotal = $lessons->count();
            $progressValues = $lessons->mapWithKeys(fn ($lesson) => [
                $lesson->id => (int) ($userProgress[$lesson->id]->progress_percent ?? (($userProgress[$lesson->id]->status ?? null) === 'completed' ? 100 : 0)),
            ]);
            $lessonProgressValues = $lessons->mapWithKeys(fn ($lesson) => [
                $lesson->sort_order => $progressValues[$lesson->id],
            ])->all();
            $completedLessons = $lessons->filter(fn ($lesson) => $progressValues[$lesson->id] >= 100)->count();
            $readLessons = $lessons->filter(fn ($lesson) => $progressValues[$lesson->id] > 0)->count();
            $hasStarted = $lessons->contains(fn ($lesson) => isset($userProgress[$lesson->id]));

            $status = match (true) {
                $actualTotal > 0 && $completedLessons === $actualTotal => 'selesai',
                $completedLessons > 0 || $hasStarted => 'berjalan',
                default => 'belum',
            };

            $progress = $actualTotal > 0
                ? (int) round($lessons->sum(fn ($lesson) => $progressValues[$lesson->id]) / $actualTotal)
                : 0;
            $selesaiCount = $actualTotal > 0 ? (int) round($readLessons / $actualTotal * $totalMateri) : 0;
            if ($status === 'selesai') {
                $selesaiCount = $totalMateri;
                $progress = 100;
            }
        }

        return view($meta['view'], compact(
            'status', 'selesaiCount', 'totalMateri', 'progress', 'no', 'module', 'additionalLessons', 'renderedAdditionalLessons', 'editedLessons', 'lessonProgressUrls', 'lessonProgressValues'
        ));
    }

    public function showModule(Course $course, Module $module)
    {
        abort_unless($course->is_published && $module->is_published, 404);

        $legacyRoute = $this->legacyModuleRoute($module);
        if ($legacyRoute) {
            return redirect()->route($legacyRoute);
        }

        $module->load(['lessons' => fn ($query) => $query->where('is_published', true)->orderBy('sort_order')]);

        return $this->renderModulePage($module, null, null);
    }

    private function renderModulePage(Module $module, ?array $meta, ?int $no): View
    {
        $lessons = $module->lessons;
        $lessonIds = $lessons->pluck('id');
        $progressRecords = auth()->check()
            ? LessonProgress::where('user_id', auth()->id())->whereIn('lesson_id', $lessonIds)->get()->keyBy('lesson_id')
            : collect();
        $statusMap = $progressRecords->mapWithKeys(fn ($record) => [$record->lesson_id => $record->status]);
        $progressMap = $progressRecords->mapWithKeys(fn ($record) => [
            $record->lesson_id => (int) ($record->progress_percent ?? ($record->status === 'completed' ? 100 : 0)),
        ]);
        $completedCount = $lessons->filter(fn ($lesson) => ($progressMap[$lesson->id] ?? 0) >= 100)->count();
        $readCount = $lessons->filter(fn ($lesson) => ($progressMap[$lesson->id] ?? 0) > 0)->count();
        $totalLessons = $lessons->count();
        $progress = $totalLessons > 0
            ? (int) round($progressMap->sum() / $totalLessons)
            : 0;
        $status = $totalLessons > 0 && $progress === 100
            ? 'selesai'
            : ($progress > 0 || $statusMap->isNotEmpty() ? 'berjalan' : 'belum');
        $renderedLessons = $lessons->mapWithKeys(fn ($lesson) => [
            $lesson->id => Str::markdown($lesson->content, [
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]),
        ]);

        return view('modul-database-detail', compact(
            'module', 'lessons', 'statusMap', 'progressMap', 'renderedLessons', 'completedCount', 'readCount', 'totalLessons', 'progress', 'status', 'meta', 'no'
        ));
    }

    /**
     * Tandai modul selesai atau kembali ke sedang berjalan.
     */
    public function toggleComplete(int $no): RedirectResponse
    {
        $meta = $this->getModuleMeta($no);
        abort_unless($meta, 404);

        $module = Module::where('slug', $meta['slug'])
            ->with(['lessons' => fn ($q) => $q->where('is_published', true)->orderBy('sort_order')])
            ->firstOrFail();

        $lessons = $module->lessons;

        $userProgress = LessonProgress::where('user_id', auth()->id())
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->get()
            ->keyBy('lesson_id');

        $isAllCompleted = $lessons->isNotEmpty() && $lessons->every(fn ($l) => ($userProgress[$l->id]->status ?? null) === 'completed');

        if ($isAllCompleted) {
            foreach ($lessons as $lesson) {
                LessonProgress::updateOrCreate(
                    ['user_id' => auth()->id(), 'lesson_id' => $lesson->id],
                    ['status' => 'in_progress', 'progress_percent' => 0, 'completed_at' => null, 'last_accessed_at' => now()]
                );
            }
            $msg = "Status Modul {$no} diatur kembali ke Sedang Berjalan.";
        } else {
            foreach ($lessons as $lesson) {
                LessonProgress::updateOrCreate(
                    ['user_id' => auth()->id(), 'lesson_id' => $lesson->id],
                    ['status' => 'completed', 'progress_percent' => 100, 'completed_at' => now(), 'last_accessed_at' => now()]
                );
            }
            $msg = "Selamat! Modul {$no} berhasil diselesaikan.";
        }

        return back()->with('status', $msg);
    }

    /**
     * Halaman baca materi (/modul/{course}/{module}/{lesson}).
     */
    public function show(Course $course, Module $module, Lesson $lesson)
    {
        abort_unless(
            $course->is_published && $module->is_published && $lesson->is_published,
            404
        );

        $legacyRoute = $this->legacyModuleRoute($module);

        return $legacyRoute
            ? redirect()->route($legacyRoute)
            : redirect()->route('module.show', [$course, $module]);
    }

    /**
     * Tandai materi selesai (butuh login).
     */
    public function complete(Course $course, Module $module, Lesson $lesson): RedirectResponse
    {
        LessonProgress::updateOrCreate(
            ['user_id' => auth()->id(), 'lesson_id' => $lesson->id],
            ['status' => 'completed', 'progress_percent' => 100, 'completed_at' => now(), 'last_accessed_at' => now()]
        );

        $legacyRoute = $this->legacyModuleRoute($module);
        $moduleUrl = $legacyRoute
            ? route($legacyRoute)
            : route('module.show', [$course, $module]);

        return redirect()->to($moduleUrl.'#materi-'.$lesson->id)
            ->with('status', 'Materi ditandai selesai. Kerja bagus!');
    }

    /**
     * Simpan persentase bacaan siswa untuk satu bagian materi.
     */
    public function recordLessonReadingProgress(Request $request, Lesson $lesson): JsonResponse
    {
        $validated = $request->validate([
            'progress_percent' => ['required', 'integer', 'between:0,100'],
        ]);
        $lesson->load('module.course');
        abort_unless($lesson->is_published && $lesson->module->is_published && $lesson->module->course->is_published, 404);

        $now = now();
        $progressPercent = $validated['progress_percent'];
        $progressData = [
            'status' => $progressPercent >= 100 ? 'completed' : 'in_progress',
            'progress_percent' => $progressPercent,
            'last_accessed_at' => $now,
            'completed_at' => $progressPercent >= 100 ? $now : null,
            'updated_at' => $now,
        ];

        DB::table('lesson_progress')->insertOrIgnore([
            'user_id' => auth()->id(),
            'lesson_id' => $lesson->id,
            ...$progressData,
            'created_at' => $now,
        ]);

        DB::table('lesson_progress')
            ->where('user_id', auth()->id())
            ->where('lesson_id', $lesson->id)
            ->where('progress_percent', '<', $progressPercent)
            ->update($progressData);

        $progressPercent = (int) LessonProgress::query()
            ->where('user_id', auth()->id())
            ->where('lesson_id', $lesson->id)
            ->value('progress_percent');

        return response()->json(['progress_percent' => $progressPercent]);
    }

    /**
     * Tandai semua bagian modul database selesai atau kembalikan ke berjalan.
     */
    public function toggleModuleCompletion(Course $course, Module $module): RedirectResponse
    {
        abort_unless($course->is_published && $module->is_published, 404);

        $lessons = $module->lessons()->where('is_published', true)->get();
        abort_if($lessons->isEmpty(), 404);

        $progress = LessonProgress::where('user_id', auth()->id())
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->get()
            ->keyBy('lesson_id');
        $isCompleted = $lessons->every(fn (Lesson $lesson) => ($progress[$lesson->id]->status ?? null) === 'completed');

        foreach ($lessons as $lesson) {
            LessonProgress::updateOrCreate(
                ['user_id' => auth()->id(), 'lesson_id' => $lesson->id],
                [
                    'status' => $isCompleted ? 'in_progress' : 'completed',
                    'progress_percent' => $isCompleted ? 0 : 100,
                    'completed_at' => $isCompleted ? null : now(),
                    'last_accessed_at' => now(),
                ]
            );
        }

        $message = $isCompleted
            ? 'Status modul diatur kembali ke Sedang Berjalan.'
            : 'Selamat! Modul berhasil diselesaikan.';

        return redirect()
            ->route('module.show', [$course, $module])
            ->with('status', $message);
    }

    private function legacyModuleRoute(Module $module): ?string
    {
        for ($number = 1; $number <= 7; $number++) {
            $meta = $this->getModuleMeta($number);

            if ($meta && $module->slug === $meta['slug']) {
                return sprintf('modul.detail.%02d', $number);
            }
        }

        return null;
    }

    /**
     * Label level per modul. Tabel `modules` belum punya kolom level,
     * jadi sementara diturunkan dari urutan modul agar sesuai desain UI.
     */
    /**
     * Metadata modul pembelajaran kurikulum Python (Modul 1 s.d. 7).
     */
    private function getModuleMeta(int $no): ?array
    {
        $meta = [
            1 => [
                'slug' => 'dasar-python-sintaks-awal',
                'view' => 'modul-detail',
                'title' => 'Dasar Python & Sintaks Awal',
                'desc_html' => 'Mengenal struktur program Python, fungsi <code class="bg-gray-100 px-1 rounded text-xs">print()</code>, komentar kode, dan aturan indentasi yang menjadi ciri khas Python.',
                'search_keywords' => 'mengenal struktur program python fungsi print komentar kode aturan indentasi',
                'level' => 'Pemula',
                'materiCount' => 5,
            ],
            2 => [
                'slug' => 'variabel-dan-tipe-data',
                'view' => 'modul-02-detail',
                'title' => 'Variabel dan Tipe Data',
                'desc_html' => 'Memahami cara menyimpan data ke dalam variabel, tipe data angka (integer & float), teks (string), boolean, serta konversi tipe data dinamis.',
                'search_keywords' => 'memahami cara menyimpan data integer float string boolean konversi tipe data dinamis',
                'level' => 'Pemula',
                'materiCount' => 6,
            ],
            3 => [
                'slug' => 'percabangan-logika-kondisional',
                'view' => 'modul-03-detail',
                'title' => 'Percabangan & Logika Kondisional',
                'desc_html' => 'Membangun logika keputusan menggunakan <code class="bg-gray-100 px-1 rounded text-xs">if</code>, <code class="bg-gray-100 px-1 rounded text-xs">elif</code>, dan <code class="bg-gray-100 px-1 rounded text-xs">else</code>. Menggunakan operator logika and, or, not.',
                'search_keywords' => 'percabangan logika kondisional if elif else operator logika and or not',
                'level' => 'Pemula',
                'materiCount' => 5,
            ],
            4 => [
                'slug' => 'perulangan-loops',
                'view' => 'modul-04-detail',
                'title' => 'Perulangan (Loops)',
                'desc_html' => 'Otomatisasi tugas berulang dengan for loop, fungsi <code class="bg-gray-100 px-1 rounded text-xs">range()</code>, while loop, serta kontrol perulangan break dan continue.',
                'search_keywords' => 'perulangan loops otomatisasi for loop range while break continue',
                'level' => 'Menengah Bawah',
                'materiCount' => 6,
            ],
            5 => [
                'slug' => 'fungsi-modularitas-kode',
                'view' => 'modul-05-detail',
                'title' => 'Fungsi & Modularitas Kode',
                'desc_html' => 'Membuat fungsi mandiri dengan <code class="bg-gray-100 px-1 rounded text-xs">def</code>, parameter, argumen default, return value, dan memahami konsep local vs global scope.',
                'search_keywords' => 'fungsi modularitas kode def parameter argumen default return value local global scope',
                'level' => 'Menengah',
                'materiCount' => 3,
            ],
            6 => [
                'slug' => 'struktur-data-dasar',
                'view' => 'modul-06-detail',
                'title' => 'Struktur Data Dasar',
                'desc_html' => 'Mengelola kumpulan data terstruktur: operasi indexing & slicing pada List, ketetapan Tuple, pasangan key-value Dictionary, dan keunikan Set.',
                'search_keywords' => 'struktur data dasar list tuple dictionary set indexing slicing key value',
                'level' => 'Menengah',
                'materiCount' => 3,
            ],
            7 => [
                'slug' => 'pengantar-pandas-numpy',
                'view' => 'modul-07-detail',
                'title' => 'Pengantar Pandas & NumPy',
                'desc_html' => 'Pengenalan library analisis data Python: membuat dan memanipulasi array NumPy, serta bekerja dengan DataFrame dan Series pada Pandas.',
                'search_keywords' => 'pengantar pandas numpy analisis data array dataframe series manipulasi data',
                'level' => 'Menengah',
                'materiCount' => 6,
            ],
        ];

        return $meta[$no] ?? null;
    }
}
