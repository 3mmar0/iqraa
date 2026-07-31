<?php

namespace App\Http\Controllers\Web\Finance;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateReportJob;
use App\Models\ReportJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'max:100'],
            'format' => ['nullable', 'string', 'in:csv,xlsx,pdf'],
        ]);

        $job = ReportJob::query()->create([
            'requester_id' => $request->user()->id,
            'type' => $validated['type'],
            'format' => $validated['format'] ?? 'csv',
            'status' => 'queued',
        ]);

        GenerateReportJob::dispatch($job);

        return back()->with('status', 'تمت إضافة التقرير إلى قائمة الانتظار.');
    }
}