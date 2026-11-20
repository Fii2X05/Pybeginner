<?php

namespace Tests\Feature;

use App\Models\{Exercise, TestCase as TestCaseModel};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExerciseIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_factory_exercise_terbentuk(): void
    {
        $exercise = Exercise::factory()->create();

        $this->assertDatabaseHas('exercises', ['id' => $exercise->id]);
    }

    public function test_hanya_test_case_publik_yang_tampil_ke_learner(): void
    {
        $exercise = Exercise::factory()->create();
        TestCaseModel::factory()->create(['exercise_id' => $exercise->id, 'is_hidden' => false]);
        TestCaseModel::factory()->create(['exercise_id' => $exercise->id, 'is_hidden' => true]);
        TestCaseModel::factory()->create(['exercise_id' => $exercise->id, 'is_active' => false]);

        $this->assertCount(1, $exercise->publicTestCases()->get());
        $this->assertCount(2, $exercise->testCases);
        $this->assertCount(3, $exercise->allTestCases);
    }
}