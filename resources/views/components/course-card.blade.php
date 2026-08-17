@props([
    'course',
    'index' => 0,
    'eager' => false,
])

@php
    $cover = $course->image_path
        ? asset('storage/'.$course->image_path)
        : asset('images/home/'.(($index % 2) === 0 ? 'islamic-studies-cover' : 'tajweed-cover').'.webp');
    $lessonCount = $course->lessons_count ?? $course->lessons?->count() ?? 0;
    $enrollmentCount = $course->enrollments_count ?? null;
@endphp

<a href="{{ route('public.courses.show', $course) }}"
   class="academy-card group flex flex-col focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-primary)]">
    <div class="relative aspect-[16/10] overflow-hidden bg-[var(--color-ink)]">
        <img src="{{ $cover }}" alt="" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]" width="640" height="400" loading="{{ $eager ? 'eager' : 'lazy' }}">
        <div class="absolute inset-x-0 top-0 flex items-start justify-between gap-2 p-3">
            <span class="academy-status">متاح الآن</span>
            @if ($course->category)
                <span class="rounded-full bg-[var(--color-ink)]/75 px-2.5 py-1 text-xs font-medium text-white/90 backdrop-blur-sm">{{ $course->category->name }}</span>
            @endif
        </div>
    </div>
    <div class="flex flex-1 flex-col border-t border-[var(--color-line)] p-5">
        <h3 class="text-lg font-bold leading-snug text-[var(--color-text)] transition group-hover:text-[var(--color-secondary-hover)]">{{ $course->title }}</h3>

        @if ($course->instructor)
            <div class="mt-3 flex items-center gap-2.5">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[var(--color-primary-light)] text-sm font-bold text-[var(--color-secondary-hover)]">
                    {{ mb_substr($course->instructor->name, 0, 1) }}
                </span>
                <span class="text-sm font-medium text-[var(--color-text-secondary)]">{{ $course->instructor->name }}</span>
            </div>
        @endif

        @if ($course->description)
            <p class="mt-3 line-clamp-2 flex-1 text-sm leading-relaxed text-[var(--color-muted)]">{{ $course->description }}</p>
        @endif

        <div class="mt-4 flex flex-wrap items-center gap-3 border-t border-[var(--color-line)] pt-4 text-xs text-[var(--color-muted)]">
            @if ($lessonCount > 0)
                <span class="inline-flex items-center gap-1">
                    <svg class="h-3.5 w-3.5 text-[var(--color-primary)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    {{ $lessonCount }} {{ $lessonCount === 1 ? 'درس' : 'دروس' }}
                </span>
            @endif
            @if ($course->hours)
                <span>{{ $course->hours }} ساعة</span>
            @endif
            @if ($enrollmentCount !== null && $enrollmentCount > 0)
                <span>{{ number_format($enrollmentCount) }} مسجّل</span>
            @endif
        </div>

        <span class="mt-4 text-sm font-bold text-[var(--color-primary)] group-hover:text-[var(--color-secondary-hover)]">عرض المقرر ←</span>
    </div>
</a>
