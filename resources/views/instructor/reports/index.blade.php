@extends('layouts.instructor')

@section('title', 'التقارير')
@section('heading', 'التقارير والتحليلات')
@section('subheading', 'أرقام سريعة عن عبء التدريس')

@section('header-actions')
    <a href="{{ route('instructor.dashboard') }}" class="rounded-2xl border border-[var(--color-line)] bg-white px-3.5 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">لوحة الأداء</a>
@endsection

@section('content')
    <div class="mx-auto max-w-6xl space-y-8">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin.kpi-card label="المقررات" :value="$stats['courses']" />
            <x-admin.kpi-card label="الدروس" :value="$stats['lessons']" />
            <x-admin.kpi-card label="الاختبارات" :value="$stats['quizzes']" />
            <x-admin.kpi-card label="الطلاب" :value="$stats['students']" />
        </div>

        <section class="rounded-2xl border border-[var(--color-line)] bg-white p-6">
            <h2 class="font-bold text-[var(--color-ink)]">ماذا تعني هذه الأرقام؟</h2>
            <ul class="mt-4 space-y-3 text-sm leading-relaxed text-[var(--color-text-secondary)]">
                <li><strong class="text-[var(--color-ink)]">المقررات:</strong> كل المقررات المعيّنة لحسابك كمعلّم.</li>
                <li><strong class="text-[var(--color-ink)]">الدروس:</strong> مجموع الدروس عبر تلك المقررات.</li>
                <li><strong class="text-[var(--color-ink)]">الاختبارات:</strong> الاختبارات المرتبطة بمقرراتك.</li>
                <li><strong class="text-[var(--color-ink)]">الطلاب:</strong> عدد الطلاب الفريدين ذوي التحاق نشط.</li>
            </ul>
            <div class="mt-6 flex flex-wrap gap-2">
                <a href="{{ route('instructor.courses.index') }}" class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white">فتح المقررات</a>
                <a href="{{ route('instructor.students.index') }}" class="rounded-xl border border-[var(--color-line)] px-4 py-2.5 text-sm font-medium text-slate-700">قائمة الطلاب</a>
            </div>
        </section>
    </div>
@endsection
