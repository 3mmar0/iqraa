@extends('layouts.admin')

@section('title', 'تعديل الواجب')
@section('heading', 'تعديل الواجب')
@section('subheading', $assignment->title)

@section('content')
    <form method="POST" action="{{ route('admin.assignments.update', $assignment) }}" class="mx-auto max-w-2xl space-y-4 rounded-2xl border border-[var(--color-line)] bg-white p-6">
        @csrf
        @method('PUT')
        @include('admin.assignments._form', ['assignment' => $assignment])
        <div class="flex gap-2">
            <button class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white">حفظ</button>
            <a href="{{ route('admin.assignments.show', $assignment) }}" class="rounded-xl border px-4 py-2.5 text-sm">رجوع</a>
        </div>
    </form>
@endsection
