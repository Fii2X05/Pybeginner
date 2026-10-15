<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Module;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ModulController extends Controller
{
    /**
     * Halaman daftar modul (/modul).
     * Data modul + progres user disiapkan di sini supaya view menampilkan status dinamis.
     */
    public function index()
    {
        $metaList = [];
        for ($i = 1; $i <= 7; $i++) {
            $metaList[$i] = $this->getModuleMeta($i);
        }

        $slugs = array_column($metaList, 'slug');

        $modules = Module::query()
            ->where('is_published', true)
            ->whereIn('slug', $slugs)
            ->with([
                'course',
                'lessons' => fn ($q) => $q->where('is_published', true)->orderBy('sort_order'),
            ])
            ->get()
            ->keyBy('slug');

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

        foreach ($metaList as $no => $meta) {
            $module = $modules->get($meta['slug']);
            $lessons = $module ? $module->lessons : collect();
            $total = $lessons->count();

            $selesaiCount = $lessons
                ->filter(fn ($l) => ($progress[$l->id]->status ?? null) === 'completed')
                ->count();

            $sudahMulai = $lessons->contains(fn ($l) => isset($progress[$l->id]));

            $status = match (true) {
                $total > 0 && $selesaiCount === $total => 'selesai',
                $selesaiCount > 0 || $sudahMulai => 'berjalan',
                default => 'belum',
            };

            // Materi yang dituju tombol: yang pertama belum selesai (atau pertama kalau semua selesai).
            $target = $lessons->first(fn ($l) => ($progress[$l->id]->status ?? null) !== 'completed')
                ?? $lessons->first();

            $modulList[] = [
                'no' => $no,
                'level' => $meta['level'],
                'status' => $status,
                'title' => $meta['title'],
                'desc' => strip_tags($meta['desc_html']),
                'desc_html' => $meta['desc_html'],
                'search_keywords' => $meta['search_keywords'],
                'materi' => $meta['materiCount'],
                'materiActual' => $total,
                'latihan' => $module ? (int) ($latihanPerModul[$module->id] ?? 0) : 0,
                'menit' => $meta['materiCount'] * 10,
                'progress' => $total > 0 ? (int) round($selesaiCount / $total * 100) : 0,
                'selesaiCount' => $total > 0 ? (int) round($selesaiCount / $total * $meta['materiCount']) : 0,
                'materiAktif' => $status === 'berjalan' && $target ? 'Materi: '.$target->title : null,
                'url' => route(sprintf('modul.detail.%02d', $no)),
                'href' => $target && $module && $module->course
                    ? route('lesson.show', [$module->course, $module, $target])
                    : null,
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
            ->with(['lessons' => fn ($q) => $q->where('is_published', true)->orderBy('sort_order')])
            ->first();

        $status = 'belum';
        $selesaiCount = 0;
        $totalMateri = $meta['materiCount'];
        $progress = 0;

        if (auth()->check() && $module) {
            $lessons = $module->lessons;
            $userProgress = LessonProgress::where('user_id', auth()->id())
                ->whereIn('lesson_id', $lessons->pluck('id'))
                ->get()
                ->keyBy('lesson_id');

            // Jika user membuka halaman modul untuk pertama kali, otomatis tandai materi pertama sedang berjalan
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
            $completedLessons = $lessons->filter(fn ($l) => ($userProgress[$l->id]->status ?? null) === 'completed')->count();
            $sudahMulai = $lessons->contains(fn ($l) => isset($userProgress[$l->id]));

            $status = match (true) {
                $actualTotal > 0 && $completedLessons === $actualTotal => 'selesai',
                $completedLessons > 0 || $sudahMulai => 'berjalan',
                default => 'belum',
            };

            $progress = $actualTotal > 0 ? (int) round($completedLessons / $actualTotal * 100) : 0;
            $selesaiCount = $actualTotal > 0 ? (int) round($completedLessons / $actualTotal * $totalMateri) : 0;
            if ($status === 'selesai') {
                $selesaiCount = $totalMateri;
                $progress = 100;
            }
        }

        return view($meta['view'], compact('status', 'selesaiCount', 'totalMateri', 'progress', 'no', 'module'));
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
                    ['status' => 'in_progress', 'completed_at' => null, 'last_accessed_at' => now()]
                );
            }
            $msg = "Status Modul {$no} diatur kembali ke Sedang Berjalan.";
        } else {
            foreach ($lessons as $lesson) {
                LessonProgress::updateOrCreate(
                    ['user_id' => auth()->id(), 'lesson_id' => $lesson->id],
                    ['status' => 'completed', 'completed_at' => now(), 'last_accessed_at' => now()]
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

        $lessons = $module->lessons()->where('is_published', true)->get();

        $index = $lessons->search(fn ($l) => $l->id === $lesson->id);
        $prev = $index > 0 ? $lessons[$index - 1] : null;
        $next = $lessons[$index + 1] ?? null;

        $statusMap = collect();
        if (auth()->check()) {
            $progress = LessonProgress::firstOrNew([
                'user_id' => auth()->id(),
                'lesson_id' => $lesson->id,
            ]);
            $progress->status = $progress->status === 'completed' ? 'completed' : 'in_progress';
            $progress->last_accessed_at = now();
            $progress->save();

            $statusMap = LessonProgress::where('user_id', auth()->id())
                ->whereIn('lesson_id', $lessons->pluck('id'))
                ->pluck('status', 'lesson_id');
        }

        $isCompleted = ($statusMap[$lesson->id] ?? null) === 'completed';

        // Isi materi ditulis dalam Markdown; HTML mentah dibuang demi keamanan.
        $html = Str::markdown($lesson->content, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        return view('lesson', compact(
            'course', 'module', 'lesson', 'lessons', 'prev', 'next', 'statusMap', 'isCompleted', 'html'
        ));
    }

    /**
     * Tandai materi selesai (butuh login).
     */
    public function complete(Course $course, Module $module, Lesson $lesson): RedirectResponse
    {
        LessonProgress::updateOrCreate(
            ['user_id' => auth()->id(), 'lesson_id' => $lesson->id],
            ['status' => 'completed', 'completed_at' => now(), 'last_accessed_at' => now()]
        );

        $next = $module->lessons()
            ->where('is_published', true)
            ->where('sort_order', '>', $lesson->sort_order)
            ->first();

        return $next
            ? redirect()->route('lesson.show', [$course, $module, $next])
            : redirect()->route('modul.index')->with('status', 'Modul selesai, kerja bagus!');
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
