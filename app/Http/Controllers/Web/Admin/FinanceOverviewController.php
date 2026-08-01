<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceTransaction;
use App\Models\Order;
use App\Models\Refund;
use App\Models\Subscription;
use Illuminate\View\View;

class FinanceOverviewController extends Controller
{
    public function index(): View
    {
        $stats = [
            'orders' => Order::query()->count(),
            'pending_orders' => Order::query()->where('status', 'pending')->count(),
            'transactions' => FinanceTransaction::query()->count(),
            'paid_total' => (float) FinanceTransaction::query()->where('status', 'paid')->sum('amount'),
            'subscriptions' => Subscription::query()->count(),
            'refunds' => Refund::query()->count(),
        ];

        return view('admin.finance.overview', compact('stats'));
    }
}
