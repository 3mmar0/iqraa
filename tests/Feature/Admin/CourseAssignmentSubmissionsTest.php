<?php

namespace Tests\Feature\Admin;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseAssignmentSubmissionsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Course $course;

    private Assignment $assignment;

    private AssignmentSubmission $submission;

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
            'title' => 'مقرر التسليمات',
            'status' => 'published',
        ]);

        $this->assignment = Assignment::query()->create([
            'course_id' => $this->course->id,
            'title' => 'واجب للتصحيح',
            'due_at' => now()->addDay(),
            'status' => 'published',
        ]);

        $student = User::factory()->create(['name' => 'طالب التسليم']);
        $this->submission = AssignmentSubmission::query()->create([
            'assignment_id' => $this->assignment->id,
            'user_id' => $student->id,
            'body' => 'حل الطالب',
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);
    }

    public function test_grade_submission_returns_to_course_assignments_tab(): void
    {
        $response = $this->actingAs($this->admin)->post(
            route('admin.assignments.submissions.grade', [$this->assignment, $this->submission]),
            [
                'score' => 85.5,
                'return_to' => 'course',
                'return_course_id' => $this->course->id,
                'return_tab' => 'assignments',
            ]
        );

        $response->assertRedirect(route('admin.courses.show', [
            'course' => $this->course->id,
            'tab' => 'assignments',
        ]));

        $this->submission->refresh();
        $this->assertSame('graded', $this->submission->status);
        $this->assertEquals(85.5, (float) $this->submission->score);
    }

    public function test_request_resubmit_clears_score(): void
    {
        $this->submission->update(['status' => 'graded', 'score' => 70]);

        $this->actingAs($this->admin)->post(
            route('admin.assignments.submissions.resubmit', [$this->assignment, $this->submission]),
            [
                'return_to' => 'course',
                'return_course_id' => $this->course->id,
                'return_tab' => 'assignments',
            ]
        )->assertRedirect(route('admin.courses.show', [
            'course' => $this->course->id,
            'tab' => 'assignments',
        ]));

        $this->submission->refresh();
        $this->assertSame('resubmit_requested', $this->submission->status);
        $this->assertNull($this->submission->score);
    }

    public function test_grade_validation_rejects_out_of_range_score(): void
    {
        $this->actingAs($this->admin)->from(route('admin.courses.show', [
            'course' => $this->course->id,
            'tab' => 'assignments',
        ]))->post(
            route('admin.assignments.submissions.grade', [$this->assignment, $this->submission]),
            ['score' => 150]
        )->assertSessionHasErrors('score');
    }
}
