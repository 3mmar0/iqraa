<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseAccessRequest;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EnrollmentService
{
    public function requestAccess(User $user, Course $course, ?string $message = null): CourseAccessRequest
    {
        if ($course->status !== 'published') {
            throw ValidationException::withMessages(['course_id' => 'المقرر غير متاح للطلب.']);
        }

        $existingEnrollment = Enrollment::query()
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->exists();

        if ($existingEnrollment) {
            throw ValidationException::withMessages(['course_id' => 'أنت مسجّل بالفعل في هذا المقرر.']);
        }

        $pending = CourseAccessRequest::query()
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'pending')
            ->first();

        if ($pending) {
            throw ValidationException::withMessages(['course_id' => 'لديك طلب معلّق بالفعل لهذا المقرر.']);
        }

        return CourseAccessRequest::query()->create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'pending',
            'message' => $message,
        ]);
    }

    public function approve(CourseAccessRequest $request, User $reviewer, ?string $note = null): Enrollment
    {
        if ($request->status !== 'pending') {
            throw ValidationException::withMessages(['status' => 'لا يمكن اعتماد طلب غير معلّق.']);
        }

        return DB::transaction(function () use ($request, $reviewer, $note) {
            $request->update([
                'status' => 'approved',
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now(),
                'review_note' => $note,
            ]);

            return Enrollment::query()->updateOrCreate(
                [
                    'user_id' => $request->user_id,
                    'course_id' => $request->course_id,
                ],
                [
                    'status' => 'active',
                    'source' => 'access_request',
                    'access_request_id' => $request->id,
                    'enrolled_at' => now(),
                    'revoked_at' => null,
                ]
            );
        });
    }

    public function reject(CourseAccessRequest $request, User $reviewer, ?string $note = null): CourseAccessRequest
    {
        if ($request->status !== 'pending') {
            throw ValidationException::withMessages(['status' => 'لا يمكن رفض طلب غير معلّق.']);
        }

        $request->update([
            'status' => 'rejected',
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'review_note' => $note,
        ]);

        return $request->fresh();
    }

    public function userHasActiveEnrollment(User $user, int $courseId): bool
    {
        return Enrollment::query()
            ->where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->where('status', 'active')
            ->exists();
    }
}
