<?php

namespace App\Http\Middleware;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOwnsCourseContent
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        $course = $this->resolveCourse($request);

        if ($course) {
            abort_unless((int) $course->instructor_user_id === (int) $user->id, 403);
        }

        return $next($request);
    }

    private function resolveCourse(Request $request): ?Course
    {
        $course = $request->route('course');
        if ($course instanceof Course) {
            return $course;
        }

        $lesson = $request->route('lesson');
        if ($lesson instanceof Lesson) {
            return $lesson->course()->first();
        }

        $quiz = $request->route('quiz');
        if ($quiz instanceof Quiz) {
            return $quiz->course()->first();
        }

        $assignment = $request->route('assignment');
        if ($assignment instanceof Assignment) {
            return $assignment->course()->first();
        }

        if ($request->filled('course_id')) {
            return Course::query()->find($request->integer('course_id'));
        }

        if ($request->filled('return_course_id')) {
            return Course::query()->find($request->integer('return_course_id'));
        }

        return null;
    }
}
