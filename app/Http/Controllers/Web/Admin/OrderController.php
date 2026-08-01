<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Finance\Services\OrderAdminService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderAdminService $orders,
        private readonly AuditLogger $audit,
    ) {
    }

    public function index(Request $request): View
    {
        $query = Order::query()
            ->with(['user', 'coupon'])
            ->withCount('items')
            ->latest();

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($from = $request->query('from')) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = $request->query('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'coupon', 'items.course', 'approver']);

        return view('admin.orders.show', compact('order'));
    }

    public function approve(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->orders->approve($order, $request->user(), $validated['note'] ?? null);

        return back()->with('status', 'تمت الموافقة على الطلب.');
    }

    public function reject(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'note' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'note.required' => 'سبب الرفض مطلوب.',
            'note.min' => 'سبب الرفض يجب أن يكون 10 أحرف على الأقل.',
        ]);

        $this->orders->reject($order, $request->user(), $validated['note']);

        return back()->with('status', 'تم رفض الطلب.');
    }

    public function refund(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['nullable', 'numeric', 'min:0.01', 'max:'.(float) $order->total],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $refund = $this->orders->refund(
            $order,
            $request->user(),
            isset($validated['amount']) ? (float) $validated['amount'] : null,
            $validated['note'] ?? null,
        );

        if (! $refund) {
            return back()->with('status', 'لم يُعثر على معاملة مرتبطة؛ تم تسجيل المحاولة فقط.');
        }

        return back()->with('status', 'تم استرداد الطلب.');
    }

    public function invoice(Order $order): View
    {
        $order->load(['user', 'items.course', 'coupon']);

        $this->audit->log(request()->user(), 'order.invoice_viewed', Order::class, $order->id);

        return view('admin.orders.invoice', compact('order'));
    }

    public function export(Request $request): StreamedResponse
    {
        $query = Order::query()->with('user')->latest();

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $filename = 'orders-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['number', 'user', 'email', 'total', 'currency', 'status', 'created_at']);

            $query->chunk(200, function ($orders) use ($handle) {
                foreach ($orders as $order) {
                    fputcsv($handle, [
                        $order->number,
                        $order->user?->name,
                        $order->user?->email,
                        $order->total,
                        $order->currency,
                        $order->status,
                        $order->created_at?->toDateTimeString(),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
