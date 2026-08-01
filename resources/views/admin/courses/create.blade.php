@extends('layouts.admin')

@section('title', 'مقرر جديد')
@section('heading', 'إنشاء مقرر')
@section('subheading', 'إضافة مقرر وتعيين المحاضر وحالة النشر')

@section('content')
    <form method="POST" action="{{ route('admin.courses.store') }}" class="mx-auto max-w-3xl space-y-5 rounded-2xl border border-[var(--color-line)] bg-white p-6">
        @csrf
        @include('admin.courses._form', ['course' => null])
        <div class="flex gap-2">
            <button class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white">حفظ</button>
            <a href="{{ route('admin.courses.index') }}" class="rounded-xl border px-4 py-2.5 text-sm">إلغاء</a>
        </div>
    </form>
@endsection
