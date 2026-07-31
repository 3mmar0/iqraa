<?php

namespace App\Http\Controllers\Web\Finance;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class RevenueController extends Controller
{
    public function index(): View
    {
        return view('finance.revenue', ['title' => 'الإيرادات']);
    }
}
