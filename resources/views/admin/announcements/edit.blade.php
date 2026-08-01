@extends('layouts.admin')

@section('title', 'تعديل إعلان')
@section('heading', 'تعديل إعلان')
@section('subheading', $announcement->title)

@section('content')
    <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" class="mb-6 max-w-2xl rounded-2xl border bg-white p-6">
        @csrf
        @method('PUT')
        @include('admin.announcements._form', compact('courses', 'announcement'))
        <div class="mt-6 flex gap-2">
            <button class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm text-white">تحديث</button>
            <a href="{{ route('admin.announcements.index') }}" class="rounded-xl border px-4 py-2.5 text-sm">رجوع</a>
        </div>
    </form>

    <div class="grid max-w-2xl gap-4 sm:grid-cols-2">
        <form method="POST" action="{{ route('admin.announcements.schedule', $announcement) }}" class="rounded-2xl border bg-white p-4">
            @csrf
            <label class="mb-2 block text-sm font-medium">جدولة النشر</label>
            <input type="datetime-local" name="published_at" required class="mb-2 w-full rounded-xl border px-3 py-2 text-sm">
            <button class="rounded-xl border px-3 py-2 text-sm">جدولة</button>
        </form>
        <div class="flex flex-col gap-2 rounded-2xl border bg-white p-4">
            <form method="POST" action="{{ route('admin.announcements.pin', $announcement) }}">@csrf<button class="rounded-xl border px-3 py-2 text-sm">تثبيت</button></form>
            <form method="POST" action="{{ route('admin.announcements.archive', $announcement) }}">@csrf<button class="rounded-xl border px-3 py-2 text-sm">أرشفة</button></form>
        </div>
    </div>
@endsection
