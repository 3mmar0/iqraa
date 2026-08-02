@extends('layouts.admin')

@section('title', 'الواجبات')
@section('heading', 'الواجبات')
@section('subheading', 'إدارة الواجبات وتسليمات الطلاب')

@section('header-actions')
    <a href="{{ route('admin.assignments.create') }}" class="admin-btn admin-btn-primary">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        واجب جديد
    </a>
@endsection

@section('content')
    @php
        $statusLabels = ['draft' => 'مسودة', 'published' => 'منشور', 'archived' => 'مؤرشف'];
    @endphp

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
                        @foreach ($statusLabels as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button class="admin-btn admin-btn-dark">تصفية</button>
                    <a href="{{ route('admin.assignments.index') }}" class="admin-btn admin-btn-ghost">مسح</a>
                </div>
            </form>
        </x-admin.filter-bar>

        @if ($assignments->isEmpty())
            <x-admin.empty-state title="لا واجبات بعد" description="أنشئ واجباً واربطه بمقرر أو درس لمتابعة التسليمات.">
                <x-slot:actions>
                    <a href="{{ route('admin.assignments.create') }}" class="admin-btn admin-btn-primary">واجب جديد</a>
                </x-slot:actions>
            </x-admin.empty-state>
        @else
            <x-admin.data-table>
                <thead class="bg-slate-50/90">
                    <tr>
                        <th class="px-4 py-3.5 text-right">الواجب</th>
                        <th class="px-4 py-3.5 text-right">المقرر</th>
                        <th class="px-4 py-3.5 text-right">الدرس</th>
                        <th class="px-4 py-3.5 text-right">موعد التسليم</th>
                        <th class="px-4 py-3.5 text-right">التسليمات</th>
                        <th class="px-4 py-3.5 text-right">الحالة</th>
                        <th class="px-4 py-3.5 text-right">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($assignments as $assignment)
                        <tr>
                            <td class="px-4 py-3.5">
                                <a href="{{ route('admin.assignments.show', $assignment) }}" class="font-semibold text-slate-900 hover:text-[var(--color-primary)]">{{ $assignment->title }}</a>
                            </td>
                            <td class="px-4 py-3.5 text-slate-600">{{ $assignment->course?->title }}</td>
                            <td class="px-4 py-3.5 text-slate-600">{{ $assignment->lesson?->title ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-sm text-slate-600">{{ $assignment->due_at?->format('Y-m-d H:i') ?? '—' }}</td>
                            <td class="px-4 py-3.5">
                                <span class="inline-flex min-w-[2rem] justify-center rounded-lg bg-amber-50 px-2 py-1 text-xs font-semibold text-amber-800">{{ $assignment->submissions_count }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <x-admin.status-badge :status="$assignment->status" :label="$statusLabels[$assignment->status] ?? $assignment->status" />
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex flex-wrap gap-1.5">
                                    <a href="{{ route('admin.assignments.show', $assignment) }}" class="admin-btn admin-btn-ghost admin-btn-sm">عرض</a>
                                    <a href="{{ route('admin.assignments.edit', $assignment) }}" class="admin-btn admin-btn-ghost admin-btn-sm">تعديل</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-admin.data-table>

            @if ($assignments->hasPages())
                <div class="mt-1">{{ $assignments->links() }}</div>
            @endif
        @endif
    </div>
@endsection
