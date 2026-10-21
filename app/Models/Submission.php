<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'submitted_at',
    ];

    // Mengubah tanggal agar otomatis menjadi instance Carbon
    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(SubmissionResult::class);
    }
}
