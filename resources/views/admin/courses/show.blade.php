@extends('layouts.admin')

@section('title', $course->title)
@section('heading', $course->title)
@section('subheading', 'تفاصيل المقرر وإدارته')

@section('header-actions')
    <a href="{{ route('admin.courses.edit', $course) }}" class="rounded-xl border bg-white px-4 py-2.5 text-sm">تعديل</a>
    <a href="{{ route('admin.lessons.create', ['course_id' => $course->id]) }}" class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white">درس جديد</a>
@endsection

@section('content')
    @php
        $tabs = [
            ['label' => 'عام', 'href' => route('admin.courses.show', [$course, 'tab' => 'general']), 'active' => $tab === 'general'],
            ['label' => 'الدروس', 'href' => route('admin.courses.show', [$course, 'tab' => 'lessons']), 'active' => $tab === 'lessons'],
            ['label' => 'الملفات', 'href' => route('admin.courses.show', [$course, 'tab' => 'files']), 'active' => $tab === 'files'],
            ['label' => 'الفيديو', 'href' => route('admin.courses.show', [$course, 'tab' => 'videos']), 'active' => $tab === 'videos'],
            ['label' => 'الاختبارات', 'href' => route('admin.courses.show', [$course, 'tab' => 'quizzes']), 'active' => $tab === 'quizzes'],
            ['label' => 'الواجبات', 'href' => route('admin.courses.show', [$course, 'tab' => 'assignments']), 'active' => $tab === 'assignments'],
            ['label' => 'الطلاب', 'href' => route('admin.courses.show', [$course, 'tab' => 'students']), 'active' => $tab === 'students'],
            ['label' => 'التحليلات', 'href' => route('admin.courses.show', [$course, 'tab' => 'analytics']), 'active' => $tab === 'analytics'],
            ['label' => 'التقييمات', 'href' => route('admin.courses.show', [$course, 'tab' => 'reviews']), 'active' => $tab === 'reviews'],
            ['label' => 'الإعدادات', 'href' => route('admin.courses.show', [$course, 'tab' => 'settings']), 'active' => $tab === 'settings'],
        ];
    @endphp

    <div class="mb-4 flex flex-wrap gap-2">
        @if (Route::has('admin.courses.publish'))
            <form method="POST" action="{{ route('admin.courses.publish', $course) }}">@csrf<button class="rounded-lg border px-3 py-1.5 text-xs">نشر</button></form>
        @endif
        @if (Route::has('admin.courses.hide'))
            <form method="POST" action="{{ route('admin.courses.hide', $course) }}">@csrf<button class="rounded-lg border px-3 py-1.5 text-xs">إخفاء</button></form>
        @endif
        @if (Route::has('admin.courses.archive'))
            <form method="POST" action="{{ route('admin.courses.archive', $course) }}">@csrf<button class="rounded-lg border px-3 py-1.5 text-xs">أرشفة</button></form>
        @endif
        @if (Route::has('admin.courses.duplicate'))
            <form method="POST" action="{{ route('admin.courses.duplicate', $course) }}">@csrf<button class="rounded-lg border px-3 py-1.5 text-xs">نسخ</button></form>
        @endif
    </div>

    <x-admin.tab-nav :tabs="$tabs" class="mb-6" />

    <div class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
        @include('admin.courses.tabs.'.$tab)
    </div>
@endsection
