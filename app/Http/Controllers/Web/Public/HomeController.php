<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('public.home');
    }
}