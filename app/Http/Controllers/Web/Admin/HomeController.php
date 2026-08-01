<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $stats = [
            'users' => User::query()->count(),
            'active' => User::query()->where('status', 'active')->count(),
            'roles' => Role::query()->count(),
            'audits' => class_exists(AuditLog::class) ? AuditLog::query()->count() : 0,
        ];

        $recentUsers = User::query()->with('roles')->latest()->limit(5)->get();
        $recentAudits = class_exists(AuditLog::class)
            ? AuditLog::query()->latest()->limit(5)->get()
            : collect();

        $failedJobs = 0;
        try {
            $failedJobs = (int) DB::table('failed_jobs')->count();
        } catch (\Throwable) {
            //
        }

        return view('admin.home', compact('stats', 'recentUsers', 'recentAudits', 'failedJobs'));
    }
}
