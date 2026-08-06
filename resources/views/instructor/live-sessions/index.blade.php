@extends('layouts.instructor')

@section('title', 'الجلسات المباشرة')
@section('heading', 'الجلسات المباشرة')
@section('subheading', 'جدولة اللقاءات وروابط الانضمام')

@section('content')
    <div class="mx-auto max-w-6xl grid gap-8 lg:grid-cols-[minmax(0,1fr)_22rem]">
        <section>
            @if ($sessions->isEmpty())
                <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-6 py-14 text-center">
                    <p class="text-lg font-bold text-[var(--color-ink)]">لا جلسات مجدولة</p>
                    <p class="mt-2 text-sm text-[var(--color-text-secondary)]">أضف جلسة من النموذج الجانبي.</p>
                </div>
            @else
                <ul class="space-y-3">
                    @foreach ($sessions as $session)
                        <li class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <h2 class="font-bold text-[var(--color-ink)]">{{ $session->title }}</h2>
                                    <p class="mt-1 text-sm text-slate-500">{{ $session->course?->title }}</p>
                                    <p class="mt-2 text-sm font-medium text-[var(--color-ink)]">{{ $session->starts_at?->translatedFormat('l d M Y — H:i') }}</p>
                                </div>
                                <x-admin.status-badge :status="$session->status ?? 'scheduled'" />
                            </div>
                            @if ($session->join_url)
                                <a href="{{ $session->join_url }}" target="_blank" rel="noopener" class="mt-3 inline-flex text-sm font-semibold text-[var(--color-secondary)] hover:underline">رابط الانضمام</a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        @if (\Illuminate\Support\Facades\Route::has('instructor.live-sessions.store'))
            <aside class="rounded-2xl border border-[var(--color-line)] bg-white p-5 xl:sticky xl:top-24 xl:self-start">
                <h2 class="font-bold text-[var(--color-ink)]">جدولة جلسة</h2>
                <form method="POST" action="{{ route('instructor.live-sessions.store') }}" class="mt-4 space-y-3">
                    @csrf
                    <select name="course_id" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                        <option value="">اختر المقرر</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="title" required placeholder="عنوان الجلسة" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                    <input type="datetime-local" name="starts_at" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                    <input type="url" name="join_url" placeholder="رابط الانضمام (اختياري)" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                    <button type="submit" class="w-full rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">جدولة</button>
                </form>
            </aside>
        @endif
    </div>
@endsection
