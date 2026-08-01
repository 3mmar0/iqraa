@extends('layouts.admin')

@section('title', 'الدروس')
@section('heading', 'الدروس')
@section('subheading', 'عرض وإدارة دروس جميع المقررات')

@section('header-actions')
    <a href="{{ route('admin.lessons.create') }}" class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white">درس جديد</a>
@endsection

@section('content')
    @php
        $statusLabels = ['draft' => 'مسودة', 'published' => 'منشور', 'scheduled' => 'مجدول', 'archived' => 'مؤرشف'];
    @endphp

    <x-admin.filter-bar class="mb-5">
        <form method="GET" class="flex w-full flex-wrap items-end gap-3">
            <div class="min-w-[200px] flex-1">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="بحث بالعنوان..." class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
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
                    <option value="">كل الحالات</option>
                    @foreach ($statusLabels as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm text-white">تصفية</button>
                <a href="{{ route('admin.lessons.index') }}" class="rounded-xl border px-4 py-2.5 text-sm">مسح</a>
            </div>
        </form>
    </x-admin.filter-bar>

    <x-admin.data-table>
        <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
            <tr>
                <th class="px-4 py-3 text-right">الدرس</th>
                <th class="px-4 py-3 text-right">المقرر</th>
                <th class="px-4 py-3 text-right">الترتيب</th>
                <th class="px-4 py-3 text-right">الاختبار</th>
                <th class="px-4 py-3 text-right">النشر</th>
                <th class="px-4 py-3 text-right">القفل</th>
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
                    <td class="px-4 py-3 text-slate-600">{{ $lesson->quiz?->title ?? '—' }}</td>
                    <td class="px-4 py-3 text-xs">{{ $lesson->published_at?->format('Y-m-d H:i') ?? '—' }}</td>
                    <td class="px-4 py-3">
                        @if ($lesson->is_locked)
                            <span class="rounded bg-amber-100 px-2 py-0.5 text-xs text-amber-800">مقفل</span>
                        @else
                            <span class="text-slate-400">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $statusLabels[$lesson->status] ?? $lesson->status }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-2">
                            @if (Route::has('admin.lessons.show'))
                                <a href="{{ route('admin.lessons.show', $lesson) }}" class="rounded-lg border px-3 py-1.5 text-xs">عرض</a>
                            @endif
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
                <tr><td colspan="8" class="px-4 py-10 text-center text-slate-500">لا توجد دروس.</td></tr>
            @endforelse
        </tbody>
    </x-admin.data-table>

    @if ($lessons->hasPages())
        <div class="mt-4">{{ $lessons->links() }}</div>
    @endif
@endsection
