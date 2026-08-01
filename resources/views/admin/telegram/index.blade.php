@extends('layouts.admin')

@section('title', 'تيليجرام')
@section('heading', 'مجموعات تيليجرام')
@section('subheading', 'ربط المقررات وإدارة الدعوات')

@section('content')
    <form method="POST" action="{{ route('admin.telegram.store') }}" class="mb-6 rounded-2xl border bg-white p-5">
        @csrf
        <h2 class="mb-3 font-semibold">مجموعة جديدة</h2>
        <div class="grid gap-3 sm:grid-cols-4">
            <input name="title" required placeholder="العنوان" class="rounded-xl border px-3 py-2 text-sm sm:col-span-2">
            <input name="chat_id" placeholder="Chat ID" class="rounded-xl border px-3 py-2 text-sm">
            <select name="status" class="rounded-xl border px-3 py-2 text-sm">
                <option value="active">نشطة</option>
                <option value="inactive">غير نشطة</option>
            </select>
            <select name="course_id" class="rounded-xl border px-3 py-2 text-sm sm:col-span-2">
                <option value="">بدون مقرر</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                @endforeach
            </select>
            <button class="rounded-xl bg-[var(--color-primary)] px-4 py-2 text-sm text-white sm:col-span-2">إنشاء</button>
        </div>
    </form>

    <div class="space-y-4">
        @forelse ($groups as $group)
            <article class="rounded-2xl border bg-white p-5">
                <div class="mb-3 flex flex-wrap items-start justify-between gap-2">
                    <div>
                        <h3 class="font-semibold">{{ $group->title }}</h3>
                        <p class="text-sm text-slate-500">Chat: {{ $group->chat_id ?? '—' }} · {{ $group->course?->title ?? 'بدون مقرر' }}</p>
                        @if ($group->invite_link)
                            <p class="mt-1 text-xs font-mono text-[var(--color-primary)]">{{ $group->invite_link }}</p>
                            <p class="text-xs text-slate-500">ينتهي: {{ $group->invite_expires_at?->format('Y-m-d H:i') ?? '—' }}</p>
                        @endif
                    </div>
                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs">{{ $group->status }}</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    <form method="POST" action="{{ route('admin.telegram.attachCourse', $group) }}" class="flex gap-1">
                        @csrf
                        <select name="course_id" class="rounded-lg border px-2 py-1 text-xs">
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" @selected($group->course_id === $course->id)>{{ $course->title }}</option>
                            @endforeach
                        </select>
                        <button class="rounded-lg border px-2 py-1 text-xs">ربط مقرر</button>
                    </form>
                    <form method="POST" action="{{ route('admin.telegram.generateInvite', $group) }}">@csrf<button class="rounded-lg border px-2 py-1 text-xs">رابط دعوة</button></form>
                    <form method="POST" action="{{ route('admin.telegram.expireLink', $group) }}">@csrf<button class="rounded-lg border px-2 py-1 text-xs">إنهاء الرابط</button></form>
                </div>
                <form method="POST" action="{{ route('admin.telegram.sendAnnouncement', $group) }}" class="mt-3 flex gap-2">
                    @csrf
                    <input name="message" required placeholder="رسالة إعلان..." class="flex-1 rounded-xl border px-3 py-2 text-sm">
                    <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm text-white">إرسال</button>
                </form>
            </article>
        @empty
            <p class="rounded-2xl border bg-white p-10 text-center text-slate-500">لا مجموعات تيليجرام.</p>
        @endforelse
    </div>

    @if ($groups->hasPages())
        <div class="mt-4">{{ $groups->links() }}</div>
    @endif
@endsection
