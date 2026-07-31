<?php

namespace App\Http\Controllers\Web\Team;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        $announcements = Announcement::query()
            ->whereNull('course_id')
            ->latest()
            ->get();

        return view('team.announcements.index', compact('announcements'));
    }
}