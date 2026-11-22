<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\{User, Exercise, Submission};

class ExerciseServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_exercise_memiliki_banyak_submission(): void
    {
        $exercise = Exercise::factory()->create();
        Submission::factory()->count(2)->create([
            'exercise_id' => $exercise->id,
            'user_id' => User::factory()->create()->id,
        ]);

        $this->assertCount(2, $exercise->submissions);
    }

    public function test_submission_milik_satu_user(): void
    {
        $user = User::factory()->create();
        $submission = Submission::factory()->create([
            'user_id' => $user->id,
            'exercise_id' => Exercise::factory()->create()->id,
        ]);

        $this->assertEquals($user->id, $submission->user->id);
    }
}