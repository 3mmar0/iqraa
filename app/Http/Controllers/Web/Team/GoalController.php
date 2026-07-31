<?php

namespace App\Http\Controllers\Web\Team;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use Illuminate\View\View;

class GoalController extends Controller
{
    public function index(): View
    {
        $goals = Goal::query()->with('owner')->latest()->get();

        return view('team.goals.index', compact('goals'));
    }
}