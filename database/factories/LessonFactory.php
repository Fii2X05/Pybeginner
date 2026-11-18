<?php

namespace Database\Factories;

use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LessonFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'module_id'    => Module::factory(),
            'title'        => $title,
            'slug'         => Str::slug($title),
            'content'      => fake()->paragraph(),
            'sort_order'   => 0,
            'is_published' => true,
        ];
    }
}