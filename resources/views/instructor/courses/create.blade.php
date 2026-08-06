@extends('layouts.instructor')

@section('title', 'إنشاء مقرر')
@section('heading', 'إنشاء مقرر')
@section('subheading', 'أضف مقرراً جديداً ثم أكمل الدروس والاختبارات')

@section('header-actions')
    <a href="{{ route('instructor.courses.index') }}" class="rounded-2xl border border-[var(--color-line)] bg-white px-3.5 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">رجوع</a>
@endsection

@section('content')
    <div class="mx-auto max-w-2xl">
        <form method="POST" action="{{ route('instructor.courses.store') }}" class="space-y-4 rounded-2xl border border-[var(--color-line)] bg-white p-6 shadow-[0_10px_28px_-22px_rgba(47,58,69,0.4)]">
            @csrf
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500" for="title">العنوان</label>
                <input id="title" type="text" name="title" value="{{ old('title') }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
                @error('title')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500" for="description">الوصف</label>
                <textarea id="description" name="description" rows="4" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">{{ old('description') }}</textarea>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-medium text-slate-500" for="hours">الساعات</label>
                    <input id="hours" type="number" step="0.5" name="hours" value="{{ old('hours') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-slate-500" for="term_label">الفصل</label>
                    <input id="term_label" type="text" name="term_label" value="{{ old('term_label') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                </div>
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500" for="schedule_text">الجدول</label>
                <input id="schedule_text" type="text" name="schedule_text" value="{{ old('schedule_text') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            </div>
            <button type="submit" class="w-full rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">حفظ المقرر</button>
        </form>
    </div>
@endsection
