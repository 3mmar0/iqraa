@extends('layouts.admin')

@section('title', $quiz->title)
@section('heading', $quiz->title)
@section('subheading', $quiz->course?->title ?? 'بدون مقرر')

@section('header-actions')
    <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="rounded-xl border bg-white px-4 py-2.5 text-sm">تعديل</a>
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

    <div class="mb-4 flex flex-wrap gap-2">
        @if (Route::has('admin.quizzes.publish'))
            <form method="POST" action="{{ route('admin.quizzes.publish', $quiz) }}">@csrf<button class="rounded-lg border px-3 py-1.5 text-xs">نشر</button></form>
        @endif
        @if (Route::has('admin.quizzes.unpublish'))
            <form method="POST" action="{{ route('admin.quizzes.unpublish', $quiz) }}">@csrf<button class="rounded-lg border px-3 py-1.5 text-xs">إلغاء النشر</button></form>
        @endif
        @if (Route::has('admin.quizzes.duplicate'))
            <form method="POST" action="{{ route('admin.quizzes.duplicate', $quiz) }}">@csrf<button class="rounded-lg border px-3 py-1.5 text-xs">نسخ</button></form>
        @endif
        @if (Route::has('admin.quizzes.randomize'))
            <form method="POST" action="{{ route('admin.quizzes.randomize', $quiz) }}">@csrf<button class="rounded-lg border px-3 py-1.5 text-xs">خلط الأسئلة</button></form>
        @endif
    </div>

    <x-admin.tab-nav :tabs="$tabs" class="mb-6" />

    <div class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
        @if ($tab === 'questions')
            <ul class="divide-y divide-slate-100">
                @forelse ($quiz->questions as $question)
                    <li class="py-3">
                        <p class="text-sm font-medium">{{ $question->position }}. {{ $question->body }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ $question->type }} · {{ $question->points }} نقطة · {{ $question->options->count() }} خيارات</p>
                    </li>
                @empty
                    <li class="py-6 text-center text-slate-500">لا أسئلة بعد.</li>
                @endforelse
            </ul>
        @elseif ($tab === 'attempts')
            <p class="text-sm text-slate-500">سجل محاولات الطلاب سيظهر هنا.</p>
        @elseif ($tab === 'statistics')
            <div class="grid gap-4 sm:grid-cols-3">
                <x-admin.kpi-card label="عدد الأسئلة" :value="$quiz->questions->count()" />
                <x-admin.kpi-card label="المدة" :value="$quiz->duration_minutes ?? '—'" />
                <x-admin.kpi-card label="الحالة" :value="$quiz->status === 'published' ? 'منشور' : 'مسودة'" />
            </div>
        @elseif ($tab === 'settings')
            <dl class="grid gap-3 sm:grid-cols-2 text-sm">
                <div><dt class="text-slate-500">المقرر</dt><dd>{{ $quiz->course?->title ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">إظهار الإجابات</dt><dd>{{ $quiz->show_correct_answers ? 'نعم' : 'لا' }}</dd></div>
            </dl>
            @if (Route::has('admin.quizzes.assign-course'))
                <form method="POST" action="{{ route('admin.quizzes.assign-course', $quiz) }}" class="mt-4 rounded-xl border p-4">
                    @csrf
                    <p class="mb-2 text-sm font-medium">ربط بالمقرر</p>
                    <select name="course_id" class="mb-2 w-full rounded-lg border px-3 py-2 text-sm">
                        @foreach (\App\Models\Course::orderBy('title')->get() as $course)
                            <option value="{{ $course->id }}" @selected($quiz->course_id === $course->id)>{{ $course->title }}</option>
                        @endforeach
                    </select>
                    <button class="rounded-lg bg-[var(--color-primary)] px-3 py-1.5 text-xs text-white">حفظ</button>
                </form>
            @endif
            @if (Route::has('admin.quizzes.assign-lesson'))
                <form method="POST" action="{{ route('admin.quizzes.assign-lesson', $quiz) }}" class="mt-4 rounded-xl border p-4">
                    @csrf
                    <p class="mb-2 text-sm font-medium">ربط بدرس</p>
                    <select name="lesson_id" class="mb-2 w-full rounded-lg border px-3 py-2 text-sm">
                        @foreach (\App\Models\Lesson::with('course')->orderBy('title')->get() as $lesson)
                            <option value="{{ $lesson->id }}">{{ $lesson->title }} ({{ $lesson->course?->title }})</option>
                        @endforeach
                    </select>
                    <button class="rounded-lg bg-[var(--color-primary)] px-3 py-1.5 text-xs text-white">ربط</button>
                </form>
            @endif
            <form method="POST" action="{{ route('admin.quizzes.destroy', $quiz) }}" class="mt-4" onsubmit="return confirm('حذف الاختبار؟');">
                @csrf @method('DELETE')
                <button class="rounded-xl bg-rose-700 px-4 py-2 text-sm text-white">حذف الاختبار</button>
            </form>
        @elseif ($tab === 'results')
            <p class="text-sm text-slate-500">نتائج الطلاب مجمّعة.</p>
        @elseif ($tab === 'leaderboard')
            <p class="text-sm text-slate-500">لوحة المتصدرين حسب أعلى الدرجات.</p>
        @endif
    </div>
@endsection
