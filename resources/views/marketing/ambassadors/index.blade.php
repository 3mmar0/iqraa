@extends('layouts.app')
@section('title', 'السفراء')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">السفراء</h1>
    @if ($ambassadors->isEmpty())
        <x-empty-state message="لا سفراء." />
    @else
        <ul class="space-y-2">
            @foreach ($ambassadors as $item)
                <li class="rounded-xl border border-slate-200 bg-white p-4 text-sm">{{ $item->user?->name }} · {{ $item->status }} · إحالات: {{ $item->referral_count }}</li>
            @endforeach
        </ul>
    @endif
@endsection