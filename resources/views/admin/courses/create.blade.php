@extends('layouts.admin')

@section('title', 'مقرر جديد')
@section('heading', 'إنشاء مقرر')
@section('subheading', 'إضافة مقرر وتعيين المحاضر وحالة النشر')

@section('content')
    <x-admin.form-shell>
        <x-slot:header>
            <p class="text-sm font-semibold text-slate-800">بيانات المقرر</p>
            <p class="mt-0.5 text-xs text-slate-500">العنوان، المحاضر، التصنيف، والسنة الدراسية</p>
        </x-slot:header>
        <form method="POST" action="{{ route('admin.courses.store') }}" class="space-y-5">
            @csrf
            @include('admin.courses._form', ['course' => null])
            <div class="flex flex-wrap gap-2 border-t border-slate-100 pt-5">
                <button class="admin-btn admin-btn-primary">حفظ</button>
                <a href="{{ route('admin.courses.index') }}" class="admin-btn admin-btn-ghost">إلغاء</a>
            </div>
        </form>
    </x-admin.form-shell>
@endsection
