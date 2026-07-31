<?php

namespace App\Http\Controllers\Web\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('instructor.settings.index');
    }
}