<?php

namespace Tests\Feature;

use App\Models\Exercise;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ExerciseRelationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_test_cases_relation_only_returns_active_cases(): void
    {
        $exercise = $this->createExerciseWithCases([
            ['is_active' => true, 'is_hidden' => false],
            ['is_active' => false, 'is_hidden' => false],
            ['is_active' => true, 'is_hidden' => true],
        ]);

        $this->assertCount(2, $exercise->testCases);
        $this->assertTrue($exercise->testCases->every(fn ($case): bool => $case->is_active));
    }

    public function test_public_test_cases_exclude_hidden_and_inactive_cases(): void
    {
        $exercise = $this->createExerciseWithCases([
            ['is_active' => true, 'is_hidden' => false],
            ['is_active' => false, 'is_hidden' => false],
            ['is_active' => true, 'is_hidden' => true],
        ]);

        $this->assertCount(1, $exercise->publicTestCases()->get());
    }

    public function test_all_test_cases_relation_includes_inactive_and_hidden_cases_in_order(): void
    {
        $exercise = $this->createExerciseWithCases([
            ['is_active' => true, 'is_hidden' => false],
            ['is_active' => false, 'is_hidden' => false],
            ['is_active' => true, 'is_hidden' => true],
        ]);

        $this->assertCount(3, $exercise->allTestCases);
        $this->assertSame([1, 2, 3], $exercise->allTestCases->pluck('sort_order')->all());
    }

    public function test_submissions_relation_only_returns_submissions_of_that_exercise(): void
    {
        $exercise = $this->createExerciseWithCases([['is_active' => true, 'is_hidden' => false]]);
        $other = $this->createExerciseWithCases([['is_active' => true, 'is_hidden' => false]], 'lain');
        $user = User::factory()->create();

        $this->createSubmission($user, $exercise);
        $this->createSubmission($user, $exercise);
        $this->createSubmission($user, $other);

        $this->assertCount(2, $exercise->submissions);
        $this->assertCount(1, $other->submissions);
    }

    /**
     * @param  array<int, array{is_active: bool, is_hidden: bool}>  $cases
     */
    private function createExerciseWithCases(array $cases, string $slug = 'jumlah'): Exercise
    {
        $now = now();
        $courseId = DB::table('courses')->insertGetId([
            'title' => 'Python',
            'slug' => 'python-'.$slug,
            'description' => 'Test course',
            'level' => 'beginner',
            'is_published' => true,
            'sort_order' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $moduleId = DB::table('modules')->insertGetId([
            'course_id' => $courseId,
            'title' => 'Dasar '.$slug,
            'slug' => 'dasar-'.$slug,
            'description' => 'Test module',
            'sort_order' => 1,
            'is_published' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $lessonId = DB::table('lessons')->insertGetId([
            'module_id' => $moduleId,
            'title' => 'Fungsi',
            'slug' => 'fungsi',
            'content' => 'Test lesson',
            'sort_order' => 1,
            'is_published' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $exerciseId = DB::table('exercises')->insertGetId([
            'lesson_id' => $lessonId,
            'title' => 'Soal '.$slug,
            'slug' => $slug,
            'description' => 'Deskripsi.',
            'instructions' => '',
            'starter_code' => '',
            'difficulty' => 'easy',
            'time_limit_ms' => 10000,
            'memory_limit_mb' => 128,
            'sort_order' => 1,
            'is_published' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        foreach ($cases as $index => $case) {
            DB::table('test_cases')->insert([
                'exercise_id' => $exerciseId,
                'input' => 'x',
                'expected_output' => 'y',
                'assertion_code' => null,
                'weight' => 1,
                'is_hidden' => $case['is_hidden'],
                'is_active' => $case['is_active'],
                'sort_order' => $index + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return Exercise::findOrFail($exerciseId);
    }

    private function createSubmission(User $user, Exercise $exercise): Submission
    {
        return Submission::create([
            'user_id' => $user->id,
            'exercise_id' => $exercise->id,
            'source_code' => 'print(1)',
            'status' => 'failed',
            'score' => 0,
            'passed_tests' => 0,
            'total_tests' => 1,
            'execution_time_ms' => 0,
            'submitted_at' => now(),
        ]);
    }
}
