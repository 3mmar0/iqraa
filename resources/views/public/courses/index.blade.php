@extends('layouts.app')
@section('title', 'كتالوج المقررات')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">كتالوج المقررات</h1>
    @if ($courses->isEmpty())
        <x-empty-state message="لا مقررات منشورة حالياً." />
    @else
        <div class="grid gap-4 md:grid-cols-2">
            @foreach ($courses as $course)
                <article class="rounded-xl border border-slate-200 bg-white p-4">
                    <h2 class="font-semibold">{{ $course->title }}</h2>
                    <p class="text-sm text-slate-600">{{ $course->instructor?->name }}</p>
                    @if (\Illuminate\Support\Facades\Route::has('public.courses.show'))
                        <a href="{{ route('public.courses.show', $course) }}" class="mt-3 inline-block text-sm text-teal-700 hover:underline">التفاصيل</a>
                    @endif
                </article>
            @endforeach
        </div>
    @endif
@endsection