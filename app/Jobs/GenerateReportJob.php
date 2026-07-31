<?php

namespace App\Jobs;

use App\Models\ReportJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateReportJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ReportJob $reportJob,
    ) {}

    public function handle(): void
    {
        $this->reportJob->update([
            'status' => 'running',
        ]);

        $this->reportJob->update([
            'status' => 'done',
            'finished_at' => now(),
        ]);
    }
}