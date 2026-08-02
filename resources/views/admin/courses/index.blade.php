@extends('layouts.admin')

@section('title', 'المقررات')
@section('heading', 'المقررات')
@section('subheading', 'إدارة جميع المقررات وحالات النشر')

@section('header-actions')
    <a href="{{ route('admin.courses.create') }}" class="admin-btn admin-btn-primary">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        مقرر جديد
    </a>
@endsection

@section('content')
    @php
        $statusLabels = [
            'draft' => 'مسودة',
            'published' => 'منشور',
            'archived' => 'مؤرشف',
            'hidden' => 'مخفي',
        ];
    @endphp

    <div class="admin-content-enter space-y-5">
        <x-admin.filter-bar>
            <form method="GET" class="grid w-full gap-3 sm:grid-cols-2 lg:grid-cols-5">
                <div class="sm:col-span-2">
                    <label class="admin-label" for="q">بحث</label>
                    <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="عنوان المقرر..." class="admin-input">
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
                <div>
                    <label class="admin-label" for="category_id">التصنيف</label>
                    <select id="category_id" name="category_id" class="admin-input">
                        <option value="">الكل</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button class="admin-btn admin-btn-dark">تصفية</button>
                    <a href="{{ route('admin.courses.index') }}" class="admin-btn admin-btn-ghost">مسح</a>
                </div>
            </form>
        </x-admin.filter-bar>

        @if ($courses->isEmpty())
            <x-admin.empty-state title="لا توجد مقررات" description="أنشئ مقرراً جديداً وابدأ بإضافة الدروس والمحتوى.">
                <x-slot:actions>
                    <a href="{{ route('admin.courses.create') }}" class="admin-btn admin-btn-primary">مقرر جديد</a>
                </x-slot:actions>
            </x-admin.empty-state>
        @else
            <x-admin.data-table>
                <thead class="bg-slate-50/90">
                    <tr>
                        <th class="px-4 py-3.5 text-right">المقرر</th>
                        <th class="px-4 py-3.5 text-right">الترم</th>
                        <th class="px-4 py-3.5 text-right">السنة</th>
                        <th class="px-4 py-3.5 text-right">المحاضر</th>
                        <th class="px-4 py-3.5 text-right">السعر</th>
                        <th class="px-4 py-3.5 text-right">الطلاب</th>
                        <th class="px-4 py-3.5 text-right">الدروس</th>
                        <th class="px-4 py-3.5 text-right">الحالة</th>
                        <th class="px-4 py-3.5 text-right">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($courses as $course)
                        <tr>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="admin-entity-thumb">
                                        @if ($course->image_path)
                                            <img src="{{ asset('storage/'.$course->image_path) }}" alt="">
                                        @else
                                            {{ mb_substr($course->title, 0, 1) }}
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.courses.show', $course) }}" class="block truncate font-semibold text-slate-900 hover:text-[var(--color-primary)]">{{ $course->title }}</a>
                                        @if ($course->category)
                                            <p class="mt-0.5 truncate text-xs text-slate-500">{{ $course->category->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-slate-600">{{ $course->semester?->name ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-slate-600">{{ $course->academicYear?->name ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-slate-600">{{ $course->instructor?->name ?? '—' }}</td>
                            <td class="px-4 py-3.5 font-medium">{{ $course->price !== null ? number_format((float) $course->price, 2).' ر.س' : '—' }}</td>
                            <td class="px-4 py-3.5">
                                <span class="inline-flex min-w-[2rem] justify-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700">{{ $course->enrollments_count }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="inline-flex min-w-[2rem] justify-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700">{{ $course->lessons_count }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <x-admin.status-badge :status="$course->status" :label="$statusLabels[$course->status] ?? $course->status" />
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex flex-wrap gap-1.5">
                                    <a href="{{ route('admin.courses.show', $course) }}" class="admin-btn admin-btn-ghost admin-btn-sm">عرض</a>
                                    <a href="{{ route('admin.courses.edit', $course) }}" class="admin-btn admin-btn-ghost admin-btn-sm">تعديل</a>
                                    <a href="{{ route('admin.lessons.index', ['course_id' => $course->id]) }}" class="admin-btn admin-btn-ghost admin-btn-sm">الدروس</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-admin.data-table>

            @if ($courses->hasPages())
                <div class="mt-1">{{ $courses->links() }}</div>
            @endif
        @endif
    </div>
@endsection
