@extends('layouts.instructor')

@section('title', 'الإعدادات')
@section('heading', 'الإعدادات')
@section('subheading', 'ملف حساب المحاضر')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">
        <section class="overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_10px_28px_-22px_rgba(47,58,69,0.4)]">
            <div class="h-2 bg-gradient-to-l from-[var(--color-primary)] via-[var(--color-secondary)] to-[var(--color-accent)]"></div>
            <div class="p-6">
                <div class="flex flex-wrap items-center gap-4">
                    <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[var(--color-secondary-light)] text-2xl font-bold text-[var(--color-secondary-hover)]">
                        {{ mb_substr($user->name, 0, 1) }}
                    </span>
                    <div>
                        <h2 class="text-xl font-bold text-[var(--color-ink)]">{{ $user->name }}</h2>
                        <p class="text-sm text-slate-500">{{ $user->email }}</p>
                    </div>
                </div>

                <dl class="mt-6 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl border border-[var(--color-line)] p-4">
                        <dt class="text-xs text-slate-500">الهاتف</dt>
                        <dd class="mt-1 font-medium">{{ $user->phone ?: '—' }}</dd>
                    </div>
                    <div class="rounded-2xl border border-[var(--color-line)] p-4">
                        <dt class="text-xs text-slate-500">الحالة</dt>
                        <dd class="mt-1"><x-admin.status-badge :status="$user->status" /></dd>
                    </div>
                    <div class="rounded-2xl border border-[var(--color-line)] p-4">
                        <dt class="text-xs text-slate-500">المقررات</dt>
                        <dd class="mt-1 font-medium">{{ $courseCount }}</dd>
                    </div>
                    <div class="rounded-2xl border border-[var(--color-line)] p-4">
                        <dt class="text-xs text-slate-500">الطلاب</dt>
                        <dd class="mt-1 font-medium">{{ $studentCount }}</dd>
                    </div>
                </dl>
            </div>
        </section>

        <section class="rounded-2xl border border-[var(--color-line)] bg-white p-6">
            <h2 class="font-bold text-[var(--color-ink)]">أمان الحساب</h2>
            <p class="mt-2 text-sm text-[var(--color-text-secondary)]">لإعادة تعيين كلمة المرور استخدم صفحة استعادة كلمة المرور من شاشة الدخول.</p>
            <div class="mt-4 flex flex-wrap gap-2">
                <a href="{{ route('password.request') }}" class="rounded-xl border border-[var(--color-line)] px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">استعادة كلمة المرور</a>
                <a href="{{ route('instructor.home') }}" class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">العودة للرئيسية</a>
            </div>
        </section>
    </div>
@endsection
