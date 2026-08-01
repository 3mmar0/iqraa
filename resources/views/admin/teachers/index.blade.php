@extends('layouts.admin')

@section('title', 'المعلمون')
@section('heading', 'المعلمون')
@section('subheading', 'إدارة حسابات المحاضرين')

@section('header-actions')
    <a href="{{ route('admin.teachers.create') }}" class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white">معلم جديد</a>
@endsection

@section('content')
    <form method="GET" class="mb-5 grid gap-3 rounded-2xl border border-[var(--color-line)] bg-white p-4 sm:grid-cols-3">
        <div class="sm:col-span-2">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="اسم أو بريد..." class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
        </div>
        <div>
            <select name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                <option value="">كل الحالات</option>
                @foreach (['active' => 'نشط', 'invited' => 'مدعو', 'disabled' => 'معطل'] as $val => $label)
                    <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="sm:col-span-3 flex gap-2">
            <button class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm text-white">تصفية</button>
            <a href="{{ route('admin.teachers.index') }}" class="rounded-xl border px-4 py-2.5 text-sm">مسح</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-right">الاسم</th>
                    <th class="px-4 py-3 text-right">البريد</th>
                    <th class="px-4 py-3 text-right">المقررات</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($teachers as $teacher)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $teacher->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $teacher->email }}</td>
                        <td class="px-4 py-3">{{ $teacher->instructed_courses_count ?? $teacher->instructedCourses->count() }}</td>
                        <td class="px-4 py-3">{{ $teacher->status }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                <a href="{{ route('admin.teachers.show', $teacher) }}" class="rounded-lg border px-2 py-1 text-xs">عرض</a>
                                <a href="{{ route('admin.teachers.edit', $teacher) }}" class="rounded-lg border px-2 py-1 text-xs">تعديل</a>
                                @if ($teacher->status !== 'disabled')
                                    <form method="POST" action="{{ route('admin.teachers.suspend', $teacher) }}" class="inline">@csrf<button class="rounded-lg border px-2 py-1 text-xs">تعليق</button></form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-slate-500">لا معلمين.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if ($teachers->hasPages())
            <div class="border-t px-4 py-3">{{ $teachers->links() }}</div>
        @endif
    </div>
@endsection
