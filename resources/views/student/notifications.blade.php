@extends('layouts.student')

@section('title', 'الإشعارات')
@section('heading', 'الإشعارات')
@section('subheading', 'تنبيهات حسابك ونشاط المقررات')

@section('content')
    <div class="mx-auto max-w-3xl">
        @if ($notifications->isEmpty())
            <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-6 py-14 text-center">
                <h2 class="text-lg font-semibold text-[var(--color-ink)]">لا إشعارات</h2>
                <p class="mx-auto mt-2 max-w-md text-sm text-[var(--color-text-secondary)]">
                    عند وصول تنبيهات جديدة ستظهر في هذه القائمة.
                </p>
            </div>
        @else
            <ul class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_12px_32px_-24px_rgba(15,23,42,0.35)]">
                @foreach ($notifications as $notification)
                    @php
                        $data = $notification->data ?? [];
                        $title = $data['title'] ?? $data['subject'] ?? 'إشعار';
                        $body = $data['body'] ?? $data['message'] ?? null;
                        $unread = is_null($notification->read_at);
                    @endphp
                    <li @class([
                        'px-5 py-4 sm:px-6',
                        'bg-[var(--color-primary-light)]/35' => $unread,
                    ])>
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <p class="font-medium text-[var(--color-ink)]">{{ $title }}</p>
                            <time class="text-xs text-[var(--color-muted)]">{{ $notification->created_at?->diffForHumans() }}</time>
                        </div>
                        @if ($body)
                            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">{{ $body }}</p>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
