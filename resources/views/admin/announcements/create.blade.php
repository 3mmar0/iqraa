@extends('layouts.admin')

@section('title', 'إعلان جديد')
@section('heading', 'إعلان جديد')

@section('content')
    <form method="POST" action="{{ route('admin.announcements.store') }}" class="max-w-2xl rounded-2xl border bg-white p-6">
        @csrf
        @include('admin.announcements._form', compact('courses'))
        <div class="mt-6 flex gap-2">
            <button class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm text-white">حفظ كمسودة</button>
            <a href="{{ route('admin.announcements.index') }}" class="rounded-xl border px-4 py-2.5 text-sm">إلغاء</a>
        </div>
    </form>
@endsection
