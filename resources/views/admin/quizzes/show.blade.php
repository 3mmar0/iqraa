@extends('layouts.admin')

@section('title', $quiz->title)
@section('heading', $quiz->title)
@section('subheading', $quiz->course?->title ?? 'بدون مقرر')

@section('header-actions')
    @if ($quiz->course_id)
        <a href="{{ route('admin.courses.show', [$quiz->course_id, 'tab' => 'quizzes']) }}" class="admin-btn admin-btn-ghost">رجوع للمقرر</a>
    @endif
    <a href="{{ route('admin.quizzes.index') }}" class="admin-btn admin-btn-ghost">كل الاختبارات</a>
    <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="admin-btn admin-btn-primary">تعديل</a>
@endsection

@section('content')
    @php
        $tabs = [
            ['label' => 'الأسئلة', 'href' => route('admin.quizzes.show', [$quiz, 'tab' => 'questions']), 'active' => $tab === 'questions'],
            ['label' => 'المحاولات', 'href' => route('admin.quizzes.show', [$quiz, 'tab' => 'attempts']), 'active' => $tab === 'attempts'],
            ['label' => 'الإحصائيات', 'href' => route('admin.quizzes.show', [$quiz, 'tab' => 'statistics']), 'active' => $tab === 'statistics'],
            ['label' => 'الإعدادات', 'href' => route('admin.quizzes.show', [$quiz, 'tab' => 'settings']), 'active' => $tab === 'settings'],
            ['label' => 'النتائج', 'href' => route('admin.quizzes.show', [$quiz, 'tab' => 'results']), 'active' => $tab === 'results'],
            ['label' => 'لوحة المتصدرين', 'href' => route('admin.quizzes.show', [$quiz, 'tab' => 'leaderboard']), 'active' => $tab === 'leaderboard'],
        ];
    @endphp

    <div class="admin-content-enter space-y-5">
        <x-admin.action-toolbar>
            <x-admin.status-badge :status="$quiz->status" :label="$quiz->status === 'published' ? 'منشور' : 'مسودة'" />
            @if (Route::has('admin.quizzes.publish'))
                <form method="POST" action="{{ route('admin.quizzes.publish', $quiz) }}">@csrf<button class="admin-btn admin-btn-ghost admin-btn-sm">نشر</button></form>
            @endif
            @if (Route::has('admin.quizzes.unpublish'))
                <form method="POST" action="{{ route('admin.quizzes.unpublish', $quiz) }}">@csrf<button class="admin-btn admin-btn-ghost admin-btn-sm">إلغاء النشر</button></form>
            @endif
            @if (Route::has('admin.quizzes.duplicate'))
                <form method="POST" action="{{ route('admin.quizzes.duplicate', $quiz) }}">@csrf<button class="admin-btn admin-btn-ghost admin-btn-sm">نسخ</button></form>
            @endif
            @if (Route::has('admin.quizzes.randomize'))
                <form method="POST" action="{{ route('admin.quizzes.randomize', $quiz) }}">@csrf<button class="admin-btn admin-btn-ghost admin-btn-sm">خلط الأسئلة</button></form>
            @endif
        </x-admin.action-toolbar>

        <x-admin.tab-nav :tabs="$tabs" />

        <div class="admin-panel p-5 sm:p-6">
            @if ($tab === 'questions')
                <ul class="divide-y divide-slate-100">
                    @forelse ($quiz->questions as $question)
                        <li class="py-4 first:pt-0 last:pb-0">
                            <p class="text-sm font-semibold text-slate-900">{{ $question->position }}. {{ $question->body }}</p>
                            <p class="mt-1.5 text-xs text-slate-500">{{ $question->type }} · {{ $question->points }} نقطة · {{ $question->options->count() }} خيارات</p>
                        </li>
                    @empty
                        <li>
                            <x-admin.empty-state title="لا أسئلة بعد" description="أضف أسئلة لهذا الاختبار لبدء استخدامه." />
                        </li>
                    @endforelse
                </ul>
            @elseif ($tab === 'attempts')
                <x-admin.empty-state title="المحاولات" description="سجل محاولات الطلاب سيظهر هنا." />
            @elseif ($tab === 'statistics')
                <div class="grid gap-4 sm:grid-cols-3">
                    <x-admin.kpi-card label="عدد الأسئلة" :value="$quiz->questions->count()" />
                    <x-admin.kpi-card label="المدة" :value="$quiz->duration_minutes ?? '—'" />
                    <x-admin.kpi-card label="الحالة" :value="$quiz->status === 'published' ? 'منشور' : 'مسودة'" />
                </div>
            @elseif ($tab === 'settings')
                <dl class="mb-5 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-xl border border-slate-100 bg-slate-50/70 px-4 py-3">
                        <dt class="text-xs text-slate-500">المقرر</dt>
                        <dd class="mt-1 text-sm font-semibold">{{ $quiz->course?->title ?? '—' }}</dd>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50/70 px-4 py-3">
                        <dt class="text-xs text-slate-500">إظهار الإجابات</dt>
                        <dd class="mt-1 text-sm font-semibold">{{ $quiz->show_correct_answers ? 'نعم' : 'لا' }}</dd>
                    </div>
                </dl>
                @if (Route::has('admin.quizzes.assign-course'))
                    <form method="POST" action="{{ route('admin.quizzes.assign-course', $quiz) }}" class="mb-4 rounded-2xl border border-slate-200 p-4">
                        @csrf
                        <p class="mb-2 text-sm font-semibold">ربط بالمقرر</p>
                        <select name="course_id" class="admin-input mb-3">
                            @foreach (\App\Models\Course::orderBy('title')->get() as $course)
                                <option value="{{ $course->id }}" @selected($quiz->course_id === $course->id)>{{ $course->title }}</option>
                            @endforeach
                        </select>
                        <button class="admin-btn admin-btn-primary admin-btn-sm">حفظ</button>
                    </form>
                @endif
                @if (Route::has('admin.quizzes.assign-lesson'))
                    <form method="POST" action="{{ route('admin.quizzes.assign-lesson', $quiz) }}" class="mb-4 rounded-2xl border border-slate-200 p-4">
                        @csrf
                        <p class="mb-2 text-sm font-semibold">ربط بدرس</p>
                        <select name="lesson_id" class="admin-input mb-3">
                            @foreach (\App\Models\Lesson::with('course')->orderBy('title')->get() as $lesson)
                                <option value="{{ $lesson->id }}">{{ $lesson->title }} ({{ $lesson->course?->title }})</option>
                            @endforeach
                        </select>
                        <button class="admin-btn admin-btn-primary admin-btn-sm">ربط</button>
                    </form>
                @endif
                <form method="POST" action="{{ route('admin.quizzes.destroy', $quiz) }}" onsubmit="return confirm('حذف الاختبار؟');">
                    @csrf @method('DELETE')
                    <button class="admin-btn admin-btn-danger">حذف الاختبار</button>
                </form>
            @elseif ($tab === 'results')
                <x-admin.empty-state title="النتائج" description="نتائج الطلاب المجمّعة ستظهر هنا." />
            @elseif ($tab === 'leaderboard')
                <x-admin.empty-state title="لوحة المتصدرين" description="أعلى الدرجات ستظهر هنا." />
            @endif
        </div>
    </div>
@endsection
