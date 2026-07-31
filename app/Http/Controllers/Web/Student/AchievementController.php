<?php

namespace App\Http\Controllers\Web\Student;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AchievementController extends Controller
{
    public function index(Request $request): View
    {
        $achievements = $request->user()->achievements()->orderByPivot('created_at', 'desc')->get();
        $available = Achievement::query()->orderBy('title')->get();

        return view('student.achievements', compact('achievements', 'available'));
    }
}
