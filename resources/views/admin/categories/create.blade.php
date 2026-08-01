@extends('layouts.admin')

@section('title', 'تصنيف جديد')
@section('heading', 'إنشاء تصنيف')

@section('content')
    <form method="POST" action="{{ route('admin.categories.store') }}" class="mx-auto max-w-2xl space-y-4 rounded-2xl border border-[var(--color-line)] bg-white p-6">
        @csrf
        @include('admin.categories._form', ['category' => null])
        <div class="flex gap-2">
            <button class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white">حفظ</button>
            <a href="{{ route('admin.categories.index') }}" class="rounded-xl border px-4 py-2.5 text-sm">إلغاء</a>
        </div>
    </form>
@endsection
