<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceTransaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $query = FinanceTransaction::query()->with('user')->latest();

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        $payments = $query->paginate(20)->withQueryString();

        $totals = [
            'count' => FinanceTransaction::query()->count(),
            'paid' => (float) FinanceTransaction::query()->where('status', 'paid')->sum('amount'),
            'pending' => FinanceTransaction::query()->where('status', 'pending')->count(),
        ];

        return view('admin.payments.index', compact('payments', 'totals'));
    }
}
