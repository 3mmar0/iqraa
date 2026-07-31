@extends('layouts.app')
@section('title', 'المصروفات')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">المصروفات</h1>
    @if ($expenses->isEmpty())
        <x-empty-state message="لا مصروفات." />
    @else
        <ul class="space-y-2">
            @foreach ($expenses as $expense)
                <li class="rounded-xl border border-slate-200 bg-white p-4 text-sm">{{ $expense->category }} · {{ $expense->amount }} · {{ $expense->payee }}</li>
            @endforeach
        </ul>
    @endif
@endsection