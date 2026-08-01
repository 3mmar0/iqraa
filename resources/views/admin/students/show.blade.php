@extends('layouts.admin')

@section('title', $student->name)
@section('heading', $student->name)
@section('subheading', 'ملف الطالب · '.$student->email)

@section('header-actions')
    <a href="{{ route('admin.students.edit', $student) }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">تعديل</a>
    @if ($student->status === 'active')
        <form method="POST" action="{{ route('admin.students.impersonate', $student) }}" class="inline">
            @csrf
            <input type="hidden" name="context" value="student">
            <button type="submit" class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-2.5 text-sm font-medium text-amber-900 hover:bg-amber-100">دخول كـ</button>
        </form>
    @endif
    @if ($student->status === 'active')
        <form method="POST" action="{{ route('admin.students.suspend', $student) }}" class="inline" onsubmit="return confirm('تعليق الحساب؟');">
            @csrf
            <button type="submit" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-medium text-rose-800 hover:bg-rose-100">تعليق</button>
        </form>
    @else
        <form method="POST" action="{{ route('admin.students.activate', $student) }}" class="inline">
            @csrf
            <button type="submit" class="rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800">تفعيل</button>
        </form>
    @endif
@endsection

@section('content')
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
            <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[var(--color-primary-light)] text-2xl font-bold text-[var(--color-primary-hover)]">{{ mb_substr($student->name, 0, 1) }}</span>
            <dl class="grid flex-1 gap-3 sm:grid-cols-2 lg:grid-cols-4 text-sm">
                <div><dt class="text-slate-500">الهاتف</dt><dd class="font-medium">{{ $student->phone ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">الجامعة</dt><dd class="font-medium">{{ $student->university ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">المجموعة</dt><dd class="font-medium">{{ $student->group?->name ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">آخر دخول</dt><dd class="font-medium">{{ $student->last_login_at?->diffForHumans() ?? '—' }}</dd></div>
            </dl>
        </div>
    </div>

    <x-admin.tab-nav :tabs="$tabNav" class="mb-0" />

    <div class="rounded-b-2xl rounded-t-none border border-t-0 border-[var(--color-line)] bg-white p-5">
        @include('admin.students.tabs.'.$tab)
    </div>

    <div class="mt-6 rounded-2xl border border-[var(--color-line)] bg-white p-5">
        <h3 class="mb-3 text-sm font-semibold text-slate-900">إعادة تعيين كلمة المرور</h3>
        <form method="POST" action="{{ route('admin.students.reset-password', $student) }}" class="flex flex-wrap items-end gap-3">
            @csrf
            <div>
                <label class="mb-1 block text-xs text-slate-500" for="password">كلمة مرور جديدة (اختياري)</label>
                <input id="password" type="text" name="password" placeholder="اتركها فارغة لتوليد تلقائي"
                       class="rounded-xl border border-slate-200 px-3 py-2 text-sm">
            </div>
            <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">إعادة التعيين</button>
        </form>
    </div>
@endsection
