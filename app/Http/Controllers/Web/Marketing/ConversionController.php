<?php

namespace App\Http\Controllers\Web\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ConversionController extends Controller
{
    public function index(): View
    {
        return view('marketing.conversions.index');
    }
}
