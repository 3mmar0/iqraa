@extends('layouts.marketing')
@section('title', 'القسائم')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-[var(--color-ink)]">القسائم</h1>
    @if (\Illuminate\Support\Facades\Route::has('marketing.coupons.store'))
        <form method="POST" action="{{ route('marketing.coupons.store') }}" class="mb-8 max-w-lg space-y-3 rounded-xl border border-slate-200 bg-white p-4">
            @csrf
            <input type="text" name="code" required placeholder="الرمز" class="w-full rounded border border-slate-300 px-3 py-2">
            <select name="discount_type" class="w-full rounded border border-slate-300 px-3 py-2">
                <option value="percent">نسبة</option>
                <option value="fixed">مبلغ ثابت</option>
            </select>
            <input type="number" step="0.01" name="discount_value" required placeholder="القيمة" class="w-full rounded border border-slate-300 px-3 py-2">
            <button type="submit" class="rounded bg-[var(--color-primary)] px-4 py-2 text-white">إضافة</button>
        </form>
    @endif
    @if ($coupons->isEmpty())
        <x-empty-state message="لا قسائم." />
    @else
        <ul class="space-y-2">
            @foreach ($coupons as $coupon)
                <li class="rounded-xl border border-slate-200 bg-white p-4 text-sm">{{ $coupon->code }} · {{ $coupon->discount_type }} {{ $coupon->discount_value }}</li>
            @endforeach
        </ul>
    @endif
@endsection