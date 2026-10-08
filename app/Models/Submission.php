<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'user_id',
        'exercise_id',
        'source_code',
        'status',
        'score',
        'passed_tests',
        'total_tests',
        'execution_time_ms',
        'submitted_at'
    ];

    // Mengubah tanggal agar otomatis menjadi instance Carbon
    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    // Relasi ke tabel submission_results
    public function results()
    {
        return $this->hasMany(SubmissionResult::class);
    }
}