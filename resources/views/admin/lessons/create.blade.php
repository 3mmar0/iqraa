@extends('layouts.admin')

@section('title', 'درس جديد')
@section('heading', 'إنشاء درس')

@section('content')
    <x-admin.form-shell max-width="max-w-2xl">
        <x-slot:header>
            <p class="text-sm font-semibold text-slate-800">بيانات الدرس</p>
            <p class="mt-0.5 text-xs text-slate-500">العنوان، المقرر، الترتيب، وحالة النشر</p>
        </x-slot:header>
        <form method="POST" action="{{ route('admin.lessons.store') }}" class="space-y-5">
            @csrf
            @include('admin.lessons._form', ['lesson' => null, 'selectedCourseId' => $selectedCourseId])
            <div class="flex flex-wrap gap-2 border-t border-slate-100 pt-5">
                <button class="admin-btn admin-btn-primary">حفظ</button>
                <a href="{{ route('admin.lessons.index') }}" class="admin-btn admin-btn-ghost">إلغاء</a>
            </div>
        </form>
    </x-admin.form-shell>
@endsection
