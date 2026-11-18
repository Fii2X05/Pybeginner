<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'title'        => $title,
            'slug'         => \Illuminate\Support\Str::slug($title),
            'description'  => fake()->sentence(),
            'level'        => 'beginner',
            'is_published' => true,
            'sort_order'   => 0,
        ];
    }
}