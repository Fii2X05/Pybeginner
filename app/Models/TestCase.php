<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestCase extends Model
{
    protected $table = 'test_cases';

    protected $fillable = [
        'exercise_id',
        'input',
        'expected_output',
        'assertion_code',
        'weight',
        'is_hidden',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_hidden' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(SubmissionResult::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(TestCaseProgress::class);
    }
}
