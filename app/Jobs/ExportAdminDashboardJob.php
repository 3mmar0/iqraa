<?php

namespace App\Jobs;

use App\Models\ReportJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Services\AdminDashboardStatsService;

class ExportAdminDashboardJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ReportJob $reportJob,
    ) {}

    public function handle(AdminDashboardStatsService $stats): void
    {
        $this->reportJob->update(['status' => 'running']);

        $cards = $stats->cards();
        $charts = $stats->charts();
        $extension = $this->reportJob->format === 'pdf' ? 'pdf.csv' : 'csv';
        $relativePath = 'exports/dashboard-'.$this->reportJob->id.'.'.$extension;

        $lines = ["metric,value"];
        foreach ($cards as $key => $value) {
            $lines[] = $key.','.$value;
        }

        $lines[] = '';
        $lines[] = 'chart,date,value';
        foreach ($charts as $chartKey => $series) {
            foreach ($series['labels'] as $i => $label) {
                $lines[] = $chartKey.','.$label.','.($series['counts'][$i] ?? 0);
            }
        }

        Storage::disk('local')->put($relativePath, implode("\n", $lines));

        $this->reportJob->update([
            'status' => 'done',
            'file_path' => $relativePath,
            'finished_at' => now(),
        ]);
    }
}
