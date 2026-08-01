@extends('layouts.admin')

@section('title', 'الدروس')
@section('heading', 'الدروس')
@section('subheading', 'عرض وإدارة دروس جميع المقررات')

@section('header-actions')
    <a href="{{ route('admin.lessons.create') }}" class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white">درس جديد</a>
@endsection

@section('content')
    <form method="GET" class="mb-5 grid gap-3 rounded-2xl border border-[var(--color-line)] bg-white p-4 sm:grid-cols-4">
        <div class="sm:col-span-2">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="بحث بالعنوان..." class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
        </div>
        <div>
            <select name="course_id" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                <option value="">كل المقررات</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected((string) request('course_id') === (string) $course->id)>{{ $course->title }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                <option value="">كل الحالات</option>
                <option value="draft" @selected(request('status') === 'draft')>مسودة</option>
                <option value="published" @selected(request('status') === 'published')>منشور</option>
                <option value="archived" @selected(request('status') === 'archived')>مؤرشف</option>
            </select>
        </div>
        <div class="sm:col-span-4 flex gap-2">
            <button class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm text-white">تصفية</button>
            <a href="{{ route('admin.lessons.index') }}" class="rounded-xl border px-4 py-2.5 text-sm">مسح</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-right">الدرس</th>
                    <th class="px-4 py-3 text-right">المقرر</th>
                    <th class="px-4 py-3 text-right">الترتيب</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($lessons as $lesson)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $lesson->title }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $lesson->course?->title }}</td>
                        <td class="px-4 py-3">{{ $lesson->position }}</td>
                        <td class="px-4 py-3">{{ $lesson->status }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.lessons.edit', $lesson) }}" class="rounded-lg border px-3 py-1.5 text-xs">تعديل</a>
                                <form method="POST" action="{{ route('admin.lessons.destroy', $lesson) }}" onsubmit="return confirm('حذف الدرس؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded-lg border border-rose-200 px-3 py-1.5 text-xs text-rose-700">حذف</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-slate-500">لا توجد دروس.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if ($lessons->hasPages())
            <div class="border-t px-4 py-3">{{ $lessons->links() }}</div>
        @endif
    </div>
@endsection
