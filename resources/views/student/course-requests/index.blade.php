@extends('layouts.student')

@section('title', 'طلبات الالتحاق')

@section('heading', 'طلبات الالتحاق')
@section('subheading', 'اطلب مقرراً منشوراً وتابع حالة المراجعة')

@section('content')
    <div class="mx-auto max-w-3xl space-y-8">
        <section class="rounded-2xl border border-[var(--color-line)] bg-white p-6 shadow-[0_12px_32px_-24px_rgba(15,23,42,0.35)]">
            <h2 class="text-base font-semibold text-[var(--color-ink)]">طلب مقرر جديد</h2>
            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">يراجع الفريق طلبك قبل تفعيل الوصول.</p>

            @if ($catalog->isEmpty())
                <p class="mt-5 text-sm text-[var(--color-text-secondary)]">لا مقررات منشورة متاحة للطلب حالياً.</p>
            @else
                <form method="POST" action="{{ route('student.course-requests.store') }}" class="mt-5 space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-[var(--color-ink)]" for="course_id">المقرر</label>
                        <select id="course_id" name="course_id" required
                                class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
                            <option value="">اختر مقرراً</option>
                            @foreach ($catalog as $course)
                                <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-[var(--color-ink)]" for="message">ملاحظة (اختياري)</label>
                        <textarea id="message" name="message" rows="3" placeholder="سبب الطلب أو أي تفاصيل مفيدة"
                                  class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">{{ old('message') }}</textarea>
                    </div>
                    <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">
                        إرسال الطلب
                    </button>
                </form>
            @endif
        </section>

        <section>
            <h2 class="mb-3 text-lg font-semibold text-[var(--color-ink)]">طلباتك السابقة</h2>
            @if ($requests->isEmpty())
                <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-5 py-10 text-center text-sm text-[var(--color-text-secondary)]">
                    لم تُرسل أي طلبات بعد.
                </div>
            @else
                <ul class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_12px_32px_-24px_rgba(15,23,42,0.35)]">
                    @foreach ($requests as $item)
                        @php
                            $statusLabel = match ($item->status) {
                                'pending' => 'قيد المراجعة',
                                'approved' => 'موافق عليه',
                                'rejected' => 'مرفوض',
                                default => $item->status,
                            };
                            $statusClass = match ($item->status) {
                                'pending' => 'bg-[var(--color-accent-light)] text-amber-900',
                                'approved' => 'bg-[var(--color-primary-light)] text-[var(--color-primary-hover)]',
                                'rejected' => 'bg-red-50 text-[var(--color-danger)]',
                                default => 'bg-[var(--color-sand)] text-[var(--color-text-secondary)]',
                            };
                        @endphp
                        <li class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
                            <div class="min-w-0">
                                <p class="font-medium text-[var(--color-ink)]">{{ $item->course->title }}</p>
                                <p class="mt-0.5 text-xs text-[var(--color-muted)]">{{ optional($item->created_at)?->diffForHumans() }}</p>
                            </div>
                            <span class="rounded-lg px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">{{ $statusLabel }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
@endsection
