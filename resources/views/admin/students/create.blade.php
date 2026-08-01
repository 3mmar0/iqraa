@extends('layouts.admin')

@section('title', 'طالب جديد')
@section('heading', 'إضافة طالب')
@section('subheading', 'إنشاء حساب طالب جديد')

@section('header-actions')
    <a href="{{ route('admin.students.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">رجوع</a>
@endsection

@section('content')
    @include('admin.students._form')
@endsection
