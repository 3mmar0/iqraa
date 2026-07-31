@extends('layouts.app')
@section('title', 'الرواتب')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">الرواتب</h1>
    @if ($records->isEmpty())
        <x-empty-state message="لا سجلات رواتب." />
    @else
        <ul class="space-y-2">
            @foreach ($records as $record)
                <li class="rounded-xl border border-slate-200 bg-white p-4 text-sm">{{ $record->user?->name }} · {{ $record->period }} · {{ $record->amount }} · {{ $record->status }}</li>
            @endforeach
        </ul>
    @endif
@endsection