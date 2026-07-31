<?php

namespace App\Http\Controllers\Web\Instructor;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use App\Models\Course;
use App\Models\LiveSession;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(Request $request): View
    {
        $courseIds = Course::query()
            ->where('instructor_user_id', $request->user()->id)
            ->pluck('id');

        $events = CalendarEvent::query()
            ->whereIn('course_id', $courseIds)
            ->orderBy('starts_at')
            ->get();

        $sessions = LiveSession::query()
            ->whereIn('course_id', $courseIds)
            ->orderBy('starts_at')
            ->get();

        return view('instructor.calendar.index', compact('events', 'sessions'));
    }
}