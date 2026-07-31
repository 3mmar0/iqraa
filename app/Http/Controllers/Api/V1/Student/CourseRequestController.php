<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseAccessRequest;
use App\Services\EnrollmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseRequestController extends Controller
{
    public function __construct(private EnrollmentService $enrollments)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $requests = CourseAccessRequest::query()
            ->with('course:id,title,status')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json(['data' => $requests]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $course = Course::query()->findOrFail($validated['course_id']);
        $accessRequest = $this->enrollments->requestAccess(
            $request->user(),
            $course,
            $validated['message'] ?? null,
        );

        return response()->json(['data' => $accessRequest->load('course:id,title,status')], 201);
    }
}