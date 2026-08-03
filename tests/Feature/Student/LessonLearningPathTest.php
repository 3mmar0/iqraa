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

class LessonLearningPathTest extends TestCase
{
    use RefreshDatabase;

    private User $student;

    private Course $course;

    private Lesson $lesson;

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
            'title' => 'مقرر الطالب',
            'status' => 'published',
        ]);

        Enrollment::query()->create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        $this->lesson = Lesson::query()->create([
            'course_id' => $this->course->id,
            'title' => 'درس التعلم',
            'description' => 'وصف',
            'content_html' => '<p>شرح الدرس</p>',
            'status' => 'published',
            'position' => 1,
        ]);

        $video = MediaAsset::query()->create([
            'lesson_id' => $this->lesson->id,
            'type' => 'video',
            'disk' => 'local_private',
            'path' => 'lessons/x/main.mp4',
            'original_name' => 'main.mp4',
            'mime' => 'video/mp4',
            'size' => 2048,
        ]);

        MediaAsset::query()->create([
            'lesson_id' => $this->lesson->id,
            'type' => 'pdf',
            'disk' => 'local_private',
            'path' => 'lessons/x/notes.pdf',
            'original_name' => 'notes.pdf',
            'mime' => 'application/pdf',
            'size' => 500,
        ]);

        $this->lesson->forceFill(['main_media_asset_id' => $video->id])->save();
    }

    public function test_student_lesson_show_exposes_main_video_body_and_files(): void
    {
        $response = $this->actingAs($this->student)->get(route('student.lessons.show', $this->lesson));

        $response->assertOk();
        $response->assertSee('درس التعلم', false);
        $response->assertSee('شرح الدرس', false);
        $response->assertSee('notes.pdf', false);
        $response->assertSee('main.mp4', false);
    }
}
