@extends('layouts.admin')

@section('title', 'المعلمون')
@section('heading', 'المعلمون')
@section('subheading', 'إدارة حسابات المحاضرين وتعيين المقررات')

@section('header-actions')
    <a href="{{ route('admin.teachers.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[var(--color-primary-hover)]">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        معلم جديد
    </a>
@endsection

@section('content')
    <x-admin.filter-bar class="mb-5">
        <form method="GET" action="{{ route('admin.teachers.index') }}" class="grid w-full gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div class="sm:col-span-2">
                <label class="mb-1 block text-xs font-medium text-slate-500" for="q">بحث</label>
                <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="الاسم، البريد، أو الهاتف..."
                       class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500" for="status">الحالة</label>
                <select id="status" name="status" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm">
                    <option value="">الكل</option>
                    <option value="active" @selected(request('status') === 'active')>نشط</option>
                    <option value="invited" @selected(request('status') === 'invited')>مدعو</option>
                    <option value="disabled" @selected(request('status') === 'disabled')>معطّل</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-slate-800">تطبيق</button>
                <a href="{{ route('admin.teachers.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50">مسح</a>
            </div>
        </form>
    </x-admin.filter-bar>

    @if ($teachers->isEmpty())
        <x-admin.empty-state title="لا يوجد معلمون" description="أضف محاضراً جديداً أو عدّل عوامل التصفية.">
            <x-slot:actions>
                <a href="{{ route('admin.teachers.create') }}" class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white">معلم جديد</a>
            </x-slot:actions>
        </x-admin.empty-state>
    @else
        <div class="overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_10px_28px_-22px_rgba(47,58,69,0.45)]">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50/90 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-4 py-3.5">المعلم</th>
                            <th class="px-4 py-3.5">التواصل</th>
                            <th class="px-4 py-3.5">المقررات</th>
                            <th class="px-4 py-3.5">الحالة</th>
                            <th class="px-4 py-3.5">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($teachers as $teacher)
                            @php
                                $courseCount = $teacher->instructed_courses_count ?? $teacher->instructedCourses->count();
                            @endphp
                            <tr class="transition hover:bg-[var(--color-sand)]/70">
                                <td class="px-4 py-3.5">
                                    <a href="{{ route('admin.teachers.show', $teacher) }}" class="flex items-center gap-3">
                                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[var(--color-secondary-light)] text-sm font-bold text-[var(--color-secondary-hover)]">
                                            {{ mb_substr($teacher->name, 0, 1) }}
                                        </span>
                                        <span class="min-w-0">
                                            <span class="block truncate font-semibold text-[var(--color-ink)]">{{ $teacher->name }}</span>
                                            <span class="block text-xs text-slate-500">#{{ $teacher->id }}</span>
                                        </span>
                                    </a>
                                </td>
                                <td class="px-4 py-3.5">
                                    <p class="truncate text-[var(--color-ink)]">{{ $teacher->email }}</p>
                                    <p class="mt-0.5 text-xs text-slate-500">{{ $teacher->phone ?: 'بدون هاتف' }}</p>
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-flex min-w-8 items-center justify-center rounded-lg bg-[var(--color-primary-light)] px-2 py-1 text-xs font-semibold text-[var(--color-primary-hover)]">
                                        {{ $courseCount }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <x-admin.status-badge :status="$teacher->status" />
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex flex-wrap gap-1.5">
                                        <a href="{{ route('admin.teachers.show', $teacher) }}" class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">عرض</a>
                                        <a href="{{ route('admin.teachers.edit', $teacher) }}" class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">تعديل</a>
                                        @if ($teacher->id !== auth()->id() && $teacher->status === 'active' && ! $teacher->hasRole('super_admin') && Route::has('admin.users.impersonate'))
                                            <form method="POST" action="{{ route('admin.users.impersonate', $teacher) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="rounded-lg border border-amber-200 bg-amber-50 px-2.5 py-1.5 text-xs font-medium text-amber-900 hover:bg-amber-100">دخول كـ</button>
                                            </form>
                                        @endif
                                        @if ($teacher->status !== 'disabled')
                                            <form method="POST" action="{{ route('admin.teachers.suspend', $teacher) }}" class="inline" onsubmit="return confirm('تعليق حساب هذا المعلم؟');">
                                                @csrf
                                                <button type="submit" class="rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-xs font-medium text-rose-800 hover:bg-rose-100">تعليق</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.teachers.activate', $teacher) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1.5 text-xs font-medium text-emerald-800 hover:bg-emerald-100">تفعيل</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($teachers->hasPages())
                <div class="border-t border-[var(--color-line)] px-4 py-3">{{ $teachers->links() }}</div>
            @endif
        </div>
    @endif
@endsection
