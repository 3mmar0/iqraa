<?php

namespace App\Http\Controllers\Web\Team;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('team.home', [
            'title' => 'لوحة الفريق',
        ]);
    }
}