<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestCaseProgress extends Model
{
    protected $fillable = [
        'user_id',
        'exercise_id',
        'test_case_id',
        'code_hash',
        'status',
        'actual_output',
        'error_message',
        'execution_time_ms',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    public function testCase(): BelongsTo
    {
        return $this->belongsTo(TestCase::class);
    }
}
