<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ExportAdminDashboardJob;
use App\Models\ReportJob;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardExportController extends Controller
{
    public function __construct(private readonly AuditLogger $audit)
    {
    }

    public function exportPdf(Request $request): RedirectResponse
    {
        return $this->enqueueExport($request, 'pdf');
    }

    public function exportExcel(Request $request): RedirectResponse
    {
        return $this->enqueueExport($request, 'csv');
    }

    private function enqueueExport(Request $request, string $format): RedirectResponse
    {
        $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ], [
            'date' => 'تاريخ غير صالح.',
            'after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد البداية.',
        ]);

        $job = ReportJob::query()->create([
            'requester_id' => $request->user()->id,
            'type' => 'admin_dashboard',
            'format' => $format,
            'status' => 'queued',
        ]);

        ExportAdminDashboardJob::dispatch($job);

        $this->audit->log($request->user(), 'dashboard.export', ReportJob::class, $job->id, [
            'format' => $format,
        ]);

        return back()->with('status', 'تمت إضافة التصدير إلى قائمة الانتظار.');
    }
}
