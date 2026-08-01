@extends('layouts.admin')

@section('title', 'تعديل مجموعة')
@section('heading', 'تعديل مجموعة')
@section('subheading', $group->name)

@section('content')
    <form method="POST" action="{{ route('admin.groups.update', $group) }}" class="max-w-2xl rounded-2xl border bg-white p-6">
        @csrf
        @method('PUT')
        @include('admin.groups._form', compact('years', 'semesters', 'group'))
        <div class="mt-6 flex gap-2">
            <button class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm text-white">تحديث</button>
            <a href="{{ route('admin.groups.show', $group) }}" class="rounded-xl border px-4 py-2.5 text-sm">رجوع</a>
        </div>
    </form>
@endsection
