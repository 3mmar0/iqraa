@extends('layouts.instructor')

@section('title', 'التقويم')

@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">التقويم</h1>
    <h2 class="mb-2 font-semibold">الأحداث</h2>
    @if ($events->isEmpty())
        <x-empty-state message="لا أحداث في التقويم." class="mb-6" />
    @else
        <ul class="mb-6 space-y-2">
            @foreach ($events as $event)
                <li class="rounded border border-slate-200 bg-white px-4 py-3 text-sm">{{ $event->title }} — {{ $event->starts_at }}</li>
            @endforeach
        </ul>
    @endif
    <h2 class="mb-2 font-semibold">الجلسات المباشرة</h2>
    @if ($sessions->isEmpty())
        <x-empty-state message="لا جلسات قادمة." />
    @else
        <ul class="space-y-2">
            @foreach ($sessions as $session)
                <li class="rounded border border-slate-200 bg-white px-4 py-3 text-sm">{{ $session->title }} — {{ $session->starts_at }}</li>
            @endforeach
        </ul>
    @endif
@endsection