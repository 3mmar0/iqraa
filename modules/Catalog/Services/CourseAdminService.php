<?php

namespace Modules\Catalog\Services;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\DB;

class CourseAdminService
{
    public function __construct(private readonly AuditLogger $audit)
    {
    }

    public function archive(Course $course, ?User $actor = null): Course
    {
        $course->update(['status' => 'archived']);
        $this->audit->log($actor, 'course.archived', Course::class, $course->id);

        return $course;
    }

    public function publish(Course $course, ?User $actor = null): Course
    {
        $course->update(['status' => 'published']);
        $this->audit->log($actor, 'course.published', Course::class, $course->id);

        return $course;
    }

    public function hide(Course $course, ?User $actor = null): Course
    {
        $course->update(['status' => 'hidden']);
        $this->audit->log($actor, 'course.hidden', Course::class, $course->id);

        return $course;
    }

    public function duplicate(Course $course, ?User $actor = null): Course
    {
        return DB::transaction(function () use ($course, $actor) {
            $copy = $course->replicate(['status']);
            $copy->title = $course->title.' (نسخة)';
            $copy->status = 'draft';
            $copy->save();

            foreach ($course->lessons as $lesson) {
                $lessonCopy = $lesson->replicate();
                $lessonCopy->course_id = $copy->id;
                $lessonCopy->status = 'draft';
                $lessonCopy->published_at = null;
                $lessonCopy->save();
            }

            $this->audit->log($actor, 'course.duplicated', Course::class, $copy->id, [
                'source_id' => $course->id,
            ]);

            return $copy->fresh(['lessons']);
        });
    }

    public function assignTeacher(Course $course, int $instructorUserId, ?User $actor = null): Course
    {
        $course->update(['instructor_user_id' => $instructorUserId]);
        $this->audit->log($actor, 'course.teacher_assigned', Course::class, $course->id, [
            'instructor_user_id' => $instructorUserId,
        ]);

        return $course->fresh('instructor');
    }

    public function assignSemester(Course $course, ?int $academicYearId, ?int $semesterId, ?User $actor = null): Course
    {
        $course->update([
            'academic_year_id' => $academicYearId,
            'semester_id' => $semesterId,
        ]);

        $this->audit->log($actor, 'course.semester_assigned', Course::class, $course->id, [
            'academic_year_id' => $academicYearId,
            'semester_id' => $semesterId,
        ]);

        return $course->fresh(['academicYear', 'semester']);
    }
}
