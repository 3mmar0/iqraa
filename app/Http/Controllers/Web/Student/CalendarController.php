<?php

namespace App\Http\Controllers\Web\Student;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(Request $request): View
    {
        $courseIds = Enrollment::query()
            ->where('user_id', $request->user()->id)
            ->where('status', 'active')
            ->pluck('course_id');

        $events = CalendarEvent::query()
            ->with('course')
            ->where(function ($q) use ($courseIds) {
                $q->whereIn('course_id', $courseIds)->orWhereNull('course_id');
            })
            ->orderBy('starts_at')
            ->get();

        return view('student.calendar', compact('events'));
    }
}
