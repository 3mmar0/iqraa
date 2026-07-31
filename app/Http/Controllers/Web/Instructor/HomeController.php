<?php

namespace App\Http\Controllers\Web\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('instructor.home', [
            'title' => 'لوحة المحاضر',
        ]);
    }
}