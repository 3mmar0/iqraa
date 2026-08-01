@extends('layouts.admin')

@section('title', 'فصل جديد')
@section('heading', 'فصل دراسي جديد')

@section('content')
    <form method="POST" action="{{ route('admin.semesters.store') }}" class="max-w-2xl rounded-2xl border bg-white p-6">
        @csrf
        @include('admin.semesters._form', ['years' => $years, 'selectedYearId' => $selectedYearId])
        <div class="mt-6 flex gap-2">
            <button class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm text-white">حفظ</button>
            <a href="{{ route('admin.semesters.index') }}" class="rounded-xl border px-4 py-2.5 text-sm">إلغاء</a>
        </div>
    </form>
@endsection
