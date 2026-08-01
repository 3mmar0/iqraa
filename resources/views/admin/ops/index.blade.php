@extends('layouts.admin')

@section('title', 'التشغيل والمراقبة')
@section('heading', 'التشغيل والمراقبة')
@section('subheading', 'حالة الطوابير والتخزين والمراقبة')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2">
        @foreach ($placeholders as $key => $label)
            <article class="rounded-2xl border border-[var(--color-line)] bg-white p-5 shadow-[0_8px_24px_-16px_rgba(12,31,28,0.3)]">
                <h2 class="text-base font-semibold text-slate-900">{{ $label }}</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    هذه اللوحة جاهزة للربط مع مؤشرات التشغيل الحية (Redis، Supervisor، مساحة القرص). يمكنك مراقبة طابور `yatmaen-queue` من الخادم حالياً.
                </p>
                <div class="mt-4 rounded-xl bg-[var(--color-sand)] px-3 py-2 text-xs font-medium text-teal-900">مفتاح: {{ $key }}</div>
            </article>
        @endforeach
    </div>
@endsection
