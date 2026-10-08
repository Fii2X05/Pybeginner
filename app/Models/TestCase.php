<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestCase extends Model
{
    protected $table = 'test_cases';

      protected $fillable = ['exercise_id', 'stdin', 'expected_output', 'is_hidden'];

    protected $casts = ['is_hidden' => 'boolean'];

    // Supaya $tc->input di Blade sama dengan $tc->stdin
    public function getInputAttribute(): ?string
    {
        return $this->stdin;
    }
}