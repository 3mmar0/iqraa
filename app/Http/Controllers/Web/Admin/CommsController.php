<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class CommsController extends Controller
{
    public function index(): View
    {
        return view('admin.comms.index');
    }
}