@extends('layouts.admin')

@section('title', 'تعديل سنة')
@section('heading', 'تعديل سنة')
@section('subheading', $year->name)

@section('content')
    <form method="POST" action="{{ route('admin.academic-years.update', $year) }}" class="max-w-2xl rounded-2xl border bg-white p-6">
        @csrf
        @method('PUT')
        @include('admin.academic-years._form', ['year' => $year])
        <div class="mt-6 flex gap-2">
            <button class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm text-white">تحديث</button>
            <a href="{{ route('admin.academic-years.index') }}" class="rounded-xl border px-4 py-2.5 text-sm">رجوع</a>
        </div>
    </form>
@endsection
