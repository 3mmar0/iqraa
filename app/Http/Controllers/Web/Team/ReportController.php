<?php

namespace App\Http\Controllers\Web\Team;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('team.reports.index');
    }
}