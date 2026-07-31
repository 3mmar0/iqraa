<?php

namespace App\Http\Controllers\Web\Finance;

use App\Http\Controllers\Controller;
use App\Models\FinanceTransaction;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(): View
    {
        $transactions = FinanceTransaction::query()->with('user')->latest()->limit(100)->get();

        return view('finance.transactions', compact('transactions'));
    }
}