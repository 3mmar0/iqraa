@extends('layouts.student')

@section('title', $lesson->title)

@section('heading')
    {{ $lesson->title }}
@endsection

@section('subheading')
    {{ $lesson->course?->title }}
    @if ($position && $total)
        · الدرس {{ $position }} من {{ $total }}
    @endif
@endsection

@section('header-actions')
    @if ($lesson->course)
        <a href="{{ route('student.courses.show', $lesson->course) }}"
           class="rounded-xl border border-[var(--color-line)] bg-white px-3.5 py-2 text-sm font-medium text-[var(--color-text-secondary)] hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">
            المقرر
        </a>
    @endif
@endsection

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">
        @if ($lesson->description)
            <p class="text-sm leading-relaxed text-[var(--color-text-secondary)]">{{ $lesson->description }}</p>
        @endif

        <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5 shadow-[0_12px_32px_-24px_rgba(15,23,42,0.35)] sm:p-6">
            <h2 class="text-base font-semibold text-[var(--color-ink)]">مواد الدرس</h2>
            @forelse ($lesson->mediaAssets as $asset)
                @php
                    $typeLabel = match ($asset->type) {
                        'video' => 'فيديو',
                        'pdf' => 'PDF',
                        'attachment' => 'مرفق',
                        default => $asset->type,
                    };
                @endphp
                <a href="{{ route('student.media.show', $asset) }}"
                   class="mt-3 flex items-center justify-between gap-3 rounded-xl border border-[var(--color-line)] px-4 py-3 text-sm transition hover:border-[var(--color-primary)] hover:bg-[var(--color-sand)]">
                    <span class="min-w-0">
                        <span class="block truncate font-medium text-[var(--color-ink)]">{{ $asset->original_name ?? basename($asset->path) }}</span>
                        <span class="mt-0.5 block text-xs text-[var(--color-text-secondary)]">{{ $typeLabel }}</span>
                    </span>
                    <span class="shrink-0 font-medium text-[var(--color-primary)]">فتح</span>
                </a>
            @empty
                <p class="mt-3 text-sm text-[var(--color-text-secondary)]">لا توجد ملفات مرفقة لهذا الدرس بعد.</p>
            @endforelse
        </section>

        <div class="flex flex-wrap items-center gap-3">
            @if ($isCompleted)
                <span class="inline-flex items-center rounded-xl bg-[var(--color-primary-light)] px-4 py-2.5 text-sm font-semibold text-[var(--color-primary-hover)]">
                    مكتمل
                </span>
            @else
                <form method="POST" action="{{ route('student.lessons.complete', $lesson) }}">
                    @csrf
                    <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">
                        تعليم كمكتمل
                    </button>
                </form>
            @endif

            <div class="mr-auto flex flex-wrap gap-4 text-sm">
                @if ($previous)
                    <a class="font-medium text-[var(--color-text-secondary)] hover:text-[var(--color-primary)]" href="{{ route('student.lessons.show', $previous) }}">الدرس السابق</a>
                @endif
                @if ($next)
                    <a class="font-medium text-[var(--color-primary)] hover:underline" href="{{ route('student.lessons.show', $next) }}">الدرس التالي</a>
                @endif
            </div>
        </div>
    </div>
@endsection
