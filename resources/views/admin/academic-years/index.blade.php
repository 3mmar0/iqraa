@extends('layouts.admin')

@section('title', 'السنوات الدراسية')
@section('heading', 'السنوات الدراسية')

@section('header-actions')
    <a href="{{ route('admin.academic-years.create') }}" class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white">سنة جديدة</a>
@endsection

@section('content')
    <div class="overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-right">الاسم</th>
                    <th class="px-4 py-3 text-right">الفترة</th>
                    <th class="px-4 py-3 text-right">الفصول</th>
                    <th class="px-4 py-3 text-right">المجموعات</th>
                    <th class="px-4 py-3 text-right">حالية</th>
                    <th class="px-4 py-3 text-right">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($years as $year)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $year->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $year->starts_on?->format('Y-m-d') ?? '—' }} — {{ $year->ends_on?->format('Y-m-d') ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $year->semesters_count }}</td>
                        <td class="px-4 py-3">{{ $year->groups_count }}</td>
                        <td class="px-4 py-3">{{ $year->is_current ? 'نعم' : '—' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-1">
                                <a href="{{ route('admin.semesters.index', ['academic_year_id' => $year->id]) }}" class="rounded-lg border px-2 py-1 text-xs">الفصول</a>
                                <a href="{{ route('admin.academic-years.edit', $year) }}" class="rounded-lg border px-2 py-1 text-xs">تعديل</a>
                                <form method="POST" action="{{ route('admin.academic-years.destroy', $year) }}" class="inline" onsubmit="return confirm('حذف؟');">@csrf @method('DELETE')<button class="rounded-lg border border-rose-200 px-2 py-1 text-xs text-rose-700">حذف</button></form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500">لا سنوات.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if ($years->hasPages())
            <div class="border-t px-4 py-3">{{ $years->links() }}</div>
        @endif
    </div>
@endsection
