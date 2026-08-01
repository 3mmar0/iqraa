<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SystemLogController extends Controller
{
    public const CHANNELS = [
        'activity',
        'authentication',
        'payment',
        'errors',
        'queue',
        'mail',
        'audit',
    ];

    /** @var array<string, string> */
    private const CHANNEL_LABELS = [
        'activity' => 'النشاط',
        'authentication' => 'المصادقة',
        'payment' => 'المدفوعات',
        'errors' => 'الأخطاء',
        'queue' => 'الطابور',
        'mail' => 'البريد',
        'audit' => 'التدقيق',
    ];

    public function index(Request $request): View
    {
        $channel = (string) $request->query('channel', 'activity');

        if (! in_array($channel, self::CHANNELS, true)) {
            $channel = 'activity';
        }

        $search = trim((string) $request->query('q', ''));
        $channels = $this->buildChannels($channel);

        if ($channel === 'audit') {
            $query = AuditLog::query()->with('actor')->latest('created_at');

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('action', 'like', "%{$search}%")
                        ->orWhere('ip', 'like', "%{$search}%");
                });
            }

            $logs = $query->paginate(50)->withQueryString();
            $logType = 'audit';
        } elseif ($channel === 'queue') {
            $query = DB::table('failed_jobs')->orderByDesc('failed_at');

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('queue', 'like', "%{$search}%")
                        ->orWhere('payload', 'like', "%{$search}%")
                        ->orWhere('exception', 'like', "%{$search}%");
                });
            }

            $page = max(1, (int) $request->query('page', 1));
            $perPage = 50;
            $total = (clone $query)->count();
            $items = $query->forPage($page, $perPage)->get();

            $logs = new LengthAwarePaginator(
                $items,
                $total,
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()],
            );
            $logType = 'queue';
        } else {
            $query = ActivityLog::query()->with('user')->where('channel', $channel)->latest();

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('event', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%")
                        ->orWhere('ip', 'like', "%{$search}%");
                });
            }

            $logs = $query->paginate(50)->withQueryString();
            $logType = 'activity';
        }

        return view('admin.system-logs.index', compact('logs', 'channel', 'channels', 'logType', 'search'));
    }

    /** @return list<array{label: string, href: string, active: bool}> */
    private function buildChannels(string $active): array
    {
        return array_map(fn (string $key) => [
            'label' => self::CHANNEL_LABELS[$key],
            'href' => route('admin.system-logs.index', ['channel' => $key]),
            'active' => $key === $active,
        ], self::CHANNELS);
    }
}
