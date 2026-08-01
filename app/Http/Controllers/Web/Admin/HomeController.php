<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Modules\Admin\Services\AdminDashboardStatsService;

class HomeController extends Controller
{
    public function __construct(
        private readonly AdminDashboardStatsService $stats,
        private readonly AuditLogger $audit,
    ) {}

    public function index(Request $request): View
    {
        if ($request->boolean('refresh')) {
            foreach (['cards', 'charts', 'recent'] as $suffix) {
                Cache::forget(AdminDashboardStatsService::CACHE_KEY.':'.$suffix);
            }

            $this->audit->log($request->user(), 'dashboard.refresh', null, null);
        }

        $from = $request->query('from');
        $to = $request->query('to');

        $cards = $this->stats->cards();
        $charts = $this->stats->charts();
        $recent = $this->stats->recent();
        $quickActions = $this->quickActions();

        $failedJobs = 0;
        try {
            $failedJobs = (int) DB::table('failed_jobs')->count();
        } catch (\Throwable) {
            //
        }

        return view('admin.home', compact(
            'cards',
            'charts',
            'recent',
            'quickActions',
            'failedJobs',
            'from',
            'to',
        ));
    }

    /** @return list<array{label: string, href: string}> */
    private function quickActions(): array
    {
        $actions = [
            ['label' => 'إضافة طالب', 'route' => 'admin.students.create'],
            ['label' => 'تصنيف جديد', 'route' => 'admin.categories.create'],
            ['label' => 'درس جديد', 'route' => 'admin.lessons.create'],
            ['label' => 'اختبار جديد', 'route' => 'admin.quizzes.create'],
            ['label' => 'كوبون خصم', 'route' => 'admin.coupons.create'],
            ['label' => 'إعلان', 'route' => 'admin.announcements.create'],
            ['label' => 'رفع فيديو (درس)', 'route' => 'admin.lessons.create'],
        ];

        return collect($actions)->map(fn (array $item) => [
            'label' => $item['label'],
            'href' => $this->adminRoute($item['route']),
        ])->all();
    }

    private function adminRoute(string $name, array $parameters = []): string
    {
        return Route::has($name) ? route($name, $parameters) : '#';
    }
}
