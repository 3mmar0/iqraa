<?php

namespace App\Http\Controllers\Web\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = $request->user()->notifications()->latest()->limit(50)->get();

        return view('student.notifications', compact('notifications'));
    }
}
