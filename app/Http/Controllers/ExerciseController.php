<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExerciseController extends Controller
{
    public const LEVELS = [
        'easy'   => ['label' => 'Pemula',   'desc' => 'Input, output, dan percabangan dasar.', 'badge' => 'bg-emerald-100 text-emerald-700'],
        'medium' => ['label' => 'Menengah', 'desc' => 'Perulangan, string, dan perhitungan.',   'badge' => 'bg-amber-100 text-amber-700'],
        'hard'   => ['label' => 'Sulit',    'desc' => 'Logika algoritma dasar.',                'badge' => 'bg-rose-100 text-rose-700'],
    ];

    public function index(Request $request)
    {
        $level = $request->query('level');
        if (!isset(self::LEVELS[$level])) {
            $level = null;
        }

        $counts = Exercise::where('is_published', true)
            ->selectRaw('difficulty, count(*) as total')
            ->groupBy('difficulty')
            ->pluck('total', 'difficulty');

        $exercises = $level
            ? Exercise::where('is_published', true)->where('difficulty', $level)->orderBy('sort_order')->get()
            : collect();

        // Progres belajar user (kosong untuk tamu)
        $solvedIds = $this->solvedExerciseIds();

        $solvedCounts = empty($solvedIds)
            ? collect()
            : Exercise::where('is_published', true)
                ->whereIn('id', $solvedIds)
                ->selectRaw('difficulty, count(*) as total')
                ->groupBy('difficulty')
                ->pluck('total', 'difficulty');

        return view('latihan', [
            'levels'       => self::LEVELS,
            'counts'       => $counts,
            'level'        => $level,
            'exercises'    => $exercises,
            'solvedIds'    => $solvedIds,
            'solvedCounts' => $solvedCounts,
        ]);
    }

    public function show(string $slug)
    {
        $exercise = Exercise::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $samples  = $exercise->publicTestCases()->get();
        $lesson   = DB::table('lessons')->where('id', $exercise->lesson_id)->first();

        return view('playground', [
            'exercise' => $exercise,
            'samples'  => $samples,
            'lesson'   => $lesson,
            'levels'   => self::LEVELS,
        ]);
    }

    /**
     * ID soal yang sudah dikerjakan sempurna (skor 100) oleh user yang login.
     * ASUMSI: tabel `submissions` punya kolom user_id, exercise_id, dan score.
     * Kalau nama kolom di projectmu berbeda, ubah di sini saja.
     * Kalau kolomnya tidak ada, hasilnya kosong sehingga halaman tetap jalan.
     */
    private function solvedExerciseIds(): array
    {
        $user = request()->user();

        if (!$user
            || !Schema::hasTable('submissions')
            || !Schema::hasColumns('submissions', ['user_id', 'exercise_id', 'score'])) {
            return [];
        }

        return DB::table('submissions')
            ->where('user_id', $user->id)
            ->where('score', '>=', 100)
            ->distinct()
            ->pluck('exercise_id')
            ->all();
    }
}