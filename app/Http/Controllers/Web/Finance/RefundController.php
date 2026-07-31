<?php

namespace App\Http\Controllers\Web\Finance;

use App\Http\Controllers\Controller;
use App\Models\FinanceTransaction;
use App\Models\Refund;
use App\Services\FinanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RefundController extends Controller
{
    public function __construct(private FinanceService $finance)
    {
    }

    public function index(): View
    {
        $refunds = Refund::query()->with(['transaction', 'approver'])->latest()->limit(100)->get();
        $transactions = FinanceTransaction::query()->latest()->limit(50)->get();

        return view('finance.refunds', compact('refunds', 'transactions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'transaction_id' => ['required', 'exists:transactions,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $transaction = FinanceTransaction::query()->findOrFail($validated['transaction_id']);
        $this->finance->createRefund(
            $transaction,
            (float) $validated['amount'],
            $request->user(),
            $validated['note'] ?? null,
        );

        return back()->with('status', 'تم تسجيل الاسترداد.');
    }
}