@extends('layouts.admin')

@section('title', 'تعديل الواجب')
@section('heading', 'تعديل الواجب')
@section('subheading', $assignment->title)

@section('content')
    <x-admin.form-shell max-width="max-w-2xl">
        <x-slot:header>
            <p class="text-sm font-semibold text-slate-800">تعديل الواجب</p>
            <p class="mt-0.5 text-xs text-slate-500">{{ $assignment->title }}</p>
        </x-slot:header>
        <form method="POST" action="{{ route('admin.assignments.update', $assignment) }}" class="space-y-5">
            @csrf
            @method('PUT')
            @include('admin.assignments._form', ['assignment' => $assignment])
            <div class="flex flex-wrap gap-2 border-t border-slate-100 pt-5">
                <button class="admin-btn admin-btn-primary">حفظ</button>
                <a href="{{ route('admin.assignments.show', $assignment) }}" class="admin-btn admin-btn-ghost">رجوع</a>
            </div>
        </form>
    </x-admin.form-shell>
@endsection
