<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $tmp = storage_path('app/tmp');
    if (File::isDirectory($tmp)) {
        File::deleteDirectory($tmp);
    }
})->daily()->name('cleanup-tmp-storage');

Schedule::call(function () {
    \Illuminate\Support\Facades\Cache::forget('admin_dashboard_stats:cards');
    \Illuminate\Support\Facades\Cache::forget('admin_dashboard_stats:charts');
    \Illuminate\Support\Facades\Cache::forget('admin_dashboard_stats:recent');
    if (class_exists(\Modules\Admin\Services\AdminDashboardStatsService::class)) {
        app(\Modules\Admin\Services\AdminDashboardStatsService::class)->cards();
    }
})->everyFiveMinutes()->name('refresh-admin-dashboard-stats');

// Reminders (enable when ready):
// Schedule::command('queue:prune-failed')->daily();
// Schedule::command('sanctum:prune-expired --hours=24')->daily();
// Schedule::command('model:prune')->daily();