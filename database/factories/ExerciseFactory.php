<?php

namespace Database\Factories;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ExerciseFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'lesson_id'    => Lesson::factory(),
            'title'        => $title,
            'slug'         => Str::slug($title),
            'description'  => fake()->paragraph(),
            'instructions' => fake()->sentence(),
            'starter_code' => 'def solve():',
            'difficulty'   => 'easy',
            'time_limit_ms'   => 10000,
            'memory_limit_mb' => 128,
            'sort_order'   => 0,
            'is_published' => true,
        ];
    }
}