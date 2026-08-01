@extends('layouts.admin')

@section('title', 'الاختبارات')
@section('heading', 'الاختبارات')
@section('subheading', 'إدارة الاختبارات والأسئلة')

@section('header-actions')
    <a href="{{ route('admin.quizzes.create') }}" class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white">اختبار جديد</a>
@endsection

@section('content')
    <x-admin.filter-bar class="mb-5">
        <form method="GET" class="flex w-full flex-wrap items-end gap-3">
            <div class="min-w-[200px] flex-1">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="بحث..." class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            </div>
            <div class="min-w-[160px]">
                <select name="course_id" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                    <option value="">كل المقررات</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" @selected((string) request('course_id') === (string) $course->id)>{{ $course->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[120px]">
                <select name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                    <option value="">الكل</option>
                    <option value="draft" @selected(request('status') === 'draft')>مسودة</option>
                    <option value="published" @selected(request('status') === 'published')>منشور</option>
                </select>
            </div>
            <button class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm text-white">تصفية</button>
        </form>
    </x-admin.filter-bar>

    <x-admin.data-table>
        <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
            <tr>
                <th class="px-4 py-3 text-right">الاختبار</th>
                <th class="px-4 py-3 text-right">المقرر</th>
                <th class="px-4 py-3 text-right">الأسئلة</th>
                <th class="px-4 py-3 text-right">المدة (د)</th>
                <th class="px-4 py-3 text-right">الحالة</th>
                <th class="px-4 py-3 text-right">إجراءات</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($quizzes as $quiz)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $quiz->title }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $quiz->course?->title ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $quiz->questions_count }}</td>
                    <td class="px-4 py-3">{{ $quiz->duration_minutes ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $quiz->status === 'published' ? 'منشور' : 'مسودة' }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.quizzes.show', $quiz) }}" class="rounded-lg border px-3 py-1.5 text-xs">عرض</a>
                            <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="rounded-lg border px-3 py-1.5 text-xs">تعديل</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500">لا اختبارات.</td></tr>
            @endforelse
        </tbody>
    </x-admin.data-table>

    @if ($quizzes->hasPages())
        <div class="mt-4">{{ $quizzes->links() }}</div>
    @endif
@endsection
