<?php

namespace Modules\Teaching\Services;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\User;

class CourseAuthoringService
{
    public function createCourse(User $instructor, array $data): Course
    {
        return Course::query()->create(array_merge([
            'instructor_user_id' => $instructor->id,
            'status' => 'draft',
        ], $data));
    }

    public function createLesson(Course $course, array $data): Lesson
    {
        $position = $data['position'] ?? ((int) $course->lessons()->max('position') + 1);

        return Lesson::query()->create(array_merge([
            'course_id' => $course->id,
            'position' => $position,
            'status' => 'draft',
        ], $data));
    }

    public function createQuiz(Course $course, array $data): Quiz
    {
        return Quiz::query()->create(array_merge([
            'course_id' => $course->id,
            'status' => 'draft',
            'show_correct_answers' => true,
        ], $data));
    }
}