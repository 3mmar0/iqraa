@extends('layouts.instructor')

@section('title', 'الإعلانات')

@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">الإعلانات</h1>

    @if (\Illuminate\Support\Facades\Route::has('instructor.announcements.store'))
        <form method="POST" action="{{ route('instructor.announcements.store') }}" class="mb-8 max-w-xl space-y-3 rounded-xl border border-slate-200 bg-white p-4">
            @csrf
            <select name="course_id" required class="w-full rounded border border-slate-300 px-3 py-2">
                <option value="">اختر المقرر</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                @endforeach
            </select>
            <input type="text" name="title" required placeholder="عنوان الإعلان" class="w-full rounded border border-slate-300 px-3 py-2">
            <textarea name="body" rows="3" required placeholder="نص الإعلان" class="w-full rounded border border-slate-300 px-3 py-2"></textarea>
            <button type="submit" class="rounded bg-teal-700 px-4 py-2 text-white">نشر</button>
        </form>
    @endif

    @if ($announcements->isEmpty())
        <x-empty-state message="لا إعلانات بعد." />
    @else
        <ul class="space-y-3">
            @foreach ($announcements as $item)
                <li class="rounded-xl border border-slate-200 bg-white p-4">
                    <h2 class="font-semibold">{{ $item->title }}</h2>
                    <p class="text-sm text-slate-500">{{ $item->course?->title }} · {{ $item->published_at?->format('Y-m-d') }}</p>
                    <p class="mt-2 text-sm">{{ $item->body }}</p>
                </li>
            @endforeach
        </ul>
    @endif
@endsection