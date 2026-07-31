<?php

namespace App\Http\Controllers\Web\Team;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(): View
    {
        $records = AttendanceRecord::query()->with('user')->latest('date')->limit(100)->get();

        return view('team.attendance.index', compact('records'));
    }
}