@extends('layouts.instructor')

@section('title', 'التقويم')
@section('heading', 'التقويم')
@section('subheading', 'أحداث المقررات والجلسات المباشرة')

@section('header-actions')
    <a href="{{ route('instructor.live-sessions.index') }}" class="rounded-2xl bg-[var(--color-primary)] px-3.5 py-2 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">جدولة جلسة</a>
@endsection

@section('content')
    <div class="mx-auto max-w-6xl grid gap-6 lg:grid-cols-2">
        <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
            <h2 class="font-bold text-[var(--color-ink)]">أحداث التقويم</h2>
            @if ($events->isEmpty())
                <p class="mt-4 text-sm text-slate-500">لا أحداث مسجّلة.</p>
            @else
                <ul class="mt-4 space-y-3">
                    @foreach ($events as $event)
                        <li class="rounded-xl border border-[var(--color-line)] bg-[var(--color-sand)]/70 px-4 py-3">
                            <p class="text-sm font-semibold text-[var(--color-ink)]">{{ $event->title }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">{{ $event->starts_at?->translatedFormat('d M Y — H:i') }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
            <h2 class="font-bold text-[var(--color-ink)]">الجلسات المباشرة</h2>
            @if ($sessions->isEmpty())
                <p class="mt-4 text-sm text-slate-500">لا جلسات في التقويم.</p>
            @else
                <ul class="mt-4 space-y-3">
                    @foreach ($sessions as $session)
                        <li class="rounded-xl border border-[var(--color-line)] bg-[var(--color-sand)]/70 px-4 py-3">
                            <p class="text-sm font-semibold text-[var(--color-ink)]">{{ $session->title }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">{{ $session->starts_at?->translatedFormat('d M Y — H:i') }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
@endsection
