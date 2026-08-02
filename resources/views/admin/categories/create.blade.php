@extends('layouts.admin')

@section('title', 'تصنيف جديد')
@section('heading', 'إنشاء تصنيف')

@section('content')
    <x-admin.form-shell max-width="max-w-2xl">
        <x-slot:header>
            <p class="text-sm font-semibold text-slate-800">بيانات التصنيف</p>
            <p class="mt-0.5 text-xs text-slate-500">الاسم والترتيب وحالة الظهور</p>
        </x-slot:header>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-5">
            @csrf
            @include('admin.categories._form', ['category' => null])
            <div class="flex flex-wrap gap-2 border-t border-slate-100 pt-5">
                <button class="admin-btn admin-btn-primary">حفظ</button>
                <a href="{{ route('admin.categories.index') }}" class="admin-btn admin-btn-ghost">إلغاء</a>
            </div>
        </form>
    </x-admin.form-shell>
@endsection
