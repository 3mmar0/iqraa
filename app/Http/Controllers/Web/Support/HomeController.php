<?php

namespace App\Http\Controllers\Web\Support;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('support.home', [
            'title' => 'لوحة الدعم',
        ]);
    }
}