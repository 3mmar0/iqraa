@extends('layouts.marketing')
@section('title', 'العملاء المحتملون')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-[var(--color-ink)]">العملاء المحتملون</h1>
    @if ($leads->isEmpty())
        <x-empty-state message="لا عملاء محتملين." />
    @else
        <ul class="space-y-2">
            @foreach ($leads as $lead)
                <li class="rounded-xl border border-slate-200 bg-white p-4 text-sm">{{ $lead->name }} · {{ $lead->email }} · {{ $lead->stage }}</li>
            @endforeach
        </ul>
    @endif
@endsection