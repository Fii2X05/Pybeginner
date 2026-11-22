<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Module;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModulLearnerTest extends TestCase
{
    use RefreshDatabase;

    public function test_module_list_only_displays_published_modules(): void
    {
        $course = $this->createCourse();
        $publishedModule = $this->createModule($course, 'Materi Terbit', 'materi-terbit', true);
        $this->createLesson($publishedModule, 'Lesson Terbit', 'lesson-terbit', true);
        $draftModule = $this->createModule($course, 'Materi Draft', 'materi-draft', false);
        $this->createLesson($draftModule, 'Lesson Draft', 'lesson-draft', true);

        $response = $this->actingAs(User::factory()->create())->get(route('modul.index'));

        $response->assertOk()->assertSee('Materi Terbit')->assertDontSee('Materi Draft');
    }

    public function test_module_detail_shows_published_lessons_only(): void
    {
        $course = $this->createCourse();
        $module = $this->createModule($course, 'Modul Terbit', 'modul-terbit', true);
        $this->createLesson($module, 'Lesson Terbit', 'lesson-terbit', true);
        $this->createLesson($module, 'Lesson Draft', 'lesson-draft', false);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('module.show', [$course, $module]));

        $response->assertOk()->assertSee('Lesson Terbit')->assertDontSee('Lesson Draft');
    }

    public function test_unpublished_modules_and_lessons_cannot_be_opened(): void
    {
        $course = $this->createCourse();
        $module = $this->createModule($course, 'Modul Draft', 'modul-draft', false);
        $draftLesson = $this->createLesson($module, 'Lesson Draft', 'lesson-draft', false);
        $publishedModule = $this->createModule($course, 'Modul Publik', 'modul-publik', true);
        $publishedLesson = $this->createLesson($publishedModule, 'Lesson Publik', 'lesson-publik', true);

        $this->actingAs(User::factory()->create())
            ->get(route('module.show', [$course, $module]))
            ->assertNotFound();

        $this->get(route('lesson.show', [$course, $publishedModule, $draftLesson]))->assertNotFound();
        $this->get(route('lesson.show', [$course, $module, $publishedLesson]))->assertNotFound();
        $this->postJson(route('lesson.progress', $draftLesson), ['progress_percent' => 50])->assertNotFound();
    }

    public function test_reading_progress_is_saved_and_does_not_decrease(): void
    {
        $course = $this->createCourse();
        $module = $this->createModule($course, 'Modul Progres', 'modul-progres', true);
        $lesson = $this->createLesson($module, 'Lesson Progres', 'lesson-progres', true);
        $user = User::factory()->create();

        $this->actingAs($user)->postJson(route('lesson.progress', $lesson), ['progress_percent' => 40])
            ->assertOk()->assertJsonPath('progress_percent', 40);
        $this->postJson(route('lesson.progress', $lesson), ['progress_percent' => 20])
            ->assertOk()->assertJsonPath('progress_percent', 40);

        $this->assertDatabaseHas('lesson_progress', [
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'status' => 'in_progress',
            'progress_percent' => 40,
        ]);
    }

    public function test_reading_progress_is_isolated_between_users(): void
    {
        $course = $this->createCourse();
        $module = $this->createModule($course, 'Modul Pengguna', 'modul-pengguna', true);
        $lesson = $this->createLesson($module, 'Lesson Pengguna', 'lesson-pengguna', true);
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        $this->actingAs($firstUser)->postJson(route('lesson.progress', $lesson), ['progress_percent' => 70])
            ->assertOk()->assertJsonPath('progress_percent', 70);
        $this->actingAs($secondUser)->postJson(route('lesson.progress', $lesson), ['progress_percent' => 25])
            ->assertOk()->assertJsonPath('progress_percent', 25);

        $this->assertDatabaseHas('lesson_progress', [
            'user_id' => $firstUser->id,
            'lesson_id' => $lesson->id,
            'progress_percent' => 70,
        ]);
        $this->assertDatabaseHas('lesson_progress', [
            'user_id' => $secondUser->id,
            'lesson_id' => $lesson->id,
            'progress_percent' => 25,
        ]);
        $this->assertSame(2, LessonProgress::where('lesson_id', $lesson->id)->count());
    }

    public function test_lesson_can_be_marked_complete(): void
    {
        $course = $this->createCourse();
        $module = $this->createModule($course, 'Modul Penyelesaian', 'modul-penyelesaian', true);
        $lesson = $this->createLesson($module, 'Lesson Penyelesaian', 'lesson-penyelesaian', true);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('lesson.complete', [$course, $module, $lesson]));

        $response->assertRedirect(route('module.show', [$course, $module])."#materi-{$lesson->id}");
        $this->assertDatabaseHas('lesson_progress', [
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'status' => 'completed',
            'progress_percent' => 100,
        ]);
        $this->assertNotNull(LessonProgress::where('user_id', $user->id)->where('lesson_id', $lesson->id)->value('completed_at'));
    }

    private function createCourse(): Course
    {
        return Course::query()->create([
            'title' => 'Kursus Learner',
            'slug' => 'kursus-learner',
            'is_published' => true,
            'sort_order' => 1,
        ]);
    }

    private function createModule(Course $course, string $title, string $slug, bool $isPublished): Module
    {
        return Module::query()->create([
            'course_id' => $course->id,
            'title' => $title,
            'slug' => $slug,
            'is_published' => $isPublished,
            'sort_order' => 1,
        ]);
    }

    private function createLesson(Module $module, string $title, string $slug, bool $isPublished): Lesson
    {
        return Lesson::query()->create([
            'module_id' => $module->id,
            'title' => $title,
            'slug' => $slug,
            'content' => 'Konten materi.',
            'is_published' => $isPublished,
            'sort_order' => 1,
        ]);
    }
}
