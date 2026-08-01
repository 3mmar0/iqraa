@extends('layouts.admin')

@section('title', 'سنة دراسية جديدة')
@section('heading', 'سنة دراسية جديدة')

@section('content')
    <form method="POST" action="{{ route('admin.academic-years.store') }}" class="max-w-2xl rounded-2xl border bg-white p-6">
        @csrf
        @include('admin.academic-years._form')
        <div class="mt-6 flex gap-2">
            <button class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm text-white">حفظ</button>
            <a href="{{ route('admin.academic-years.index') }}" class="rounded-xl border px-4 py-2.5 text-sm">إلغاء</a>
        </div>
    </form>
@endsection
