<?php

namespace App\Http\Controllers\Web\Finance;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ProfitController extends Controller
{
    public function index(): View
    {
        return view('finance.profit', ['title' => 'الأرباح']);
    }
}
