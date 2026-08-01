@extends('layouts.admin')

@section('title', 'التقارير')
@section('heading', 'التقارير')
@section('subheading', 'إنشاء وتنزيل تقارير المنصة')

@section('content')
    @if (session('status'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
            {{ session('status') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900">
            {{ session('error') }}
        </div>
    @endif

    <x-admin.page-header title="أنواع التقارير" subtitle="اختر نوع التقرير والصيغة ثم أضفه إلى قائمة الانتظار" />

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($reportTypes as $type => $label)
            <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
                <h3 class="text-base font-semibold text-[var(--color-ink)]">{{ $label }}</h3>
                <form method="POST" action="{{ route('admin.reports.store') }}" class="mt-4 space-y-3">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <div>
                        <label for="format_{{ $type }}" class="mb-1 block text-xs font-medium text-slate-500">الصيغة</label>
                        <select name="format" id="format_{{ $type }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                            <option value="csv">CSV</option>
                            <option value="xlsx">Excel (CSV)</option>
                            <option value="pdf">PDF (CSV)</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-[var(--color-primary)] px-3 py-2 text-sm font-medium text-white hover:bg-[var(--color-primary-hover)]">
                        إنشاء
                    </button>
                </form>
            </section>
        @endforeach
    </div>

    <x-admin.page-header title="قائمة الانتظار" subtitle="آخر طلبات التقارير — حدّث الصفحة بعد ثوانٍ إن كان الطابور يعمل" class="mt-10" />

    <x-admin.data-table>
        <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
            <tr>
                <th class="px-4 py-3 text-right">الوقت</th>
                <th class="px-4 py-3 text-right">مقدّم الطلب</th>
                <th class="px-4 py-3 text-right">النوع</th>
                <th class="px-4 py-3 text-right">الصيغة</th>
                <th class="px-4 py-3 text-right">الحالة</th>
                <th class="px-4 py-3 text-right">تحميل</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($jobs as $job)
                <tr class="hover:bg-slate-50/70">
                    <td class="whitespace-nowrap px-4 py-3 text-slate-600">{{ $job->created_at?->format('Y-m-d H:i') }}</td>
                    <td class="px-4 py-3">{{ $job->requester?->name ?? '—' }}</td>
                    <td class="px-4 py-3 font-medium text-slate-900">{{ $reportTypes[$job->type] ?? $job->type }}</td>
                    <td class="px-4 py-3 uppercase text-slate-600">{{ $job->format }}</td>
                    <td class="px-4 py-3">
                        @php
                            $statusClass = match ($job->status) {
                                'done' => 'bg-emerald-50 text-emerald-800',
                                'failed' => 'bg-rose-50 text-rose-800',
                                'running' => 'bg-amber-50 text-amber-800',
                                default => 'bg-slate-100 text-slate-700',
                            };
                        @endphp
                        <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClass }}">{{ $job->status }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @if ($job->status === 'done' && $job->file_path)
                            <a href="{{ route('admin.reports.download', $job) }}" class="rounded-lg border border-[var(--color-primary)]/30 bg-[var(--color-primary-light)] px-3 py-1.5 text-xs font-medium text-[var(--color-primary-hover)] hover:opacity-90">
                                تنزيل
                            </a>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-slate-500">لا توجد تقارير في قائمة الانتظار.</td>
                </tr>
            @endforelse
        </tbody>
    </x-admin.data-table>

    @if ($jobs->hasPages())
        <div class="mt-4">{{ $jobs->links() }}</div>
    @endif
@endsection
