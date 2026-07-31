<?php

namespace App\Http\Controllers\Web\Staff;

use App\Http\Controllers\Controller;
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

    public function index(): View
    {
        $requests = CourseAccessRequest::query()
            ->with(['user', 'course'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('staff.course-requests.index', compact('requests'));
    }

    public function approve(Request $request, CourseAccessRequest $courseAccessRequest): RedirectResponse
    {
        $this->enrollments->approve($courseAccessRequest, $request->user(), $request->input('review_note'));

        return back()->with('status', 'تمت الموافقة وإنشاء التسجيل.');
    }

    public function reject(Request $request, CourseAccessRequest $courseAccessRequest): RedirectResponse
    {
        $this->enrollments->reject($courseAccessRequest, $request->user(), $request->input('review_note'));

        return back()->with('status', 'تم رفض الطلب.');
    }
}
