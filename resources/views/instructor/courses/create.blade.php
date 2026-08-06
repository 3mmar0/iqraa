@extends('layouts.instructor')

@section('title', 'مقرر جديد')
@section('heading', 'إنشاء مقرر')
@section('subheading', 'إضافة مقرر وتعيين التصنيف وحالة النشر')

@section('header-actions')
    <a href="{{ route('instructor.courses.index') }}" class="admin-btn admin-btn-ghost">رجوع</a>
@endsection

@section('content')
    <x-admin.form-shell>
        <x-slot:header>
            <p class="text-sm font-semibold text-slate-800">بيانات المقرر</p>
            <p class="mt-0.5 text-xs text-slate-500">العنوان، التصنيف، والسنة الدراسية — سيُسجَّل المقرر باسمك كمحاضر</p>
        </x-slot:header>
        <form method="POST" action="{{ route('instructor.courses.store') }}" class="space-y-5">
            @csrf
            @include('instructor.courses._form', ['course' => null])
            <div class="flex flex-wrap gap-2 border-t border-slate-100 pt-5">
                <button type="submit" class="admin-btn admin-btn-primary">حفظ</button>
                <a href="{{ route('instructor.courses.index') }}" class="admin-btn admin-btn-ghost">إلغاء</a>
            </div>
        </form>
    </x-admin.form-shell>
@endsection
