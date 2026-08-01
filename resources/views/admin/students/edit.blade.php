@extends('layouts.admin')

@section('title', 'تعديل '.$student->name)
@section('heading', 'تعديل الطالب')
@section('subheading', $student->email)

@section('header-actions')
    <a href="{{ route('admin.students.show', $student) }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">عرض الملف</a>
@endsection

@section('content')
    @include('admin.students._form', ['student' => $student])
@endsection
