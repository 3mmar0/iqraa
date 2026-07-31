@extends('layouts.app')
@section('title', 'الإحالات')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">الإحالات</h1>
    @if ($referrals->isEmpty())
        <x-empty-state message="لا إحالات." />
    @else
        <ul class="space-y-2">
            @foreach ($referrals as $item)
                <li class="rounded-xl border border-slate-200 bg-white p-4 text-sm">{{ $item->code }} · {{ $item->referrer?->name }} → {{ $item->referred?->name }} · {{ $item->status }}</li>
            @endforeach
        </ul>
    @endif
@endsection