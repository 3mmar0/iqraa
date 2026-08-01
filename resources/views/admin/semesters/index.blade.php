@extends('layouts.admin')

@section('title', 'الفصول الدراسية')
@section('heading', 'الفصول الدراسية')

@section('header-actions')
    <a href="{{ route('admin.semesters.create', request()->only('academic_year_id')) }}" class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white">فصل جديد</a>
@endsection

@section('content')
    <form method="GET" class="mb-5 flex flex-wrap gap-3 rounded-2xl border bg-white p-4">
        <select name="academic_year_id" class="rounded-xl border px-3 py-2.5 text-sm">
            <option value="">كل السنوات</option>
            @foreach ($years as $year)
                <option value="{{ $year->id }}" @selected(request('academic_year_id') == $year->id)>{{ $year->name }}</option>
            @endforeach
        </select>
        <button class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm text-white">تصفية</button>
    </form>

    <div class="overflow-hidden rounded-2xl border bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-right">الفصل</th>
                    <th class="px-4 py-3 text-right">السنة</th>
                    <th class="px-4 py-3 text-right">الترم</th>
                    <th class="px-4 py-3 text-right">الفترة</th>
                    <th class="px-4 py-3 text-right">حالي</th>
                    <th class="px-4 py-3 text-right">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($semesters as $semester)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $semester->name }}</td>
                        <td class="px-4 py-3">{{ $semester->academicYear?->name }}</td>
                        <td class="px-4 py-3">{{ $semester->term_number ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $semester->starts_on?->format('Y-m-d') ?? '—' }} — {{ $semester->ends_on?->format('Y-m-d') ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $semester->is_current ? 'نعم' : '—' }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.semesters.edit', $semester) }}" class="rounded-lg border px-2 py-1 text-xs">تعديل</a>
                            <form method="POST" action="{{ route('admin.semesters.destroy', $semester) }}" class="inline" onsubmit="return confirm('حذف؟');">@csrf @method('DELETE')<button class="rounded-lg border border-rose-200 px-2 py-1 text-xs text-rose-700">حذف</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500">لا فصول.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if ($semesters->hasPages())
            <div class="border-t px-4 py-3">{{ $semesters->links() }}</div>
        @endif
    </div>
@endsection
