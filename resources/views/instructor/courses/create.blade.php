@extends('layouts.instructor')

@section('title', 'إنشاء مقرر')

@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">إنشاء مقرر</h1>

    <form method="POST" action="{{ route('instructor.courses.store') }}" class="max-w-xl space-y-4 rounded-xl border border-slate-200 bg-white p-6">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-medium">العنوان</label>
            <input type="text" name="title" value="{{ old('title') }}" required class="w-full rounded border border-slate-300 px-3 py-2">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">الوصف</label>
            <textarea name="description" rows="4" class="w-full rounded border border-slate-300 px-3 py-2">{{ old('description') }}</textarea>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium">الساعات</label>
                <input type="number" step="0.5" name="hours" value="{{ old('hours') }}" class="w-full rounded border border-slate-300 px-3 py-2">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">الفصل</label>
                <input type="text" name="term_label" value="{{ old('term_label') }}" class="w-full rounded border border-slate-300 px-3 py-2">
            </div>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">الجدول</label>
            <input type="text" name="schedule_text" value="{{ old('schedule_text') }}" class="w-full rounded border border-slate-300 px-3 py-2">
        </div>
        <button type="submit" class="rounded bg-teal-700 px-4 py-2 text-white">حفظ</button>
    </form>
@endsection