<?php

namespace App\Http\Controllers\Web\Finance;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ForecastController extends Controller
{
    public function index(): View
    {
        return view('finance.forecast', ['title' => 'التوقعات']);
    }
}
