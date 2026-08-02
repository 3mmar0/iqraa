@extends('layouts.admin')

@section('title', $course->title)
@section('heading', $course->title)
@section('subheading', 'تفاصيل المقرر وإدارته')

@section('header-actions')
    <a href="{{ route('admin.courses.index') }}" class="admin-btn admin-btn-ghost">رجوع</a>
    <a href="{{ route('admin.courses.edit', $course) }}" class="admin-btn admin-btn-primary">تعديل المقرر</a>
@endsection

@section('content')
    @include('components.alert')

    @php
        $statusLabels = ['draft' => 'مسودة', 'published' => 'منشور', 'archived' => 'مؤرشف', 'hidden' => 'مخفي'];
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

    <div class="admin-content-enter space-y-5">
        <div class="admin-panel overflow-hidden">
            <div class="relative overflow-hidden bg-gradient-to-l from-[var(--color-teal-50)] via-white to-white px-5 py-5 sm:px-6">
                <div class="pointer-events-none absolute inset-y-0 left-0 w-40 bg-[radial-gradient(circle_at_top_left,rgba(15,118,110,0.12),transparent_70%)]"></div>
                <div class="relative flex flex-wrap items-start justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="admin-entity-thumb !h-14 !w-14 text-base">
                            @if ($course->image_path)
                                <img src="{{ asset('storage/'.$course->image_path) }}" alt="">
                            @else
                                {{ mb_substr($course->title, 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <div class="mb-2 flex flex-wrap items-center gap-2">
                                <x-admin.status-badge :status="$course->status" :label="$statusLabels[$course->status] ?? $course->status" />
                                @if ($course->category)
                                    <span class="admin-chip admin-chip-draft">{{ $course->category->name }}</span>
                                @endif
                            </div>
                            <p class="text-sm text-slate-600">{{ $course->instructor?->name ?? 'بدون محاضر' }} · {{ $course->enrollments_count }} طالب · {{ $course->lessons_count }} درس</p>
                        </div>
                    </div>
                    <x-admin.action-toolbar class="!mb-0 !border-0 !bg-transparent !p-0">
                        @if (Route::has('admin.courses.publish'))
                            <form method="POST" action="{{ route('admin.courses.publish', $course) }}">@csrf<button class="admin-btn admin-btn-ghost admin-btn-sm">نشر</button></form>
                        @endif
                        @if (Route::has('admin.courses.hide'))
                            <form method="POST" action="{{ route('admin.courses.hide', $course) }}">@csrf<button class="admin-btn admin-btn-ghost admin-btn-sm">إخفاء</button></form>
                        @endif
                        @if (Route::has('admin.courses.archive'))
                            <form method="POST" action="{{ route('admin.courses.archive', $course) }}">@csrf<button class="admin-btn admin-btn-ghost admin-btn-sm">أرشفة</button></form>
                        @endif
                        @if (Route::has('admin.courses.duplicate'))
                            <form method="POST" action="{{ route('admin.courses.duplicate', $course) }}">@csrf<button class="admin-btn admin-btn-ghost admin-btn-sm">نسخ</button></form>
                        @endif
                    </x-admin.action-toolbar>
                </div>
            </div>
        </div>

        <x-admin.tab-nav :tabs="$tabs" />

        <div class="admin-panel p-5 sm:p-6">
            @include('admin.courses.tabs.'.$tab)
        </div>
    </div>
@endsection
