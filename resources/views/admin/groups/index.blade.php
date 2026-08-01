@extends('layouts.admin')

@section('title', 'المجموعات')
@section('heading', 'المجموعات')

@section('header-actions')
    <a href="{{ route('admin.groups.create') }}" class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white">مجموعة جديدة</a>
@endsection

@section('content')
    <form method="GET" class="mb-5 flex flex-wrap gap-3 rounded-2xl border bg-white p-4">
        <select name="academic_year_id" class="rounded-xl border px-3 py-2.5 text-sm">
            <option value="">كل السنوات</option>
            @foreach ($years as $year)
                <option value="{{ $year->id }}" @selected(request('academic_year_id') == $year->id)>{{ $year->name }}</option>
            @endforeach
        </select>
        <select name="status" class="rounded-xl border px-3 py-2.5 text-sm">
            <option value="">كل الحالات</option>
            @foreach (['active', 'inactive', 'archived'] as $st)
                <option value="{{ $st }}" @selected(request('status') === $st)>{{ $st }}</option>
            @endforeach
        </select>
        <button class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm text-white">تصفية</button>
    </form>

    <div class="overflow-hidden rounded-2xl border bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-right">المجموعة</th>
                    <th class="px-4 py-3 text-right">السنة</th>
                    <th class="px-4 py-3 text-right">الفصل</th>
                    <th class="px-4 py-3 text-right">الأعضاء</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($groups as $group)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $group->name }}</td>
                        <td class="px-4 py-3">{{ $group->academicYear?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $group->semester?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $group->users_count }}</td>
                        <td class="px-4 py-3">{{ $group->status }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.groups.show', $group) }}" class="rounded-lg border px-2 py-1 text-xs">عرض</a>
                            <a href="{{ route('admin.groups.edit', $group) }}" class="rounded-lg border px-2 py-1 text-xs">تعديل</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500">لا مجموعات.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if ($groups->hasPages())
            <div class="border-t px-4 py-3">{{ $groups->links() }}</div>
        @endif
    </div>
@endsection
