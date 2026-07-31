@extends('layouts.app')
@section('title', 'الأهداف')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">الأهداف</h1>
    @if ($goals->isEmpty())
        <x-empty-state message="لا أهداف." />
    @else
        <ul class="space-y-2">
            @foreach ($goals as $goal)
                <li class="rounded-xl border border-slate-200 bg-white p-4 text-sm">{{ $goal->title }} · {{ $goal->status }} · {{ $goal->owner?->name }}</li>
            @endforeach
        </ul>
    @endif
@endsection