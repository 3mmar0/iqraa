<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SecurityController extends Controller
{
    public function index(): View
    {
        return view('admin.security.index', [
            'placeholders' => [
                'backups' => 'النسخ الاحتياطي',
                'security' => 'السياسات الأمنية',
            ],
        ]);
    }
}