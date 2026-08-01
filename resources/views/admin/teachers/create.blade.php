@extends('layouts.admin')

@section('title', 'معلم جديد')
@section('heading', 'معلم جديد')

@section('content')
    <form method="POST" action="{{ route('admin.teachers.store') }}" class="max-w-2xl rounded-2xl border border-[var(--color-line)] bg-white p-6">
        @csrf
        @include('admin.teachers._form')
        <div class="mt-6 flex gap-2">
            <button class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm text-white">حفظ</button>
            <a href="{{ route('admin.teachers.index') }}" class="rounded-xl border px-4 py-2.5 text-sm">إلغاء</a>
        </div>
    </form>
@endsection
