@extends('layouts.support')
@section('title', 'البحث عن طالب')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">البحث عن طالب</h1>
    <form method="GET" class="mb-6 flex max-w-lg gap-2">
        <input type="search" name="q" value="{{ $q }}" placeholder="اسم أو بريد أو هاتف" class="flex-1 rounded border border-slate-300 px-3 py-2">
        <button type="submit" class="rounded bg-teal-700 px-4 py-2 text-white">بحث</button>
    </form>
    @if ($students->isEmpty())
        <x-empty-state message="لا نتائج." />
    @else
        <ul class="space-y-2">
            @foreach ($students as $student)
                <li class="rounded-xl border border-slate-200 bg-white p-4 text-sm">{{ $student->name }} · {{ $student->email }} · {{ $student->phone }} · {{ $student->status }}</li>
            @endforeach
        </ul>
    @endif
@endsection