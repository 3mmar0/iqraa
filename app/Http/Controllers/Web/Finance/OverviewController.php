<?php

namespace App\Http\Controllers\Web\Finance;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\FinanceTransaction;
use App\Models\Refund;
use App\Models\Subscription;
use Illuminate\View\View;

class OverviewController extends Controller
{
    public function index(): View
    {
        return view('finance.overview', [
            'title' => 'نظرة عامة',
            'transactionsCount' => FinanceTransaction::query()->count(),
            'refundsCount' => Refund::query()->count(),
            'expensesCount' => Expense::query()->count(),
            'subscriptionsCount' => Subscription::query()->where('status', 'active')->count(),
        ]);
    }
}