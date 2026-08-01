@extends('layouts.finance')
@section('title', 'المعاملات')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">المعاملات</h1>
    @if ($transactions->isEmpty())
        <x-empty-state message="لا معاملات." />
    @else
        <ul class="space-y-2">
            @foreach ($transactions as $tx)
                <li class="rounded-xl border border-slate-200 bg-white p-4 text-sm">
                    #{{ $tx->id }} · {{ $tx->amount }} {{ $tx->currency }} · {{ $tx->type }} · {{ $tx->status }} · {{ $tx->user?->name }}
                </li>
            @endforeach
        </ul>
    @endif
@endsection