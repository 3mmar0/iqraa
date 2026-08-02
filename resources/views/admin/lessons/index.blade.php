@extends('layouts.admin')

@section('title', 'الدروس')
@section('heading', 'الدروس')
@section('subheading', 'عرض وإدارة دروس جميع المقررات')

@section('header-actions')
    <a href="{{ route('admin.lessons.create') }}" class="admin-btn admin-btn-primary">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        درس جديد
    </a>
@endsection

@section('content')
    @php
        $statusLabels = ['draft' => 'مسودة', 'published' => 'منشور', 'scheduled' => 'مجدول', 'archived' => 'مؤرشف'];
    @endphp

    <div class="admin-content-enter space-y-5">
        <x-admin.filter-bar>
            <form method="GET" class="grid w-full gap-3 sm:grid-cols-2 lg:grid-cols-5">
                <div class="sm:col-span-2">
                    <label class="admin-label" for="q">بحث</label>
                    <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="بحث بالعنوان..." class="admin-input">
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
                        <option value="">كل الحالات</option>
                        @foreach ($statusLabels as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button class="admin-btn admin-btn-dark">تصفية</button>
                    <a href="{{ route('admin.lessons.index') }}" class="admin-btn admin-btn-ghost">مسح</a>
                </div>
            </form>
        </x-admin.filter-bar>

        @if ($lessons->isEmpty())
            <x-admin.empty-state title="لا توجد دروس" description="أضف درساً جديداً أو افتح مقرراً وأضف دروساً من داخله.">
                <x-slot:actions>
                    <a href="{{ route('admin.lessons.create') }}" class="admin-btn admin-btn-primary">درس جديد</a>
                </x-slot:actions>
            </x-admin.empty-state>
        @else
            <x-admin.data-table>
                <thead class="bg-slate-50/90">
                    <tr>
                        <th class="px-4 py-3.5 text-right">الدرس</th>
                        <th class="px-4 py-3.5 text-right">المقرر</th>
                        <th class="px-4 py-3.5 text-right">الترتيب</th>
                        <th class="px-4 py-3.5 text-right">الاختبار</th>
                        <th class="px-4 py-3.5 text-right">النشر</th>
                        <th class="px-4 py-3.5 text-right">القفل</th>
                        <th class="px-4 py-3.5 text-right">الحالة</th>
                        <th class="px-4 py-3.5 text-right">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($lessons as $lesson)
                        <tr>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="admin-entity-thumb text-[0.65rem]">{{ $lesson->position }}</div>
                                    <div class="min-w-0">
                                        @if (Route::has('admin.lessons.show'))
                                            <a href="{{ route('admin.lessons.show', $lesson) }}" class="block truncate font-semibold text-slate-900 hover:text-[var(--color-primary)]">{{ $lesson->title }}</a>
                                        @else
                                            <p class="truncate font-semibold text-slate-900">{{ $lesson->title }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-slate-600">{{ $lesson->course?->title }}</td>
                            <td class="px-4 py-3.5">{{ $lesson->position }}</td>
                            <td class="px-4 py-3.5 text-slate-600">{{ $lesson->quiz?->title ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-xs text-slate-500">{{ $lesson->published_at?->format('Y-m-d H:i') ?? '—' }}</td>
                            <td class="px-4 py-3.5">
                                @if ($lesson->is_locked)
                                    <span class="admin-chip admin-chip-warning">مقفل</span>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                <x-admin.status-badge :status="$lesson->status" :label="$statusLabels[$lesson->status] ?? $lesson->status" />
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex flex-wrap gap-1.5">
                                    @if (Route::has('admin.lessons.show'))
                                        <a href="{{ route('admin.lessons.show', $lesson) }}" class="admin-btn admin-btn-ghost admin-btn-sm">عرض</a>
                                    @endif
                                    <a href="{{ route('admin.lessons.edit', $lesson) }}" class="admin-btn admin-btn-ghost admin-btn-sm">تعديل</a>
                                    <form method="POST" action="{{ route('admin.lessons.destroy', $lesson) }}" onsubmit="return confirm('حذف الدرس؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="admin-btn admin-btn-danger admin-btn-sm">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-admin.data-table>

            @if ($lessons->hasPages())
                <div class="mt-1">{{ $lessons->links() }}</div>
            @endif
        @endif
    </div>
@endsection
