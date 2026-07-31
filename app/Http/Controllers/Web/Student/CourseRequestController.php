<?php

namespace App\Http\Controllers\Web\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreCourseRequestRequest;
use App\Models\Course;
use App\Models\CourseAccessRequest;
use App\Services\EnrollmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseRequestController extends Controller
{
    public function __construct(private EnrollmentService $enrollments)
    {
    }

    public function index(Request $request): View
    {
        $requests = CourseAccessRequest::query()
            ->with('course')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        $catalog = Course::query()->where('status', 'published')->orderBy('title')->get();

        return view('student.course-requests.index', compact('requests', 'catalog'));
    }

    public function store(StoreCourseRequestRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $course = Course::query()->findOrFail($validated['course_id']);
        $this->enrollments->requestAccess($request->user(), $course, $validated['message'] ?? null);

        return back()->with('status', 'تم إرسال طلب الالتحاق.');
    }
}
