<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceTransaction;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\FinanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentController extends Controller
{
    public function __construct(
        private readonly AuditLogger $audit,
        private readonly FinanceService $finance,
    ) {
    }

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

        if ($request->boolean('pending_verification')) {
            $query->where('status', 'pending')
                ->whereNull('meta->verified_at');
        }

        if ($from = $request->query('from')) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = $request->query('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $payments = $query->paginate(20)->withQueryString();

        $totals = [
            'count' => FinanceTransaction::query()->count(),
            'paid' => (float) FinanceTransaction::query()->where('status', 'paid')->sum('amount'),
            'pending' => FinanceTransaction::query()->where('status', 'pending')->count(),
        ];

        $students = User::query()
            ->whereHas('roles', fn ($q) => $q->where('slug', 'student'))
            ->orderBy('name')
            ->limit(200)
            ->get(['id', 'name', 'email']);

        return view('admin.payments.index', compact('payments', 'totals', 'students'));
    }

    public function verify(Request $request, FinanceTransaction $payment): RedirectResponse
    {
        $meta = $payment->meta ?? [];
        $meta['verified_at'] = now()->toDateTimeString();
        $meta['verified_by'] = $request->user()->id;

        $payment->update(['meta' => $meta]);

        $this->audit->log($request->user(), 'payment.verified', FinanceTransaction::class, $payment->id);

        return back()->with('status', 'تم التحقق من الدفعة.');
    }

    public function approve(Request $request, FinanceTransaction $payment): RedirectResponse
    {
        $payment->update(['status' => 'paid']);

        $this->audit->log($request->user(), 'payment.approved', FinanceTransaction::class, $payment->id);

        return back()->with('status', 'تمت الموافقة على الدفعة.');
    }

    public function reject(Request $request, FinanceTransaction $payment): RedirectResponse
    {
        $validated = $request->validate([
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $meta = $payment->meta ?? [];
        $meta['reject_note'] = $validated['note'] ?? null;

        $payment->update([
            'status' => 'failed',
            'meta' => $meta,
        ]);

        $this->audit->log($request->user(), 'payment.rejected', FinanceTransaction::class, $payment->id);

        return back()->with('status', 'تم رفض الدفعة.');
    }

    public function refund(Request $request, FinanceTransaction $payment): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:'.(float) $payment->amount],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->finance->createRefund(
            $payment,
            (float) $validated['amount'],
            $request->user(),
            $validated['note'] ?? null,
        );

        $payment->update(['status' => 'refunded']);

        return back()->with('status', 'تم استرداد الدفعة.');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['nullable', 'string', 'size:3'],
            'reference' => ['nullable', 'string', 'max:255'],
            'type' => ['required', Rule::in(['payment', 'subscription'])],
            'note' => ['nullable', 'string', 'max:1000'],
        ], [
            'user_id.required' => 'الطالب مطلوب.',
            'amount.required' => 'المبلغ مطلوب.',
        ]);

        $payment = FinanceTransaction::query()->create([
            'user_id' => $validated['user_id'],
            'amount' => $validated['amount'],
            'currency' => $validated['currency'] ?? 'EGP',
            'type' => $validated['type'],
            'status' => 'paid',
            'reference' => $validated['reference'] ?? 'MAN-'.now()->format('YmdHis'),
            'meta' => [
                'manual' => true,
                'note' => $validated['note'] ?? null,
                'created_by' => $request->user()->id,
            ],
        ]);

        $this->audit->log($request->user(), 'payment.manual_created', FinanceTransaction::class, $payment->id);

        return back()->with('status', 'تم تسجيل الدفعة اليدوية.');
    }

    public function export(Request $request): StreamedResponse
    {
        $query = FinanceTransaction::query()->with('user')->latest();

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $filename = 'payments-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['reference', 'user', 'email', 'amount', 'currency', 'type', 'status', 'created_at']);

            $query->chunk(200, function ($payments) use ($handle) {
                foreach ($payments as $payment) {
                    fputcsv($handle, [
                        $payment->reference ?: '#'.$payment->id,
                        $payment->user?->name,
                        $payment->user?->email,
                        $payment->amount,
                        $payment->currency,
                        $payment->type,
                        $payment->status,
                        $payment->created_at?->toDateTimeString(),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
