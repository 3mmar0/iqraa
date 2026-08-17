@extends('layouts.app')

@section('title', 'الشروط والأحكام')

@section('content')
    <x-public-page-hero title="الشروط والأحكام" />

    <article class="academy-section bg-[var(--color-surface)]">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <p class="text-sm text-[var(--color-muted)]">آخر تحديث: {{ now()->translatedFormat('F Y') }}</p>
            <div class="mt-8 space-y-6 leading-relaxed text-[var(--color-text-secondary)]">
                <p>باستخدامك لمنصة {{ config('app.name') }} فإنك توافق على الالتزام بهذه الشروط وعلى استخدام المنصة لأغراض تعليمية مشروعة.</p>
                <h2 class="academy-display text-xl font-bold text-[var(--color-text)]">الحسابات</h2>
                <p>أنت مسؤول عن سرية بيانات الدخول ودقة المعلومات التي تقدّمها. يحق للإدارة إيقاف الحسابات التي تنتهك السياسات أو تسيء استخدام النظام.</p>
                <h2 class="academy-display text-xl font-bold text-[var(--color-text)]">المحتوى والالتحاق</h2>
                <p>الوصول إلى المقررات يتم عبر طلب وموافقة وفق قواعد المنصة. المحتوى محمي لصالح المنصة والمحاضرين ولا يجوز إعادة نشره دون إذن.</p>
                <h2 class="academy-display text-xl font-bold text-[var(--color-text)]">التعديلات</h2>
                <p>قد نحدّث هذه الشروط عند الحاجة. استمرارك في الاستخدام بعد التحديث يعني قبولك للنسخة الأحدث.</p>
                <p>للاستفسار: <a href="{{ route('public.contact') }}" class="font-bold text-[var(--color-primary)] hover:underline">تواصل معنا</a>.</p>
            </div>
        </div>
    </article>
@endsection
