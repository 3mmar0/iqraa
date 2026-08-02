@extends('layouts.admin')

@section('title', $assignment->title)
@section('heading', $assignment->title)
@section('subheading', $assignment->course?->title)

@section('header-actions')
    <a href="{{ route('admin.assignments.index') }}" class="admin-btn admin-btn-ghost">رجوع</a>
    <a href="{{ route('admin.assignments.edit', $assignment) }}" class="admin-btn admin-btn-primary">تعديل</a>
@endsection

@section('content')
    @php
        $statusLabels = ['draft' => 'مسودة', 'published' => 'منشور', 'archived' => 'مؤرشف'];
    @endphp

    <div class="admin-content-enter space-y-5">
        <div class="grid gap-5 lg:grid-cols-3">
            <div class="admin-panel p-5 sm:p-6 lg:col-span-2">
                <div class="mb-4 flex flex-wrap items-center gap-2">
                    <x-admin.status-badge :status="$assignment->status" :label="$statusLabels[$assignment->status] ?? $assignment->status" />
                    @if ($assignment->due_at)
                        <span class="admin-chip admin-chip-warning">التسليم: {{ $assignment->due_at->format('Y-m-d H:i') }}</span>
                    @endif
                </div>
                <p class="text-sm leading-7 text-slate-600 whitespace-pre-line">{{ $assignment->description ?: 'لا يوجد وصف.' }}</p>
                <dl class="mt-5 grid gap-3 sm:grid-cols-2">
                    @foreach ([
                        ['المقرر', $assignment->course?->title],
                        ['الدرس', $assignment->lesson?->title ?? '—'],
                        ['موعد التسليم', $assignment->due_at?->format('Y-m-d H:i') ?? '—'],
                        ['الحالة', $statusLabels[$assignment->status] ?? $assignment->status],
                    ] as [$label, $value])
                        <div class="rounded-xl border border-slate-100 bg-slate-50/70 px-4 py-3">
                            <dt class="text-xs font-medium text-slate-500">{{ $label }}</dt>
                            <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
            <x-admin.kpi-card label="التسليمات" :value="$assignment->submissions->count()" />
        </div>

        <section class="admin-panel overflow-hidden">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="font-semibold text-slate-900">تسليمات الطلاب</h2>
            </div>
            @if ($assignment->submissions->isEmpty())
                <div class="p-5">
                    <x-admin.empty-state title="لا تسليمات بعد" description="عندما يسلّم الطلاب الواجب ستظهر هنا." />
                </div>
            @else
                <x-admin.data-table class="!rounded-none !border-0 !shadow-none">
                    <thead class="bg-slate-50/90">
                        <tr>
                            <th class="px-4 py-3.5 text-right">الطالب</th>
                            <th class="px-4 py-3.5 text-right">الحالة</th>
                            <th class="px-4 py-3.5 text-right">الدرجة</th>
                            <th class="px-4 py-3.5 text-right">تاريخ التسليم</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($assignment->submissions as $submission)
                            <tr>
                                <td class="px-4 py-3.5 font-medium">{{ $submission->user?->name ?? '—' }}</td>
                                <td class="px-4 py-3.5">{{ $submission->status }}</td>
                                <td class="px-4 py-3.5">{{ $submission->score ?? '—' }}</td>
                                <td class="px-4 py-3.5 text-slate-600">{{ $submission->submitted_at?->format('Y-m-d H:i') ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-admin.data-table>
            @endif
        </section>

        <form method="POST" action="{{ route('admin.assignments.destroy', $assignment) }}" onsubmit="return confirm('حذف الواجب؟');">
            @csrf
            @method('DELETE')
            <button class="admin-btn admin-btn-danger">حذف الواجب</button>
        </form>
    </div>
@endsection
