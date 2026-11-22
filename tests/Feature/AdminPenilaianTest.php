<?php

namespace Tests\Feature;

use App\Models\Exercise;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminPenilaianTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_failed_submission_is_labelled_belum_lolos(): void
    {
        $this->makeSubmission('failed', 40);
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->get(route('admin.penilaian'))
            ->assertOk()
            ->assertSee('Belum Lolos')
            ->assertDontSee('Failed');
    }

    public function test_accepted_submission_is_labelled_lolos(): void
    {
        $this->makeSubmission('accepted', 100);
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->get(route('admin.penilaian'))
            ->assertOk()
            ->assertSee('Lolos')
            ->assertDontSee('Belum Lolos');
    }

    private function makeSubmission(string $status, int $score): Submission
    {
        $now = now();
        $uid = uniqid();

        $courseId = DB::table('courses')->insertGetId([
            'title' => 'Python', 'slug' => 'python-'.$uid, 'description' => 'Test course',
            'level' => 'beginner', 'is_published' => true, 'sort_order' => 1,
            'created_at' => $now, 'updated_at' => $now,
        ]);
        $moduleId = DB::table('modules')->insertGetId([
            'course_id' => $courseId, 'title' => 'Dasar '.$uid, 'slug' => 'dasar-'.$uid,
            'description' => 'Test module', 'sort_order' => 1, 'is_published' => true,
            'created_at' => $now, 'updated_at' => $now,
        ]);
        $lessonId = DB::table('lessons')->insertGetId([
            'module_id' => $moduleId, 'title' => 'Fungsi', 'slug' => 'pelajaran-'.$uid,
            'content' => 'Test lesson', 'sort_order' => 1, 'is_published' => true,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $exercise = Exercise::create([
            'lesson_id' => $lessonId,
            'title' => 'Jumlah',
            'slug' => 'jumlah-'.$uid,
            'description' => 'Jumlahkan dua bilangan.',
            'difficulty' => 'easy',
            'time_limit_ms' => 2000,
            'memory_limit_mb' => 128,
            'sort_order' => 1,
            'is_published' => true,
        ]);

        return Submission::create([
            'user_id' => User::factory()->create(['role' => 'learner'])->id,
            'exercise_id' => $exercise->id,
            'source_code' => 'print(1)',
            'status' => $status,
            'score' => $score,
            'passed_tests' => $score === 100 ? 1 : 0,
            'total_tests' => 1,
            'execution_time_ms' => 10,
            'submitted_at' => now(),
        ]);
    }
}