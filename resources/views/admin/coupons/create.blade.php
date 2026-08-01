@extends('layouts.admin')

@section('title', 'كوبون جديد')
@section('heading', 'كوبون جديد')
@section('subheading', 'إنشاء كود خصم')

@section('content')
    <form method="POST" action="{{ route('admin.coupons.store') }}" class="max-w-2xl rounded-2xl border border-[var(--color-line)] bg-white p-6">
        @csrf
        @include('admin.coupons._form')
        <div class="mt-6 flex gap-2">
            <button class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm text-white">حفظ</button>
            <a href="{{ route('admin.coupons.index') }}" class="rounded-xl border px-4 py-2.5 text-sm">إلغاء</a>
        </div>
    </form>
@endsection
