<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exercise_id',
        'source_code',
        'status',
        'score',
        'passed_tests',
        'total_tests',
        'execution_time_ms',
        'judge0_status_id',
        'judge0_status_description',
        'user_stdout',
        'error_message',
        'details',
        'submitted_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'score' => 'float',
            'passed_tests' => 'integer',
            'total_tests' => 'integer',
            'execution_time_ms' => 'integer',
            'judge0_status_id' => 'integer',
            'details' => 'array',
            'submitted_at' => 'datetime',
        ];
    }

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
