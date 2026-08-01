@extends('layouts.instructor')

@section('title', 'الطلاب')

@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">قائمة الطلاب</h1>
    @if ($enrollments->isEmpty())
        <x-empty-state message="لا يوجد طلاب مسجّلون في مقرراتك." />
    @else
        <ul class="space-y-3">
            @foreach ($enrollments as $enrollment)
                <li class="rounded-xl border border-slate-200 bg-white p-4">
                    <p class="font-medium">{{ $enrollment->user?->name }}</p>
                    <p class="text-sm text-slate-600">{{ $enrollment->user?->email }} · {{ $enrollment->course?->title }}</p>
                </li>
            @endforeach
        </ul>
    @endif
@endsection