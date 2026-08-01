@extends('layouts.admin')

@section('title', $course->title)
@section('heading', $course->title)
@section('subheading', 'تفاصيل المقرر والدروس والملتحقين')

@section('header-actions')
    <a href="{{ route('admin.courses.edit', $course) }}" class="rounded-xl border bg-white px-4 py-2.5 text-sm">تعديل</a>
    <a href="{{ route('admin.lessons.create', ['course_id' => $course->id]) }}" class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white">درس جديد</a>
@endsection

@section('content')
    <div class="mb-6 grid gap-4 lg:grid-cols-3">
        <div class="rounded-2xl border border-[var(--color-line)] bg-white p-5 lg:col-span-2">
            <p class="text-sm text-slate-600 whitespace-pre-line">{{ $course->description ?: 'لا يوجد وصف.' }}</p>
            <dl class="mt-4 grid gap-3 sm:grid-cols-2 text-sm">
                <div><dt class="text-slate-500">المحاضر</dt><dd class="font-medium">{{ $course->instructor?->name ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">الحالة</dt><dd class="font-medium">{{ $course->status }}</dd></div>
                <div><dt class="text-slate-500">الساعات</dt><dd class="font-medium">{{ $course->hours ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">الترم</dt><dd class="font-medium">{{ $course->term_label ?? '—' }}</dd></div>
            </dl>
        </div>
        <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" class="rounded-2xl border border-rose-200 bg-rose-50 p-5" onsubmit="return confirm('حذف المقرر؟');">
            @csrf
            @method('DELETE')
            <p class="mb-3 text-sm text-rose-800">حذف المقرر نهائياً (ناعم).</p>
            <button class="rounded-xl bg-rose-700 px-4 py-2 text-sm text-white">حذف المقرر</button>
        </form>
    </div>

    <section class="mb-6 rounded-2xl border border-[var(--color-line)] bg-white p-5">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="font-semibold">الدروس ({{ $course->lessons->count() }})</h2>
            <a href="{{ route('admin.lessons.index', ['course_id' => $course->id]) }}" class="text-sm text-teal-700 hover:underline">إدارة الدروس</a>
        </div>
        <ul class="divide-y divide-slate-100">
            @forelse ($course->lessons as $lesson)
                <li class="flex items-center justify-between py-3 text-sm">
                    <span>{{ $lesson->position }}. {{ $lesson->title }}</span>
                    <a href="{{ route('admin.lessons.edit', $lesson) }}" class="text-teal-700 hover:underline">تعديل</a>
                </li>
            @empty
                <li class="py-6 text-slate-500">لا دروس بعد.</li>
            @endforelse
        </ul>
    </section>

    <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
        <h2 class="mb-3 font-semibold">الملتحقون ({{ $course->enrollments->count() }})</h2>
        <ul class="divide-y divide-slate-100 text-sm">
            @forelse ($course->enrollments as $enrollment)
                <li class="py-2">{{ $enrollment->user?->name }} <span class="text-slate-500">({{ $enrollment->user?->email }})</span></li>
            @empty
                <li class="py-6 text-slate-500">لا ملتحقين بعد.</li>
            @endforelse
        </ul>
    </section>
@endsection
