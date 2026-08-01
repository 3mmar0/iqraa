@extends('layouts.student')

@section('title', 'التقويم')
@section('heading', 'التقويم')
@section('subheading', 'المحاضرات والمواعيد المرتبطة بمقرراتك')

@section('content')
    <div class="mx-auto max-w-3xl">
        @if ($events->isEmpty())
            <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-6 py-14 text-center">
                <h2 class="text-lg font-semibold text-[var(--color-ink)]">لا أحداث مجدولة</h2>
                <p class="mx-auto mt-2 max-w-md text-sm text-[var(--color-text-secondary)]">
                    عند إضافة مواعيد لمقرراتك ستظهر هنا مرتبة زمنياً.
                </p>
            </div>
        @else
            <ol class="relative space-y-0 divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_12px_32px_-24px_rgba(15,23,42,0.35)]">
                @foreach ($events as $event)
                    <li class="px-5 py-5 sm:px-6">
                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                            <p class="font-semibold text-[var(--color-ink)]">{{ $event->title }}</p>
                            @if ($event->type)
                                <span class="rounded-md bg-[var(--color-sand)] px-2 py-0.5 text-xs font-medium text-[var(--color-text-secondary)]">{{ $event->type }}</span>
                            @endif
                        </div>
                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                            {{ optional($event->starts_at)?->timezone(config('app.timezone'))->format('Y/m/d H:i') }}
                            @if ($event->ends_at)
                                — {{ $event->ends_at->timezone(config('app.timezone'))->format('H:i') }}
                            @endif
                        </p>
                        @if ($event->course)
                            <p class="mt-1 text-xs text-[var(--color-muted)]">{{ $event->course->title }}</p>
                        @endif
                    </li>
                @endforeach
            </ol>
        @endif
    </div>
@endsection
