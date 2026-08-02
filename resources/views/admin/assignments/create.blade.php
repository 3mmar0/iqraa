@extends('layouts.admin')

@section('title', 'واجب جديد')
@section('heading', 'إنشاء واجب')

@section('content')
    <x-admin.form-shell max-width="max-w-2xl">
        <x-slot:header>
            <p class="text-sm font-semibold text-slate-800">بيانات الواجب</p>
            <p class="mt-0.5 text-xs text-slate-500">العنوان، المقرر، وموعد التسليم</p>
        </x-slot:header>
        <form method="POST" action="{{ route('admin.assignments.store') }}" class="space-y-5">
            @csrf
            @include('admin.assignments._form', ['assignment' => null])
            <div class="flex flex-wrap gap-2 border-t border-slate-100 pt-5">
                <button class="admin-btn admin-btn-primary">حفظ</button>
                <a href="{{ route('admin.assignments.index') }}" class="admin-btn admin-btn-ghost">إلغاء</a>
            </div>
        </form>
    </x-admin.form-shell>
@endsection
