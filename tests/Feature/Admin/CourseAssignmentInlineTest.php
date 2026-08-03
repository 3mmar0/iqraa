<?php

namespace Tests\Feature\Admin;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseAssignmentInlineTest extends TestCase
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
            'title' => 'مقرر الواجبات',
            'status' => 'published',
        ]);
    }

    public function test_create_assignment_returns_to_course_tab(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.assignments.store'), [
            'course_id' => $this->course->id,
            'title' => 'واجب 1',
            'description' => 'وصف',
            'due_at' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'status' => 'draft',
            'return_to' => 'course',
            'return_course_id' => $this->course->id,
            'return_tab' => 'assignments',
        ]);

        $response->assertRedirect(route('admin.courses.show', [
            'course' => $this->course->id,
            'tab' => 'assignments',
        ]));

        $this->assertDatabaseHas('assignments', [
            'course_id' => $this->course->id,
            'title' => 'واجب 1',
        ]);
    }

    public function test_delete_with_graded_submission_archives_instead(): void
    {
        $assignment = Assignment::query()->create([
            'course_id' => $this->course->id,
            'title' => 'واجب مقيّم',
            'due_at' => now()->addDay(),
            'status' => 'published',
        ]);

        $student = User::factory()->create();
        AssignmentSubmission::query()->create([
            'assignment_id' => $assignment->id,
            'user_id' => $student->id,
            'body' => 'إجابة',
            'status' => 'graded',
            'score' => 90,
            'submitted_at' => now(),
        ]);

        $this->actingAs($this->admin)->delete(route('admin.assignments.destroy', $assignment), [
            'return_to' => 'course',
            'return_course_id' => $this->course->id,
            'return_tab' => 'assignments',
        ])->assertRedirect(route('admin.courses.show', [
            'course' => $this->course->id,
            'tab' => 'assignments',
        ]));

        $this->assertDatabaseHas('assignments', [
            'id' => $assignment->id,
            'status' => 'archived',
        ]);
        $this->assertNull($assignment->fresh()->deleted_at);
    }

    public function test_assignments_tab_has_inline_detail_cta(): void
    {
        Assignment::query()->create([
            'course_id' => $this->course->id,
            'title' => 'واجب ظاهر',
            'due_at' => now()->addDay(),
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.courses.show', [
            'course' => $this->course->id,
            'tab' => 'assignments',
        ]));

        $response->assertOk();
        $response->assertSee('التفاصيل والتسليمات', false);
        $response->assertSee('صفحة مستقلة', false);
    }
}
