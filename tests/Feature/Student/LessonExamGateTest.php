<?php

namespace Tests\Feature\Student;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\MediaAsset;
use App\Models\Quiz;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonExamGateTest extends TestCase
{
    use RefreshDatabase;

    private User $student;

    private Course $course;

    private Lesson $lesson;

    private Quiz $quiz;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::query()->create([
            'slug' => 'student',
            'name_ar' => 'طالب',
            'dashboard_key' => 'student',
        ]);

        $this->student = User::factory()->create(['status' => 'active']);
        $this->student->roles()->attach($role->id);

        $this->course = Course::query()->create([
            'title' => 'مقرر الاختبار',
            'status' => 'published',
        ]);

        Enrollment::query()->create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        $this->quiz = Quiz::query()->create([
            'course_id' => $this->course->id,
            'title' => 'اختبار بعد الفيديو',
            'status' => 'published',
        ]);

        $this->lesson = Lesson::query()->create([
            'course_id' => $this->course->id,
            'title' => 'درس مع اختبار',
            'status' => 'published',
            'position' => 1,
            'quiz_id' => $this->quiz->id,
        ]);

        $video = MediaAsset::query()->create([
            'lesson_id' => $this->lesson->id,
            'type' => 'video',
            'disk' => 'local_private',
            'path' => 'lessons/y/v.mp4',
            'original_name' => 'v.mp4',
            'mime' => 'video/mp4',
            'size' => 100,
        ]);

        $this->lesson->forceFill(['main_media_asset_id' => $video->id])->save();
    }

    public function test_quiz_start_blocked_until_video_completed(): void
    {
        $response = $this->actingAs($this->student)->post(route('student.quizzes.start', $this->quiz));
        $response->assertForbidden();
    }

    public function test_quiz_start_allowed_after_video_completed(): void
    {
        $this->actingAs($this->student)->postJson(route('student.lessons.progress', $this->lesson), [
            'position_seconds' => 120,
            'completed' => true,
        ])->assertOk()->assertJson(['exam_unlocked' => true]);

        $response = $this->actingAs($this->student)->post(route('student.quizzes.start', $this->quiz));
        $response->assertRedirect();
    }

    public function test_finishing_video_marks_lesson_completed(): void
    {
        $this->actingAs($this->student)->postJson(route('student.lessons.progress', $this->lesson), [
            'position_seconds' => 200,
            'completed' => true,
        ])->assertOk()->assertJson([
            'video_completed' => true,
            'lesson_completed' => true,
        ]);

        $this->assertDatabaseHas('lesson_progress', [
            'user_id' => $this->student->id,
            'lesson_id' => $this->lesson->id,
            'status' => 'completed',
        ]);
    }

    public function test_no_video_lesson_unlocks_exam_after_complete(): void
    {
        $this->lesson->forceFill(['main_media_asset_id' => null])->save();

        $this->actingAs($this->student)->post(route('student.quizzes.start', $this->quiz))->assertForbidden();

        $this->actingAs($this->student)->post(route('student.lessons.complete', $this->lesson))->assertRedirect();

        $this->actingAs($this->student)->post(route('student.quizzes.start', $this->quiz))->assertRedirect();
    }

    public function test_manual_complete_forbidden_when_lesson_has_main_video(): void
    {
        $this->actingAs($this->student)
            ->post(route('student.lessons.complete', $this->lesson))
            ->assertForbidden();
    }
}
