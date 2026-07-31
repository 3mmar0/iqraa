<?php

namespace App\Http\Controllers\Web\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(): View
    {
        return view('marketing.analytics.index');
    }
}
