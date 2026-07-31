<?php

namespace App\Http\Controllers\Web\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function index(): View
    {
        return view('instructor.assignments.index');
    }
}