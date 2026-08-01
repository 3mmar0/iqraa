@extends('layouts.student')

@section('title', $lesson->title)

@section('content')
    <h1 class="mb-2 text-2xl font-bold">{{ $lesson->title }}</h1>
    <p class="mb-4 text-slate-600">{{ $lesson->description }}</p>

    <div class="mb-6 rounded-xl border border-slate-200 bg-white p-4">
        <h2 class="mb-2 font-semibold">المحتوى</h2>
        @forelse ($lesson->mediaAssets as $asset)
            <p class="text-sm">{{ $asset->type }}: {{ $asset->original_name ?? $asset->path }}</p>
        @empty
            <p class="text-sm text-slate-500">لا توجد ملفات مرفقة (التشغيل الخاص يُضاف عند رفع الفيديو).</p>
        @endforelse
    </div>

    <form method="POST" action="{{ route('student.lessons.complete', $lesson) }}" class="mb-6">
        @csrf
        <button class="rounded-lg bg-[var(--color-primary)] px-4 py-2 text-white">تعليم كمكتمل</button>
    </form>

    <div class="flex gap-4 text-sm">
        @if ($previous)
            <a class="text-[var(--color-primary)] hover:underline" href="{{ route('student.lessons.show', $previous) }}">الدرس السابق</a>
        @endif
        @if ($next)
            <a class="text-[var(--color-primary)] hover:underline" href="{{ route('student.lessons.show', $next) }}">الدرس التالي</a>
        @endif
    </div>
@endsection
