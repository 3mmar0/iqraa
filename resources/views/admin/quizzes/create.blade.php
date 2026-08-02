@extends('layouts.admin')

@section('title', 'اختبار جديد')
@section('heading', 'إنشاء اختبار')

@section('content')
    <x-admin.form-shell max-width="max-w-2xl">
        <x-slot:header>
            <p class="text-sm font-semibold text-slate-800">بيانات الاختبار</p>
            <p class="mt-0.5 text-xs text-slate-500">العنوان، المقرر، المدة، وحالة النشر</p>
        </x-slot:header>
        <form method="POST" action="{{ route('admin.quizzes.store') }}" class="space-y-5">
            @csrf
            @include('admin.quizzes._form', ['quiz' => null])
            <div class="flex flex-wrap gap-2 border-t border-slate-100 pt-5">
                <button class="admin-btn admin-btn-primary">حفظ</button>
                <a href="{{ route('admin.quizzes.index') }}" class="admin-btn admin-btn-ghost">إلغاء</a>
            </div>
        </form>
    </x-admin.form-shell>
@endsection
