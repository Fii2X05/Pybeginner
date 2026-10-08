<?php

namespace App\Http\Controllers;

use App\Models\Exercise;

class ExerciseController extends Controller
{
    // Halaman daftar soal
    public function index()
    {
        $exercises = Exercise::where('is_published', true)
            ->orderBy('sort_order')
            ->get();

        return view('latihan', compact('exercises'));
    }

    // Halaman detail soal + editor
    public function show(string $slug)
    {
        $exercise = Exercise::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $samples = $exercise->publicTestCases()->get();

        return view('playground', compact('exercise', 'samples'));
    }
}