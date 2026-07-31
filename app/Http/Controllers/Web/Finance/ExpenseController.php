<?php

namespace App\Http\Controllers\Web\Finance;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(): View
    {
        $expenses = Expense::query()->with('recorder')->latest()->limit(100)->get();

        return view('finance.expenses', compact('expenses'));
    }
}