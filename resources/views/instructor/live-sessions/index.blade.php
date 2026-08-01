@extends('layouts.instructor')

@section('title', 'الجلسات المباشرة')

@section('content')
    <h1 class="mb-6 text-2xl font-bold text-[var(--color-ink)]">الجلسات المباشرة</h1>

    @if (\Illuminate\Support\Facades\Route::has('instructor.live-sessions.store'))
        <form method="POST" action="{{ route('instructor.live-sessions.store') }}" class="mb-8 max-w-xl space-y-3 rounded-xl border border-slate-200 bg-white p-4">
            @csrf
            <select name="course_id" required class="w-full rounded border border-slate-300 px-3 py-2">
                <option value="">اختر المقرر</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                @endforeach
            </select>
            <input type="text" name="title" required placeholder="عنوان الجلسة" class="w-full rounded border border-slate-300 px-3 py-2">
            <input type="datetime-local" name="starts_at" required class="w-full rounded border border-slate-300 px-3 py-2">
            <input type="url" name="join_url" placeholder="رابط الانضمام" class="w-full rounded border border-slate-300 px-3 py-2">
            <button type="submit" class="rounded bg-[var(--color-primary)] px-4 py-2 text-white">جدولة</button>
        </form>
    @endif

    @if ($sessions->isEmpty())
        <x-empty-state message="لا جلسات مجدولة." />
    @else
        <ul class="space-y-3">
            @foreach ($sessions as $session)
                <li class="rounded-xl border border-slate-200 bg-white p-4">
                    <p class="font-medium">{{ $session->title }}</p>
                    <p class="text-sm text-slate-600">{{ $session->course?->title }} · {{ $session->starts_at }}</p>
                    @if ($session->join_url)
                        <a href="{{ $session->join_url }}" class="text-sm text-[var(--color-primary)] hover:underline" target="_blank" rel="noopener">رابط الانضمام</a>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
@endsection