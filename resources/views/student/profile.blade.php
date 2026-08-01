@extends('layouts.student')

@section('title', 'الملف الشخصي')
@section('heading', 'الملف الشخصي')
@section('subheading', 'حدّث بياناتك الأساسية وكلمة المرور')

@section('content')
    <div class="mx-auto max-w-xl">
        <form method="POST" action="{{ route('student.profile.update') }}" class="space-y-5 rounded-2xl border border-[var(--color-line)] bg-white p-6 shadow-[0_12px_32px_-24px_rgba(15,23,42,0.35)] sm:p-7">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1.5 block text-sm font-medium text-[var(--color-ink)]" for="name">الاسم</label>
                <input id="name" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-[var(--color-ink)]" for="email">البريد الإلكتروني</label>
                <input id="email" type="email" value="{{ $user->email }}" disabled
                       class="w-full cursor-not-allowed rounded-xl border border-slate-200 bg-[var(--color-sand)] px-3.5 py-2.5 text-sm text-[var(--color-text-secondary)]">
                <p class="mt-1 text-xs text-[var(--color-muted)]">لا يمكن تغيير البريد من هنا.</p>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-[var(--color-ink)]" for="phone">الهاتف</label>
                <input id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                       class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-[var(--color-ink)]" for="university">الجامعة</label>
                <input id="university" name="university" value="{{ old('university', $user->university) }}"
                       class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
            </div>

            <div class="border-t border-[var(--color-line)] pt-5">
                <p class="text-sm font-semibold text-[var(--color-ink)]">تغيير كلمة المرور</p>
                <p class="mt-1 text-xs text-[var(--color-muted)]">اترك الحقول فارغة إن لم ترغب بالتغيير.</p>
                <div class="mt-4 space-y-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-[var(--color-ink)]" for="current_password">كلمة المرور الحالية</label>
                        <input id="current_password" type="password" name="current_password"
                               class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-[var(--color-ink)]" for="password">كلمة المرور الجديدة</label>
                        <input id="password" type="password" name="password"
                               class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-[var(--color-ink)]" for="password_confirmation">تأكيد كلمة المرور</label>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                               class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full rounded-xl bg-[var(--color-primary)] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)] sm:w-auto">
                حفظ التغييرات
            </button>
        </form>
    </div>
@endsection
