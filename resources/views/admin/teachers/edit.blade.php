@extends('layouts.admin')

@section('title', 'تعديل معلم')
@section('heading', 'تعديل معلم')
@section('subheading', $teacher->name)

@section('content')
    <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}" class="max-w-2xl rounded-2xl border border-[var(--color-line)] bg-white p-6">
        @csrf
        @method('PUT')
        @include('admin.teachers._form', ['teacher' => $teacher])
        <div class="mt-6 flex gap-2">
            <button class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm text-white">تحديث</button>
            <a href="{{ route('admin.teachers.show', $teacher) }}" class="rounded-xl border px-4 py-2.5 text-sm">رجوع</a>
        </div>
    </form>
@endsection
