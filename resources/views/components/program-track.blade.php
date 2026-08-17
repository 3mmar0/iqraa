@props([
    'category',
    'index' => 0,
])

@php
    $courseCount = $category->courses_count ?? $category->courses?->count() ?? 0;
    $coverIndex = ($index % 2) + 1;
@endphp

<a href="{{ route('public.courses.index', ['category_id' => $category->id]) }}"
   class="academy-track group block focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-primary)]">
    <div class="relative aspect-[16/9] overflow-hidden bg-[var(--color-ink)]">
        <img src="{{ asset('images/home/course-cover-'.$coverIndex.'.webp') }}" alt="" class="h-full w-full object-cover opacity-80 transition duration-500 group-hover:scale-[1.04] group-hover:opacity-95" width="640" height="360" loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-t from-[var(--color-ink)]/90 via-[var(--color-ink)]/30 to-transparent"></div>
        <div class="absolute inset-x-0 bottom-0 p-5 sm:p-6">
            <h3 class="academy-display text-xl font-bold text-white sm:text-2xl">{{ $category->name }}</h3>
            @if ($category->description)
                <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-white/75">{{ $category->description }}</p>
            @endif
        </div>
    </div>
    <div class="flex items-center justify-between gap-3 border-t border-[var(--color-line)] px-5 py-4">
        <span class="text-sm text-[var(--color-muted)]">{{ $courseCount }} {{ $courseCount === 1 ? 'مقرر' : 'مقررات' }}</span>
        <span class="text-sm font-bold text-[var(--color-primary)] group-hover:text-[var(--color-secondary-hover)]">عرض المساق ←</span>
    </div>
</a>
