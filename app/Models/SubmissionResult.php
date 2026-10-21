<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionResult extends Model
{
    protected $fillable = [
        'submission_id',
        'test_case_id',
        'status',
        'actual_output',
        'error_message',
        'execution_time_ms'
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }
}