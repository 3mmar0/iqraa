@extends('layouts.admin')

@section('title', 'اختبار جديد')
@section('heading', 'إنشاء اختبار')

@section('content')
    <form method="POST" action="{{ route('admin.quizzes.store') }}" class="mx-auto max-w-2xl space-y-4 rounded-2xl border border-[var(--color-line)] bg-white p-6">
        @csrf
        @include('admin.quizzes._form', ['quiz' => null])
        <div class="flex gap-2">
            <button class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white">حفظ</button>
            <a href="{{ route('admin.quizzes.index') }}" class="rounded-xl border px-4 py-2.5 text-sm">إلغاء</a>
        </div>
    </form>
@endsection
