@extends('layouts.student')

@section('title', 'الإعدادات')
@section('heading', 'الإعدادات')
@section('subheading', 'تفضيلات الإشعارات والمظهر')

@section('content')
    @php
        $prefs = $settings->notification_preferences ?? [];
    @endphp
    <div class="mx-auto max-w-xl">
        <form method="POST" action="{{ route('student.settings.update') }}" class="space-y-6 rounded-2xl border border-[var(--color-line)] bg-white p-6 shadow-[0_12px_32px_-24px_rgba(15,23,42,0.35)] sm:p-7">
            @csrf
            @method('PUT')

            <fieldset>
                <legend class="text-base font-semibold text-[var(--color-ink)]">الإشعارات</legend>
                <div class="mt-4 space-y-3">
                    <label class="flex cursor-pointer items-center justify-between gap-4 rounded-xl border border-[var(--color-line)] px-4 py-3">
                        <span class="text-sm text-[var(--color-ink)]">إشعارات البريد</span>
                        <input type="checkbox" name="notify_email" value="1" @checked(old('notify_email', $prefs['email'] ?? true))
                               class="rounded border-slate-300 text-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                    </label>
                    <label class="flex cursor-pointer items-center justify-between gap-4 rounded-xl border border-[var(--color-line)] px-4 py-3">
                        <span class="text-sm text-[var(--color-ink)]">إشعارات داخل المنصة</span>
                        <input type="checkbox" name="notify_in_app" value="1" @checked(old('notify_in_app', $prefs['in_app'] ?? true))
                               class="rounded border-slate-300 text-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                    </label>
                </div>
            </fieldset>

            <fieldset>
                <legend class="text-base font-semibold text-[var(--color-ink)]">المظهر</legend>
                <label class="mt-4 flex cursor-pointer items-center justify-between gap-4 rounded-xl border border-[var(--color-line)] px-4 py-3">
                    <span class="text-sm text-[var(--color-ink)]">الوضع الداكن (عند التفعيل لاحقاً)</span>
                    <input type="checkbox" name="dark_mode" value="1" @checked(old('dark_mode', $settings->dark_mode))
                           class="rounded border-slate-300 text-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                </label>
            </fieldset>

            <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">
                حفظ الإعدادات
            </button>
        </form>
    </div>
@endsection
