@extends('layouts.app')

@section('title', 'الشروط والأحكام')

@section('content')
    <article class="mx-auto max-w-3xl px-4 py-16 sm:px-6">
        <h1 class="site-brand text-4xl font-bold text-[var(--color-ink)]">الشروط والأحكام</h1>
        <p class="mt-2 text-sm text-slate-500">آخر تحديث: {{ now()->translatedFormat('F Y') }}</p>
        <div class="mt-10 space-y-6 text-slate-700 leading-relaxed">
            <p>باستخدامك لمنصة {{ config('app.name') }} فإنك توافق على الالتزام بهذه الشروط وعلى استخدام المنصة لأغراض تعليمية مشروعة.</p>
            <h2 class="text-xl font-semibold text-slate-900">الحسابات</h2>
            <p>أنت مسؤول عن سرية بيانات الدخول ودقة المعلومات التي تقدّمها. يحق للإدارة إيقاف الحسابات التي تنتهك السياسات أو تسيء استخدام النظام.</p>
            <h2 class="text-xl font-semibold text-slate-900">المحتوى والالتحاق</h2>
            <p>الوصول إلى المقررات يتم عبر طلب وموافقة وفق قواعد المنصة. المحتوى محمي لصالح المنصة والمحاضرين ولا يجوز إعادة نشره دون إذن.</p>
            <h2 class="text-xl font-semibold text-slate-900">التعديلات</h2>
            <p>قد نحدّث هذه الشروط عند الحاجة. استمرارك في الاستخدام بعد التحديث يعني قبولك للنسخة الأحدث.</p>
            <p>للاستفسار: <a href="{{ route('public.contact') }}" class="text-teal-800 hover:underline">تواصل معنا</a>.</p>
        </div>
    </article>
@endsection
