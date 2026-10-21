<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exercise extends Model
{
    protected $fillable = [
        'lesson_id', 'title', 'slug', 'description', 'instructions',
        'starter_code', 'difficulty', 'time_limit_ms', 'memory_limit_mb',
        'sort_order', 'is_published',
    ];

    // Semua test case aktif soal ini
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function testCases(): HasMany
    {
        return $this->hasMany(TestCase::class, 'exercise_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    // Hanya test case yang boleh dilihat learner
    public function publicTestCases()
    {
        return $this->testCases()->where('is_hidden', false);
    }
}
