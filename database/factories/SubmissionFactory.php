<?php

namespace Database\Factories;

use App\Models\Exercise;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubmissionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'      => User::factory(),
            'exercise_id'  => Exercise::factory(),
            'source_code'  => 'print(1)',
            'status'       => 'pending',
            'score'        => 0,
            'passed_tests' => 0,
            'total_tests'  => 0,
            'submitted_at' => now(),
        ];
    }
}