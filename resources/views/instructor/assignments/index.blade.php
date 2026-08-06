@extends('layouts.instructor')

@section('title', 'الواجبات')
@section('heading', 'الواجبات')
@section('subheading', 'متابعة واجبات مقرراتك وتسليمات الطلاب')

@section('content')
    <div class="mx-auto max-w-6xl space-y-6">
        <section class="rounded-2xl border border-[var(--color-line)] bg-[var(--color-primary-light)]/50 px-5 py-4">
            <p class="text-sm text-[var(--color-text-secondary)]">
                لديك <strong class="text-[var(--color-ink)]">{{ $assignments->count() }}</strong> واجب عبر
                <strong class="text-[var(--color-ink)]">{{ $courses->count() }}</strong> مقرر.
            </p>
        </section>

        @if ($assignments->isEmpty())
            <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-6 py-14 text-center">
                <p class="text-lg font-bold text-[var(--color-ink)]">لا واجبات بعد</p>
                <p class="mx-auto mt-2 max-w-md text-sm text-[var(--color-text-secondary)]">عند إضافة واجبات لمقرراتك من لوحة الإدارة أو أدوات التأليف ستظهر هنا مع عدّ التسليمات.</p>
            </div>
        @else
            <div class="overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
                        <tr>
                            <th class="px-4 py-3.5 text-right">الواجب</th>
                            <th class="px-4 py-3.5 text-right">المقرر</th>
                            <th class="px-4 py-3.5 text-right">الاستحقاق</th>
                            <th class="px-4 py-3.5 text-right">التسليمات</th>
                            <th class="px-4 py-3.5 text-right">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($assignments as $assignment)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3.5">
                                    <p class="font-semibold text-[var(--color-ink)]">{{ $assignment->title }}</p>
                                    @if ($assignment->lesson)
                                        <p class="text-xs text-slate-500">درس: {{ $assignment->lesson->title }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5">{{ $assignment->course?->title }}</td>
                                <td class="px-4 py-3.5 text-slate-600">{{ $assignment->due_at?->translatedFormat('d M Y') ?? '—' }}</td>
                                <td class="px-4 py-3.5 tabular-nums">{{ $assignment->submissions_count }}</td>
                                <td class="px-4 py-3.5"><x-admin.status-badge :status="$assignment->status ?? 'draft'" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
