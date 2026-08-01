@extends('layouts.team')
@section('title', 'الاجتماعات')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">الاجتماعات</h1>
    @if ($meetings->isEmpty())
        <x-empty-state message="لا اجتماعات." />
    @else
        <ul class="space-y-2">
            @foreach ($meetings as $meeting)
                <li class="rounded-xl border border-slate-200 bg-white p-4 text-sm">{{ $meeting->title }} · {{ $meeting->starts_at }} · {{ $meeting->location }}</li>
            @endforeach
        </ul>
    @endif
@endsection