<?php

namespace Modules\Finance\Services;

use App\Models\FinanceTransaction;
use App\Models\Order;
use App\Models\Refund;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\FinanceService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderAdminService
{
    public function __construct(
        private readonly AuditLogger $audit,
        private readonly FinanceService $finance,
    ) {
    }

    public function generateNumber(): string
    {
        do {
            $number = 'ORD-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (Order::query()->where('number', $number)->exists());

        return $number;
    }

    public function approve(Order $order, User $approver, ?string $note = null): Order
    {
        $order->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $approver->id,
            'note' => $note ?? $order->note,
        ]);

        $this->audit->log($approver, 'order.approved', Order::class, $order->id);

        return $order->fresh('approver');
    }

    public function reject(Order $order, User $actor, ?string $note = null): Order
    {
        $order->update([
            'status' => 'rejected',
            'note' => $note ?? $order->note,
        ]);

        $this->audit->log($actor, 'order.rejected', Order::class, $order->id);

        return $order;
    }

    public function refund(Order $order, User $approver, ?float $amount = null, ?string $note = null): ?Refund
    {
        $transaction = $this->findLinkedTransaction($order);

        if (! $transaction) {
            $this->audit->log($approver, 'order.refund_skipped', Order::class, $order->id, [
                'reason' => 'no_linked_transaction',
            ]);

            return null;
        }

        $refundAmount = $amount ?? (float) $order->total;

        return DB::transaction(function () use ($order, $transaction, $approver, $refundAmount, $note) {
            $refund = $this->finance->createRefund($transaction, $refundAmount, $approver, $note);

            $order->update(['status' => 'refunded']);
            $this->audit->log($approver, 'order.refunded', Order::class, $order->id, [
                'refund_id' => $refund->id,
                'transaction_id' => $transaction->id,
            ]);

            return $refund;
        });
    }

    private function findLinkedTransaction(Order $order): ?FinanceTransaction
    {
        return FinanceTransaction::query()
            ->where('reference', $order->number)
            ->orWhere('meta->order_id', $order->id)
            ->first();
    }
}
