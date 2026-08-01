<?php

namespace Modules\Admin\Services;

use App\Models\Category;
use App\Models\Course;
use App\Models\FinanceTransaction;
use App\Models\Lesson;
use App\Models\LessonComment;
use App\Models\MediaAsset;
use App\Models\Order;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Subscription;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminDashboardStatsService
{
    public const CACHE_KEY = 'admin_dashboard_stats';

    /** @return array<string, int|float> */
    public function cards(): array
    {
        return Cache::remember(self::CACHE_KEY.':cards', 60, function () {
            $revenueQuery = FinanceTransaction::query()
                ->where(function ($q) {
                    $q->where('type', 'income')
                        ->orWhere('status', 'completed')
                        ->orWhere('status', 'paid');
                });

            $today = Carbon::today();
            $monthStart = Carbon::now()->startOfMonth();

            return [
                'students' => User::query()
                    ->whereHas('roles', fn ($q) => $q->where('slug', 'student'))
                    ->count(),
                'courses' => Course::query()->count(),
                'categories' => Category::query()->count(),
                'lessons' => Lesson::query()->count(),
                'videos' => MediaAsset::query()->where('type', 'video')->count(),
                'orders' => Order::query()->count(),
                'subscriptions_active' => Subscription::query()->where('status', 'active')->count(),
                'revenue_total' => (float) (clone $revenueQuery)->sum('amount'),
                'revenue_today' => (float) (clone $revenueQuery)->whereDate('created_at', $today)->sum('amount'),
                'revenue_month' => (float) (clone $revenueQuery)->where('created_at', '>=', $monthStart)->sum('amount'),
                'quizzes' => Quiz::query()->count(),
                'dau' => User::query()->whereDate('last_login_at', $today)->count(),
                'tickets_open' => Ticket::query()->where('status', 'open')->count(),
                'notifications_unread' => $this->unreadNotificationsCount(),
            ];
        });
    }

    /** @return array<string, array{labels: list<string>, counts: list<int>}> */
    public function charts(): array
    {
        return Cache::remember(self::CACHE_KEY.':charts', 60, function () {
            $days = collect(range(6, 0))->map(fn (int $offset) => Carbon::today()->subDays($offset));

            return [
                'revenue' => $this->dailySeries($days, fn (Carbon $day) => (float) FinanceTransaction::query()
                    ->where(function ($q) {
                        $q->where('type', 'income')
                            ->orWhere('status', 'completed')
                            ->orWhere('status', 'paid');
                    })
                    ->whereDate('created_at', $day)
                    ->sum('amount')),
                'student_growth' => $this->dailySeries($days, fn (Carbon $day) => User::query()
                    ->whereHas('roles', fn ($q) => $q->where('slug', 'student'))
                    ->whereDate('created_at', $day)
                    ->count()),
                'orders' => $this->dailySeries($days, fn (Carbon $day) => Order::query()
                    ->whereDate('created_at', $day)
                    ->count()),
                'dau' => $this->dailySeries($days, fn (Carbon $day) => User::query()
                    ->whereDate('last_login_at', $day)
                    ->count()),
                'quiz_attempts' => $this->dailySeries($days, fn (Carbon $day) => QuizAttempt::query()
                    ->whereDate('created_at', $day)
                    ->count()),
                'subscriptions' => $this->dailySeries($days, fn (Carbon $day) => Subscription::query()
                    ->whereDate('created_at', $day)
                    ->count()),
            ];
        });
    }

    /** @return array<string, Collection<int, mixed>> */
    public function recent(): array
    {
        return Cache::remember(self::CACHE_KEY.':recent', 60, function () {
            return [
                'students' => User::query()
                    ->whereHas('roles', fn ($q) => $q->where('slug', 'student'))
                    ->latest()
                    ->limit(8)
                    ->get(['id', 'name', 'email', 'created_at']),
                'payments' => FinanceTransaction::query()
                    ->with('user:id,name,email')
                    ->latest()
                    ->limit(8)
                    ->get(),
                'quiz_attempts' => QuizAttempt::query()
                    ->with(['quiz:id,title'])
                    ->latest()
                    ->limit(8)
                    ->get(),
                'comments' => LessonComment::query()
                    ->with(['user:id,name', 'lesson:id,title'])
                    ->latest()
                    ->limit(8)
                    ->get(),
                'tickets' => Ticket::query()
                    ->with(['student:id,name', 'assignee:id,name'])
                    ->latest()
                    ->limit(8)
                    ->get(),
            ];
        });
    }

    /** @param  Collection<int, Carbon>  $days */
    private function dailySeries(Collection $days, callable $counter): array
    {
        return [
            'labels' => $days->map(fn (Carbon $day) => $day->format('m/d'))->values()->all(),
            'counts' => $days->map(fn (Carbon $day) => (int) $counter($day))->values()->all(),
        ];
    }

    private function unreadNotificationsCount(): int
    {
        if (! DB::getSchemaBuilder()->hasTable('notifications')) {
            return 0;
        }

        return (int) DB::table('notifications')->whereNull('read_at')->count();
    }
}
