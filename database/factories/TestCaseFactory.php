<?php

namespace Database\Factories;

use App\Models\Exercise;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestCaseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'exercise_id'     => Exercise::factory(),
            'input'           => '2 3',
            'expected_output' => '5',
            'weight'          => 1,
            'is_hidden'       => false,
            'is_active'       => true,
            'sort_order'      => 0,
        ];
    }
}