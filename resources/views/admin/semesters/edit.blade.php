@extends('layouts.admin')

@section('title', 'تعديل فصل')
@section('heading', 'تعديل فصل')
@section('subheading', $semester->name)

@section('content')
    <form method="POST" action="{{ route('admin.semesters.update', $semester) }}" class="max-w-2xl rounded-2xl border bg-white p-6">
        @csrf
        @method('PUT')
        @include('admin.semesters._form', ['years' => $years, 'semester' => $semester])
        <div class="mt-6 flex gap-2">
            <button class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm text-white">تحديث</button>
            <a href="{{ route('admin.semesters.index', ['academic_year_id' => $semester->academic_year_id]) }}" class="rounded-xl border px-4 py-2.5 text-sm">رجوع</a>
        </div>
    </form>
@endsection
