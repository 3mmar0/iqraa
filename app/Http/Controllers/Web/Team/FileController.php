<?php

namespace App\Http\Controllers\Web\Team;

use App\Http\Controllers\Controller;
use App\Models\TeamFile;
use Illuminate\View\View;

class FileController extends Controller
{
    public function index(): View
    {
        $files = TeamFile::query()->with('uploader')->latest()->get();

        return view('team.files.index', compact('files'));
    }
}