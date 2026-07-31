@extends('layouts.app')
@section('title', 'الحضور')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">الحضور</h1>
    @if ($records->isEmpty())
        <x-empty-state message="لا سجلات حضور." />
    @else
        <ul class="space-y-2">
            @foreach ($records as $record)
                <li class="rounded-xl border border-slate-200 bg-white p-4 text-sm">{{ $record->user?->name }} · {{ $record->date?->format('Y-m-d') }} · {{ $record->status }}</li>
            @endforeach
        </ul>
    @endif
@endsection