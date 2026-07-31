@extends('layouts.app')
@section('title', 'العمليات')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">العمليات</h1>
    <ul class="grid gap-3 sm:grid-cols-2">
        @foreach ($placeholders as $key => $label)
            <li class="rounded-xl border border-dashed border-slate-300 bg-white p-4 text-slate-600">{{ $label }} — قريباً</li>
        @endforeach
    </ul>
@endsection