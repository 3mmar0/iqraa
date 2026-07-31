@extends('layouts.app')
@section('title', 'ملفات الفريق')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">ملفات الفريق</h1>
    @if ($files->isEmpty())
        <x-empty-state message="لا ملفات." />
    @else
        <ul class="space-y-2">
            @foreach ($files as $file)
                <li class="rounded-xl border border-slate-200 bg-white p-4 text-sm">{{ $file->title }} · {{ $file->uploader?->name }}</li>
            @endforeach
        </ul>
    @endif
@endsection