@extends('layouts.admin')

@section('title', 'المقررات')
@section('heading', 'المقررات')
@section('subheading', 'إدارة جميع المقررات وحالات النشر')

@section('header-actions')
    <a href="{{ route('admin.courses.create') }}" class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-800">مقرر جديد</a>
@endsection

@section('content')
    <form method="GET" class="mb-5 grid gap-3 rounded-2xl border border-[var(--color-line)] bg-white p-4 sm:grid-cols-3">
        <div class="sm:col-span-2">
            <label class="mb-1 block text-xs font-medium text-slate-500" for="q">بحث</label>
            <input id="q" type="search" name="q" value="{{ request('q') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500" for="status">الحالة</label>
            <select id="status" name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                <option value="">الكل</option>
                <option value="draft" @selected(request('status') === 'draft')>مسودة</option>
                <option value="published" @selected(request('status') === 'published')>منشور</option>
                <option value="archived" @selected(request('status') === 'archived')>مؤرشف</option>
            </select>
        </div>
        <div class="sm:col-span-3 flex gap-2">
            <button class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm text-white">تصفية</button>
            <a href="{{ route('admin.courses.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm">مسح</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-right">المقرر</th>
                    <th class="px-4 py-3 text-right">المحاضر</th>
                    <th class="px-4 py-3 text-right">الدروس</th>
                    <th class="px-4 py-3 text-right">الملتحقون</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($courses as $course)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $course->title }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $course->instructor?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $course->lessons_count }}</td>
                        <td class="px-4 py-3">{{ $course->enrollments_count }}</td>
                        <td class="px-4 py-3">
                            @php
                                $labels = ['draft' => 'مسودة', 'published' => 'منشور', 'archived' => 'مؤرشف'];
                            @endphp
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs">{{ $labels[$course->status] ?? $course->status }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.courses.show', $course) }}" class="rounded-lg border px-3 py-1.5 text-xs">عرض</a>
                                <a href="{{ route('admin.courses.edit', $course) }}" class="rounded-lg border px-3 py-1.5 text-xs">تعديل</a>
                                <a href="{{ route('admin.lessons.index', ['course_id' => $course->id]) }}" class="rounded-lg border px-3 py-1.5 text-xs">الدروس</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500">لا توجد مقررات.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if ($courses->hasPages())
            <div class="border-t px-4 py-3">{{ $courses->links() }}</div>
        @endif
    </div>
@endsection
