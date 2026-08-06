@extends('layouts.instructor')

@section('title', 'الطلاب')
@section('heading', 'قائمة الطلاب')
@section('subheading', 'الطلاب الملتحقون بمقرراتك')

@section('header-actions')
    <a href="{{ route('instructor.courses.index') }}" class="rounded-2xl border border-[var(--color-line)] bg-white px-3.5 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">المقررات</a>
@endsection

@section('content')
    <div class="mx-auto max-w-6xl" x-data="{ q: '' }">
        @if ($enrollments->isEmpty())
            <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-6 py-14 text-center">
                <p class="text-lg font-bold text-[var(--color-ink)]">لا يوجد طلاب بعد</p>
                <p class="mx-auto mt-2 max-w-md text-sm text-[var(--color-text-secondary)]">عندما يُعتمد التحاق الطلاب بمقرراتك سيظهرون هنا.</p>
            </div>
        @else
            <div class="mb-5">
                <label class="mb-1 block text-xs font-medium text-slate-500" for="student-q">بحث</label>
                <input id="student-q" type="search" x-model="q" placeholder="اسم الطالب أو المقرر أو البريد..."
                       class="w-full max-w-md rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
            </div>

            <div class="overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_10px_28px_-22px_rgba(47,58,69,0.4)]">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
                        <tr>
                            <th class="px-4 py-3.5 text-right">الطالب</th>
                            <th class="px-4 py-3.5 text-right">المقرر</th>
                            <th class="px-4 py-3.5 text-right">تاريخ الالتحاق</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($enrollments as $enrollment)
                            @php
                                $hay = trim(($enrollment->user?->name ?? '').' '.($enrollment->user?->email ?? '').' '.($enrollment->course?->title ?? ''));
                            @endphp
                            <tr class="hover:bg-[var(--color-sand)]/60" x-show="!q || {{ \Illuminate\Support\Js::from($hay) }}.includes(q)">
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[var(--color-secondary-light)] text-sm font-bold text-[var(--color-secondary-hover)]">
                                            {{ mb_substr($enrollment->user?->name ?? '?', 0, 1) }}
                                        </span>
                                        <div class="min-w-0">
                                            <p class="truncate font-semibold text-[var(--color-ink)]">{{ $enrollment->user?->name }}</p>
                                            <p class="truncate text-xs text-slate-500">{{ $enrollment->user?->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-[var(--color-ink)]">{{ $enrollment->course?->title }}</td>
                                <td class="px-4 py-3.5 text-slate-500">{{ $enrollment->created_at?->translatedFormat('d M Y') ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
