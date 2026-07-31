<?php

namespace App\Http\Controllers\Web\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(): View
    {
        return view('instructor.messages.index');
    }
}