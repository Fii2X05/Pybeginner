<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ModuleFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'course_id'    => Course::factory(),
            'title'        => $title,
            'slug'         => Str::slug($title),
            'description'  => fake()->sentence(),
            'sort_order'   => 0,
            'is_published' => true,
        ];
    }
}