@extends('layouts.app')
@section('title', 'الاشتراكات')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">الاشتراكات</h1>
    @if ($subscriptions->isEmpty())
        <x-empty-state message="لا اشتراكات." />
    @else
        <ul class="space-y-2">
            @foreach ($subscriptions as $sub)
                <li class="rounded-xl border border-slate-200 bg-white p-4 text-sm">{{ $sub->user?->name }} · {{ $sub->plan_code }} · {{ $sub->status }}</li>
            @endforeach
        </ul>
    @endif
@endsection