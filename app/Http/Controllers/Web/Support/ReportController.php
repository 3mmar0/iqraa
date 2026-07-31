<?php

namespace App\Http\Controllers\Web\Support;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('support.reports.index');
    }
}