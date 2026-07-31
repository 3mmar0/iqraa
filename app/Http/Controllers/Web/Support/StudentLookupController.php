<?php

namespace App\Http\Controllers\Web\Support;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentLookupController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));

        $students = User::query()
            ->whereHas('roles', fn ($query) => $query->where('slug', 'student'))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%");
                });
            })
            ->orderBy('name')
            ->limit(50)
            ->get();

        return view('support.students.index', compact('students', 'q'));
    }
}