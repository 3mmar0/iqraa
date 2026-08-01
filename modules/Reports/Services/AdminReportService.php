<?php

namespace Modules\Reports\Services;

use App\Jobs\GenerateReportJob;
use App\Models\ReportJob;
use App\Models\User;
use App\Services\AuditLogger;

class AdminReportService
{
    public function __construct(private readonly AuditLogger $audit)
    {
    }

    public function enqueue(
        User $requester,
        string $type,
        string $format = 'csv',
        ?User $actor = null,
    ): ReportJob {
        $job = ReportJob::query()->create([
            'requester_id' => $requester->id,
            'type' => $type,
            'format' => $format,
            'status' => 'queued',
        ]);

        GenerateReportJob::dispatch($job);

        $this->audit->log($actor ?? $requester, 'report.enqueued', ReportJob::class, $job->id, [
            'type' => $type,
            'format' => $format,
        ]);

        return $job;
    }
}
