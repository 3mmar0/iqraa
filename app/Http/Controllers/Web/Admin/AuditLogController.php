<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(): View
    {
        $logs = AuditLog::query()->with('actor')->latest('created_at')->paginate(50);

        return view('admin.audit-logs.index', compact('logs'));
    }
}