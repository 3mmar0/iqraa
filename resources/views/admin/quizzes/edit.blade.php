@extends('layouts.admin')

@section('title', 'تعديل الاختبار')
@section('heading', 'تعديل الاختبار')
@section('subheading', $quiz->title)

@section('content')
    <x-admin.form-shell max-width="max-w-2xl">
        <x-slot:header>
            <p class="text-sm font-semibold text-slate-800">تعديل الاختبار</p>
            <p class="mt-0.5 text-xs text-slate-500">{{ $quiz->title }}</p>
        </x-slot:header>
        <form method="POST" action="{{ route('admin.quizzes.update', $quiz) }}" class="space-y-5">
            @csrf
            @method('PUT')
            @include('admin.quizzes._form', ['quiz' => $quiz])
            <div class="flex flex-wrap gap-2 border-t border-slate-100 pt-5">
                <button class="admin-btn admin-btn-primary">حفظ</button>
                <a href="{{ route('admin.quizzes.show', $quiz) }}" class="admin-btn admin-btn-ghost">رجوع</a>
            </div>
        </form>
    </x-admin.form-shell>
@endsection
