<?php

namespace Tests\Feature\Admin;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\MediaAsset;
use App\Models\Quiz;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonContentAuthoringTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Course $course;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::query()->create([
            'slug' => 'super_admin',
            'name_ar' => 'مدير النظام',
            'dashboard_key' => 'admin',
        ]);

        $this->admin = User::factory()->create(['status' => 'active']);
        $this->admin->roles()->attach($role->id);

        $this->course = Course::query()->create([
            'title' => 'مقرر الدروس',
            'status' => 'published',
        ]);
    }

    public function test_admin_can_save_content_html_and_quiz_on_lesson(): void
    {
        $quiz = Quiz::query()->create([
            'course_id' => $this->course->id,
            'title' => 'اختبار الدرس',
            'status' => 'published',
        ]);

        $lesson = Lesson::query()->create([
            'course_id' => $this->course->id,
            'title' => 'درس 1',
            'status' => 'draft',
            'position' => 1,
        ]);

        $video = MediaAsset::query()->create([
            'lesson_id' => $lesson->id,
            'type' => 'video',
            'disk' => 'local_private',
            'path' => 'lessons/1/demo.mp4',
            'original_name' => 'demo.mp4',
            'mime' => 'video/mp4',
            'size' => 1000,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.lessons.update', $lesson), [
            'course_id' => $this->course->id,
            'title' => 'درس 1',
            'description' => 'وصف قصير',
            'content_html' => '<p>شرح <strong>مهم</strong></p><script>alert(1)</script>',
            'status' => 'published',
            'quiz_id' => $quiz->id,
            'main_media_asset_id' => $video->id,
        ]);

        $response->assertRedirect();

        $lesson->refresh();
        $this->assertSame($quiz->id, $lesson->quiz_id);
        $this->assertSame($video->id, $lesson->main_media_asset_id);
        $this->assertStringContainsString('<strong>مهم</strong>', (string) $lesson->content_html);
        $this->assertStringNotContainsString('<script>', (string) $lesson->content_html);
    }
}
