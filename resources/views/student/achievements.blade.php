@extends('layouts.student')

@section('title', 'الإنجازات')
@section('heading', 'الإنجازات')
@section('subheading', 'شارات حصلت عليها وما يمكن تحقيقه')

@section('content')
    <div class="mx-auto max-w-5xl space-y-8">
        <section>
            <h2 class="mb-3 text-lg font-semibold text-[var(--color-ink)]">حصلت عليها</h2>
            @if ($achievements->isEmpty())
                <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-5 py-10 text-center text-sm text-[var(--color-text-secondary)]">
                    لم تحصل على إنجازات بعد — أكمل الدروس والاختبارات لتظهر الشارات هنا.
                </div>
            @else
                <ul class="grid gap-3 sm:grid-cols-2">
                    @foreach ($achievements as $achievement)
                        <li class="rounded-2xl border border-[var(--color-line)] bg-white px-5 py-5 shadow-[0_12px_32px_-24px_rgba(15,23,42,0.35)]">
                            <p class="font-semibold text-[var(--color-ink)]">{{ $achievement->title }}</p>
                            @if ($achievement->description)
                                <p class="mt-1 text-sm text-[var(--color-text-secondary)]">{{ $achievement->description }}</p>
                            @endif
                            @if ($achievement->pivot?->created_at)
                                <p class="mt-3 text-xs text-[var(--color-muted)]">{{ $achievement->pivot->created_at->diffForHumans() }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        @php
            $earnedIds = $achievements->pluck('id')->all();
            $locked = $available->whereNotIn('id', $earnedIds);
        @endphp
        @if ($locked->isNotEmpty())
            <section>
                <h2 class="mb-3 text-lg font-semibold text-[var(--color-ink)]">متاحة للتحقيق</h2>
                <ul class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white">
                    @foreach ($locked as $achievement)
                        <li class="px-5 py-4 sm:px-6">
                            <p class="font-medium text-[var(--color-text-secondary)]">{{ $achievement->title }}</p>
                            @if ($achievement->description)
                                <p class="mt-0.5 text-sm text-[var(--color-muted)]">{{ $achievement->description }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
    </div>
@endsection
