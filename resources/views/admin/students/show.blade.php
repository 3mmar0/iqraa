@extends('layouts.admin')

@section('title', $student->name)
@section('heading', $student->name)
@section('subheading', 'ملف الطالب · '.$student->email)

@php
    $statusLabel = ['active' => 'نشط', 'invited' => 'مدعو', 'disabled' => 'معطّل'][$student->status] ?? $student->status;
    $statusClass = match ($student->status) {
        'active' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
        'invited' => 'bg-amber-50 text-amber-900 border-amber-200',
        default => 'bg-rose-50 text-rose-800 border-rose-200',
    };
@endphp

@section('header-actions')
    <a href="{{ route('admin.students.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">رجوع للقائمة</a>
    <a href="{{ route('admin.students.edit', $student) }}" class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">تعديل البيانات</a>
@endsection

@section('content')
    @include('components.alert')

    @php
        $tabs = [
            ['key' => 'overview', 'label' => 'نظرة عامة'],
            ['key' => 'courses', 'label' => 'المقررات'],
            ['key' => 'payments', 'label' => 'المدفوعات'],
            ['key' => 'quizzes', 'label' => 'الاختبارات'],
            ['key' => 'progress', 'label' => 'التقدم'],
            ['key' => 'attendance', 'label' => 'الحضور'],
            ['key' => 'notifications', 'label' => 'الإشعارات'],
            ['key' => 'orders', 'label' => 'الطلبات'],
            ['key' => 'activity', 'label' => 'النشاط'],
            ['key' => 'notes', 'label' => 'ملاحظات'],
        ];
        $tabNav = collect($tabs)->map(fn ($t) => [
            'label' => $t['label'],
            'href' => route('admin.students.show', ['student' => $student, 'tab' => $t['key']]),
            'active' => $tab === $t['key'],
        ])->all();
    @endphp

    <div class="mb-6 rounded-2xl border border-[var(--color-line)] bg-white p-5">
        <div class="flex flex-wrap items-start gap-4">
            <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-[var(--color-primary-light)] text-2xl font-bold text-[var(--color-primary-hover)]">{{ mb_substr($student->name, 0, 1) }}</span>
            <div class="min-w-0 flex-1">
                <div class="mb-3 flex flex-wrap items-center gap-2">
                    <span class="rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $statusClass }}">{{ $statusLabel }}</span>
                    <span class="text-xs text-slate-500">#{{ $student->id }}</span>
                    @if ($student->email_verified_at)
                        <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs text-slate-600">البريد مُتحقق</span>
                    @endif
                </div>
                <dl class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4 text-sm">
                    <div><dt class="text-slate-500">الهاتف</dt><dd class="font-medium">{{ $student->phone ?? '—' }}</dd></div>
                    <div><dt class="text-slate-500">الجامعة</dt><dd class="font-medium">{{ $student->university ?? '—' }}</dd></div>
                    <div><dt class="text-slate-500">المجموعة</dt><dd class="font-medium">{{ $student->group?->name ?? '—' }}</dd></div>
                    <div><dt class="text-slate-500">آخر دخول</dt><dd class="font-medium">{{ $student->last_login_at?->diffForHumans() ?? '—' }}</dd></div>
                </dl>
            </div>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_20rem]">
        <div class="min-w-0">
            <x-admin.tab-nav :tabs="$tabNav" class="mb-0" />
            <div class="rounded-b-2xl rounded-t-none border border-t-0 border-[var(--color-line)] bg-white p-5">
                @include('admin.students.tabs.'.$tab)
            </div>
        </div>

        <aside class="space-y-4 xl:sticky xl:top-24 xl:self-start">
            @include('admin.students._controls', ['student' => $student, 'controls' => $controls])
        </aside>
    </div>
@endsection
