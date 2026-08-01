@extends('layouts.admin')

@section('title', $assignment->title)
@section('heading', $assignment->title)
@section('subheading', $assignment->course?->title)

@section('header-actions')
    <a href="{{ route('admin.assignments.edit', $assignment) }}" class="rounded-xl border bg-white px-4 py-2.5 text-sm">تعديل</a>
@endsection

@section('content')
    <div class="mb-6 grid gap-4 lg:grid-cols-3">
        <div class="rounded-2xl border border-[var(--color-line)] bg-white p-5 lg:col-span-2">
            <p class="text-sm text-slate-600 whitespace-pre-line">{{ $assignment->description ?: 'لا يوجد وصف.' }}</p>
            <dl class="mt-4 grid gap-3 sm:grid-cols-2 text-sm">
                <div><dt class="text-slate-500">المقرر</dt><dd class="font-medium">{{ $assignment->course?->title }}</dd></div>
                <div><dt class="text-slate-500">الدرس</dt><dd class="font-medium">{{ $assignment->lesson?->title ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">موعد التسليم</dt><dd class="font-medium">{{ $assignment->due_at?->format('Y-m-d H:i') }}</dd></div>
                <div><dt class="text-slate-500">الحالة</dt><dd class="font-medium">{{ $assignment->status }}</dd></div>
            </dl>
        </div>
        <x-admin.kpi-card label="التسليمات" :value="$assignment->submissions->count()" />
    </div>

    <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
        <h2 class="mb-3 font-semibold">تسليمات الطلاب</h2>
        <x-admin.data-table>
            <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-right">الطالب</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right">الدرجة</th>
                    <th class="px-4 py-3 text-right">تاريخ التسليم</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($assignment->submissions as $submission)
                    <tr>
                        <td class="px-4 py-3">{{ $submission->user?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $submission->status }}</td>
                        <td class="px-4 py-3">{{ $submission->score ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $submission->submitted_at?->format('Y-m-d H:i') ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-10 text-center text-slate-500">لا تسليمات بعد.</td></tr>
                @endforelse
            </tbody>
        </x-admin.data-table>
    </section>

    <form method="POST" action="{{ route('admin.assignments.destroy', $assignment) }}" class="mt-6" onsubmit="return confirm('حذف الواجب؟');">
        @csrf
        @method('DELETE')
        <button class="rounded-xl bg-rose-700 px-4 py-2 text-sm text-white">حذف الواجب</button>
    </form>
@endsection
