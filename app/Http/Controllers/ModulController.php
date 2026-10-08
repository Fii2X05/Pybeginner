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
     * Data modul + progres user disiapkan di sini supaya view tinggal menampilkan.
     */
    public function index()
    {
        $modules = Module::query()
            ->where('is_published', true)
            ->whereHas('course', fn ($q) => $q->where('is_published', true))
            ->with([
                'course',
                'lessons' => fn ($q) => $q->where('is_published', true),
            ])
            ->get()
            ->sortBy([
                fn ($a, $b) => $a->course->sort_order <=> $b->course->sort_order,
                fn ($a, $b) => $a->sort_order <=> $b->sort_order,
            ])
            ->values();

        // Jumlah latihan per modul. Pakai query langsung ke tabel `exercises`
        // supaya tidak bergantung pada model Exercise milik Dinda.
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

        foreach ($modules as $i => $module) {
            $lessons = $module->lessons;
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
                'no' => $i + 1,
                'level' => $this->levelLabel($i + 1),
                'status' => $status,
                'title' => $module->title,
                'desc' => $module->description,
                'materi' => $total,
                'latihan' => (int) ($latihanPerModul[$module->id] ?? 0),
                'menit' => $total * 10, // estimasi: 10 menit per materi (belum ada kolom durasi)
                'progress' => $total > 0 ? (int) round($selesaiCount / $total * 100) : 0,
                'selesaiCount' => $selesaiCount,
                'materiAktif' => $status === 'berjalan' && $target ? 'Materi: ' . $target->title : null,
                'href' => $target
                    ? route('lesson.show', [$module->course, $module, $target])
                    : null,
            ];
        }

        return view('modul', compact('modulList'));
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
    private function levelLabel(int $urutan): string
    {
        return match (true) {
            $urutan <= 3 => 'Pemula',
            $urutan === 4 => 'Menengah Bawah',
            default => 'Menengah',
        };
    }
}
