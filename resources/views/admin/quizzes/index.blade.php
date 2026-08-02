@extends('layouts.admin')

@section('title', 'الاختبارات')
@section('heading', 'الاختبارات')
@section('subheading', 'إدارة الاختبارات والأسئلة')

@section('header-actions')
    <a href="{{ route('admin.quizzes.create') }}" class="admin-btn admin-btn-primary">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        اختبار جديد
    </a>
@endsection

@section('content')
    <div class="admin-content-enter space-y-5">
        <x-admin.filter-bar>
            <form method="GET" class="grid w-full gap-3 sm:grid-cols-2 lg:grid-cols-5">
                <div class="sm:col-span-2">
                    <label class="admin-label" for="q">بحث</label>
                    <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="بحث..." class="admin-input">
                </div>
                <div>
                    <label class="admin-label" for="course_id">المقرر</label>
                    <select id="course_id" name="course_id" class="admin-input">
                        <option value="">كل المقررات</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @selected((string) request('course_id') === (string) $course->id)>{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="admin-label" for="status">الحالة</label>
                    <select id="status" name="status" class="admin-input">
                        <option value="">الكل</option>
                        <option value="draft" @selected(request('status') === 'draft')>مسودة</option>
                        <option value="published" @selected(request('status') === 'published')>منشور</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button class="admin-btn admin-btn-dark">تصفية</button>
                    <a href="{{ route('admin.quizzes.index') }}" class="admin-btn admin-btn-ghost">مسح</a>
                </div>
            </form>
        </x-admin.filter-bar>

        @if ($quizzes->isEmpty())
            <x-admin.empty-state title="لا اختبارات بعد" description="أنشئ اختباراً واربطه بمقرر أو درس ثم أضف الأسئلة.">
                <x-slot:actions>
                    <a href="{{ route('admin.quizzes.create') }}" class="admin-btn admin-btn-primary">اختبار جديد</a>
                </x-slot:actions>
            </x-admin.empty-state>
        @else
            <x-admin.data-table>
                <thead class="bg-slate-50/90">
                    <tr>
                        <th class="px-4 py-3.5 text-right">الاختبار</th>
                        <th class="px-4 py-3.5 text-right">المقرر</th>
                        <th class="px-4 py-3.5 text-right">الأسئلة</th>
                        <th class="px-4 py-3.5 text-right">المدة (د)</th>
                        <th class="px-4 py-3.5 text-right">الحالة</th>
                        <th class="px-4 py-3.5 text-right">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($quizzes as $quiz)
                        <tr>
                            <td class="px-4 py-3.5">
                                <a href="{{ route('admin.quizzes.show', $quiz) }}" class="font-semibold text-slate-900 hover:text-[var(--color-primary)]">{{ $quiz->title }}</a>
                            </td>
                            <td class="px-4 py-3.5 text-slate-600">{{ $quiz->course?->title ?? '—' }}</td>
                            <td class="px-4 py-3.5">
                                <span class="inline-flex min-w-[2rem] justify-center rounded-lg bg-indigo-50 px-2 py-1 text-xs font-semibold text-indigo-700">{{ $quiz->questions_count }}</span>
                            </td>
                            <td class="px-4 py-3.5 text-slate-600">{{ $quiz->duration_minutes ?? '—' }}</td>
                            <td class="px-4 py-3.5">
                                <x-admin.status-badge :status="$quiz->status" :label="$quiz->status === 'published' ? 'منشور' : 'مسودة'" />
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex flex-wrap gap-1.5">
                                    <a href="{{ route('admin.quizzes.show', $quiz) }}" class="admin-btn admin-btn-ghost admin-btn-sm">عرض</a>
                                    <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="admin-btn admin-btn-ghost admin-btn-sm">تعديل</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-admin.data-table>

            @if ($quizzes->hasPages())
                <div class="mt-1">{{ $quizzes->links() }}</div>
            @endif
        @endif
    </div>
@endsection
