@extends('layouts.admin')

@section('title', 'المقررات')
@section('heading', 'المقررات')
@section('subheading', 'إدارة جميع المقررات وحالات النشر')

@section('header-actions')
    <a href="{{ route('admin.courses.create') }}" class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">مقرر جديد</a>
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

    <x-admin.filter-bar class="mb-5">
        <form method="GET" class="flex w-full flex-wrap items-end gap-3">
            <div class="min-w-[200px] flex-1">
                <label class="mb-1 block text-xs font-medium text-slate-500" for="q">بحث</label>
                <input id="q" type="search" name="q" value="{{ request('q') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            </div>
            <div class="min-w-[140px]">
                <label class="mb-1 block text-xs font-medium text-slate-500" for="status">الحالة</label>
                <select id="status" name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                    <option value="">الكل</option>
                    @foreach ($statusLabels as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[140px]">
                <label class="mb-1 block text-xs font-medium text-slate-500" for="category_id">التصنيف</label>
                <select id="category_id" name="category_id" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                    <option value="">الكل</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm text-white">تصفية</button>
                <a href="{{ route('admin.courses.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm">مسح</a>
            </div>
        </form>
    </x-admin.filter-bar>

    <x-admin.data-table>
        <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
            <tr>
                <th class="px-4 py-3 text-right">المقرر</th>
                <th class="px-4 py-3 text-right">الترم</th>
                <th class="px-4 py-3 text-right">السنة</th>
                <th class="px-4 py-3 text-right">المحاضر</th>
                <th class="px-4 py-3 text-right">السعر</th>
                <th class="px-4 py-3 text-right">الطلاب</th>
                <th class="px-4 py-3 text-right">الدروس</th>
                <th class="px-4 py-3 text-right">الحالة</th>
                <th class="px-4 py-3 text-right">إجراءات</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($courses as $course)
                <tr>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if ($course->image_path)
                                <img src="{{ asset('storage/'.$course->image_path) }}" alt="" class="h-10 w-10 rounded-lg object-cover">
                            @else
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-xs text-slate-400">—</div>
                            @endif
                            <div>
                                <p class="font-medium">{{ $course->title }}</p>
                                @if ($course->category)
                                    <p class="text-xs text-slate-500">{{ $course->category->name }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-slate-600">{{ $course->semester?->name ?? $course->term_label ?? '—' }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $course->academicYear?->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $course->instructor?->name ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $course->price !== null ? number_format((float) $course->price, 2).' ر.س' : '—' }}</td>
                    <td class="px-4 py-3">{{ $course->enrollments_count }}</td>
                    <td class="px-4 py-3">{{ $course->lessons_count }}</td>
                    <td class="px-4 py-3">
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs">{{ $statusLabels[$course->status] ?? $course->status }}</span>
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
                <tr><td colspan="9" class="px-4 py-10 text-center text-slate-500">لا توجد مقررات.</td></tr>
            @endforelse
        </tbody>
    </x-admin.data-table>

    @if ($courses->hasPages())
        <div class="mt-4">{{ $courses->links() }}</div>
    @endif
@endsection
