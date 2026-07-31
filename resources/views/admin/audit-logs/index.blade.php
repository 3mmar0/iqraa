@extends('layouts.app')
@section('title', 'سجل التدقيق')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">سجل التدقيق</h1>
    @if ($logs->isEmpty())
        <x-empty-state message="لا سجلات." />
    @else
        <ul class="space-y-2">
            @foreach ($logs as $log)
                <li class="rounded-xl border border-slate-200 bg-white p-4 text-sm">
                    {{ $log->created_at }} · {{ $log->actor?->name }} · {{ $log->action }} · {{ $log->target_type }}#{{ $log->target_id }}
                </li>
            @endforeach
        </ul>
        <div class="mt-4">{{ $logs->links() }}</div>
    @endif
@endsection