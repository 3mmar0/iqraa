@extends('layouts.instructor')

@section('title', 'الإعلانات')
@section('heading', 'الإعلانات')
@section('subheading', 'انشر تحديثات لمقرراتك وطلابك')

@section('content')
    <div class="mx-auto max-w-6xl grid gap-8 lg:grid-cols-[minmax(0,1fr)_22rem]">
        <section class="min-w-0">
            @if ($announcements->isEmpty())
                <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-6 py-14 text-center">
                    <p class="text-lg font-bold text-[var(--color-ink)]">لا إعلانات بعد</p>
                    <p class="mt-2 text-sm text-[var(--color-text-secondary)]">انشر أول إعلان من النموذج الجانبي.</p>
                </div>
            @else
                <ul class="space-y-3">
                    @foreach ($announcements as $item)
                        <li class="rounded-2xl border border-[var(--color-line)] bg-white p-5 shadow-[0_10px_28px_-22px_rgba(47,58,69,0.35)]">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="text-base font-bold text-[var(--color-ink)]">{{ $item->title }}</h2>
                                <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs text-slate-600">{{ $item->course?->title }}</span>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">{{ $item->published_at?->diffForHumans() ?? $item->created_at?->diffForHumans() }}</p>
                            <p class="mt-3 text-sm leading-relaxed text-[var(--color-text-secondary)] whitespace-pre-wrap">{{ $item->body }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        @if (\Illuminate\Support\Facades\Route::has('instructor.announcements.store'))
            <aside class="rounded-2xl border border-[var(--color-line)] bg-white p-5 xl:sticky xl:top-24 xl:self-start">
                <h2 class="font-bold text-[var(--color-ink)]">نشر إعلان</h2>
                <form method="POST" action="{{ route('instructor.announcements.store') }}" class="mt-4 space-y-3">
                    @csrf
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">المقرر</label>
                        <select name="course_id" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                            <option value="">اختر المقرر</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">العنوان</label>
                        <input type="text" name="title" required placeholder="عنوان الإعلان" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">النص</label>
                        <textarea name="body" rows="5" required placeholder="نص الإعلان" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm"></textarea>
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">نشر</button>
                </form>
            </aside>
        @endif
    </div>
@endsection
