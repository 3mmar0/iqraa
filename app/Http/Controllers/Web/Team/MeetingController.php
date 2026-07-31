<?php

namespace App\Http\Controllers\Web\Team;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\View\View;

class MeetingController extends Controller
{
    public function index(): View
    {
        $meetings = Meeting::query()->with('creator')->orderBy('starts_at')->get();

        return view('team.meetings.index', compact('meetings'));
    }
}