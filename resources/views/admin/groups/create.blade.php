@extends('layouts.admin')

@section('title', 'مجموعة جديدة')
@section('heading', 'مجموعة جديدة')

@section('content')
    <form method="POST" action="{{ route('admin.groups.store') }}" class="max-w-2xl rounded-2xl border bg-white p-6">
        @csrf
        @include('admin.groups._form', compact('years', 'semesters'))
        <div class="mt-6 flex gap-2">
            <button class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm text-white">حفظ</button>
            <a href="{{ route('admin.groups.index') }}" class="rounded-xl border px-4 py-2.5 text-sm">إلغاء</a>
        </div>
    </form>
@endsection
