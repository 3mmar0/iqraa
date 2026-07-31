@extends('layouts.app')
@section('title', 'الاستردادات')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">الاستردادات</h1>
    @if (\Illuminate\Support\Facades\Route::has('finance.refunds.store'))
        <form method="POST" action="{{ route('finance.refunds.store') }}" class="mb-8 max-w-lg space-y-3 rounded-xl border border-slate-200 bg-white p-4">
            @csrf
            <select name="transaction_id" required class="w-full rounded border border-slate-300 px-3 py-2">
                <option value="">اختر معاملة</option>
                @foreach ($transactions as $tx)
                    <option value="{{ $tx->id }}">#{{ $tx->id }} — {{ $tx->amount }} {{ $tx->currency }}</option>
                @endforeach
            </select>
            <input type="number" step="0.01" name="amount" required placeholder="المبلغ" class="w-full rounded border border-slate-300 px-3 py-2">
            <input type="text" name="note" placeholder="ملاحظة" class="w-full rounded border border-slate-300 px-3 py-2">
            <button type="submit" class="rounded bg-teal-700 px-4 py-2 text-white">تسجيل استرداد</button>
        </form>
    @endif
    @if ($refunds->isEmpty())
        <x-empty-state message="لا استردادات." />
    @else
        <ul class="space-y-2">
            @foreach ($refunds as $refund)
                <li class="rounded-xl border border-slate-200 bg-white p-4 text-sm">#{{ $refund->id }} · {{ $refund->amount }} · {{ $refund->status }}</li>
            @endforeach
        </ul>
    @endif
@endsection