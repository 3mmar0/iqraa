@extends('layouts.admin')

@section('title', 'لوحة الإدارة')
@section('heading', 'نظرة عامة')
@section('subheading', 'إدارة المنصة والمستخدمين والتشغيل')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <a href="{{ route('admin.users.index', ['type' => 'students']) }}" class="group rounded-2xl border border-[var(--color-line)] bg-white p-5 shadow-[0_8px_24px_-16px_rgba(12,31,28,0.45)] transition hover:-translate-y-0.5 hover:border-teal-300">
            <p class="text-sm text-slate-500">المستخدمون</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($stats['users']) }}</p>
            <p class="mt-1 text-xs text-teal-700 group-hover:underline">الطلاب وفريق العمل</p>
        </a>
        <div class="rounded-2xl border border-[var(--color-line)] bg-white p-5 shadow-[0_8px_24px_-16px_rgba(12,31,28,0.45)]">
            <p class="text-sm text-slate-500">حسابات نشطة</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($stats['active']) }}</p>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="group rounded-2xl border border-[var(--color-line)] bg-white p-5 shadow-[0_8px_24px_-16px_rgba(12,31,28,0.45)] transition hover:-translate-y-0.5 hover:border-teal-300">
            <p class="text-sm text-slate-500">الأدوار</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($stats['roles']) }}</p>
            <p class="mt-1 text-xs text-teal-700 group-hover:underline">إدارة الصلاحيات</p>
        </a>
        <a href="{{ route('admin.ops.index') }}" class="group rounded-2xl border border-[var(--color-line)] bg-white p-5 shadow-[0_8px_24px_-16px_rgba(12,31,28,0.45)] transition hover:-translate-y-0.5 hover:border-teal-300">
            <p class="text-sm text-slate-500">وظائف فاشلة</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($failedJobs) }}</p>
            <p class="mt-1 text-xs text-teal-700 group-hover:underline">مراقبة التشغيل</p>
        </a>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-base font-semibold text-slate-900">أحدث المستخدمين</h2>
                <a href="{{ route('admin.users.create') }}" class="text-sm font-medium text-teal-700 hover:underline">إضافة مستخدم</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($recentUsers as $user)
                    <div class="flex items-center justify-between gap-3 py-3">
                        <div class="min-w-0">
                            <p class="truncate font-medium text-slate-900">{{ $user->name }}</p>
                            <p class="truncate text-sm text-slate-500">{{ $user->email }}</p>
                        </div>
                        <a href="{{ route('admin.users.edit', $user) }}" class="shrink-0 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">تعديل</a>
                    </div>
                @empty
                    <p class="py-6 text-sm text-slate-500">لا يوجد مستخدمون بعد.</p>
                @endforelse
            </div>
        </section>

        <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-base font-semibold text-slate-900">اختصارات سريعة</h2>
            </div>
            <div class="grid gap-3 sm:grid-cols-2">
                <a href="{{ route('admin.users.index', ['type' => 'students']) }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-teal-50">الطلاب</a>
                <a href="{{ route('admin.users.index', ['type' => 'staff']) }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-teal-50">فريق العمل</a>
                <a href="{{ route('admin.courses.index') }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-teal-50">المقررات</a>
                <a href="{{ route('admin.lessons.index') }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-teal-50">الدروس</a>
                <a href="{{ route('admin.enrollment-requests.index') }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-teal-50">طلبات الالتحاق</a>
                <a href="{{ route('admin.payments.index') }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-teal-50">المدفوعات</a>
                <a href="{{ route('admin.roles.index') }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-teal-50">الأدوار</a>
                <a href="{{ route('admin.security.index') }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-teal-50">الأمان</a>
            </div>
            @if ($recentAudits->isNotEmpty())
                <h3 class="mb-2 mt-6 text-sm font-semibold text-slate-700">آخر أحداث التدقيق</h3>
                <ul class="space-y-2 text-sm text-slate-600">
                    @foreach ($recentAudits as $log)
                        <li class="rounded-lg bg-slate-50 px-3 py-2">{{ $log->action }} · {{ $log->created_at?->diffForHumans() }}</li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
@endsection
