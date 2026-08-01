@extends('layouts.team')
@section('title', 'إعلانات الفريق')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-[var(--color-ink)]">إعلانات الفريق</h1>
    @if ($announcements->isEmpty())
        <x-empty-state message="لا إعلانات فريق." />
    @else
        <ul class="space-y-3">
            @foreach ($announcements as $item)
                <li class="rounded-xl border border-slate-200 bg-white p-4">
                    <h2 class="font-semibold">{{ $item->title }}</h2>
                    <p class="mt-1 text-sm">{{ $item->body }}</p>
                </li>
            @endforeach
        </ul>
    @endif
@endsection