<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $courses = Enrollment::query()
            ->with(['course.instructor:id,name', 'course.lessons:id,course_id,title,position'])
            ->where('user_id', $request->user()->id)
            ->where('status', 'active')
            ->get()
            ->pluck('course')
            ->filter()
            ->values();

        return response()->json(['data' => $courses]);
    }
}