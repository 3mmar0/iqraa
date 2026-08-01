@extends('layouts.student')

@section('title', 'الدعم')
@section('heading', 'الدعم الفني')
@section('subheading', 'افتح تذكرة أو راجع الأسئلة الشائعة')

@section('content')
    <div class="mx-auto max-w-3xl space-y-8">
        <section class="rounded-2xl border border-[var(--color-line)] bg-white p-6 shadow-[0_12px_32px_-24px_rgba(15,23,42,0.35)]">
            <h2 class="text-base font-semibold text-[var(--color-ink)]">تذكرة جديدة</h2>
            <form method="POST" action="{{ route('student.support.store') }}" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-[var(--color-ink)]" for="subject">الموضوع</label>
                    <input id="subject" name="subject" value="{{ old('subject') }}" required
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-[var(--color-ink)]" for="body">التفاصيل</label>
                    <textarea id="body" name="body" rows="4" required
                              class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">{{ old('body') }}</textarea>
                </div>
                <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">
                    إرسال التذكرة
                </button>
            </form>
        </section>

        <section>
            <h2 class="mb-3 text-lg font-semibold text-[var(--color-ink)]">تذاكرك</h2>
            @if ($tickets->isEmpty())
                <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-5 py-8 text-center text-sm text-[var(--color-text-secondary)]">
                    لا تذاكر مفتوحة أو سابقة.
                </div>
            @else
                <ul class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_12px_32px_-24px_rgba(15,23,42,0.35)]">
                    @foreach ($tickets as $ticket)
                        @php
                            $statusLabel = match ($ticket->status) {
                                'open' => 'مفتوحة',
                                'pending' => 'قيد المتابعة',
                                'closed' => 'مغلقة',
                                'resolved' => 'محلولة',
                                default => $ticket->status,
                            };
                        @endphp
                        <li class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
                            <div>
                                <p class="font-medium text-[var(--color-ink)]">{{ $ticket->subject }}</p>
                                <p class="mt-0.5 text-xs text-[var(--color-muted)]">{{ optional($ticket->created_at)?->diffForHumans() }}</p>
                            </div>
                            <span class="rounded-lg bg-[var(--color-sand)] px-2.5 py-1 text-xs font-semibold text-[var(--color-text-secondary)]">{{ $statusLabel }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        @if ($faqs->isNotEmpty())
            <section>
                <h2 class="mb-3 text-lg font-semibold text-[var(--color-ink)]">أسئلة شائعة</h2>
                <div class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white" x-data="{ open: null }">
                    @foreach ($faqs as $i => $faq)
                        <div>
                            <button type="button"
                                    class="flex w-full items-center justify-between gap-3 px-5 py-4 text-right sm:px-6"
                                    @click="open = open === {{ $i }} ? null : {{ $i }}">
                                <span class="font-medium text-[var(--color-ink)]">{{ $faq->title }}</span>
                                <span class="text-[var(--color-primary)]" x-text="open === {{ $i }} ? '−' : '+'"></span>
                            </button>
                            <div x-show="open === {{ $i }}" class="px-5 pb-4 text-sm leading-relaxed text-[var(--color-text-secondary)] sm:px-6" style="display: none;">
                                {{ $faq->body }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
