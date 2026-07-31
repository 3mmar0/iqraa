@extends('layouts.guest')

@section('title', 'اختر اللوحة')

@section('content')
    <h1 class="mb-4 text-xl font-semibold">اختر لوحة التحكم</h1>
    <form method="POST" action="{{ route('dashboard.choose') }}" class="space-y-3">
        @csrf
        @foreach ($dashboards as $key)
            <button type="submit" name="dashboard" value="{{ $key }}"
                    class="block w-full rounded-lg border border-slate-200 px-4 py-3 text-right hover:border-teal-600 hover:bg-teal-50">
                {{ $labels[$key] ?? $key }}
            </button>
        @endforeach
    </form>
@endsection
