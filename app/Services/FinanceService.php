<?php

namespace App\Services;

use App\Models\FinanceTransaction;
use App\Models\Refund;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FinanceService
{
    public function __construct(
        private readonly AuditLogger $auditLogger,
    ) {}

    public function createRefund(
        FinanceTransaction $transaction,
        float $amount,
        User $approver,
        ?string $note = null,
    ): Refund {
        if ($amount <= 0 || $amount > (float) $transaction->amount) {
            throw ValidationException::withMessages(['amount' => 'Invalid refund amount.']);
        }

        return DB::transaction(function () use ($transaction, $amount, $approver, $note) {
            $refund = Refund::query()->create([
                'transaction_id' => $transaction->id,
                'amount' => $amount,
                'status' => 'approved',
                'approved_by' => $approver->id,
                'note' => $note,
            ]);

            $this->auditLogger->log(
                $approver,
                'finance.refund_created',
                Refund::class,
                $refund->id,
                ['transaction_id' => $transaction->id, 'amount' => $amount],
            );

            return $refund;
        });
    }
}