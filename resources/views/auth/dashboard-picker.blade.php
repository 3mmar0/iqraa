@extends('layouts.guest')

@section('title', 'اختر اللوحة')

@section('content')
    <header class="guest-head">
        <h1 class="guest-title">اختر لوحة التحكم</h1>
        <p class="guest-lead">حسابك مرتبط بأكثر من دور — اختر اللوحة المناسبة الآن.</p>
    </header>

    <form method="POST" action="{{ route('dashboard.choose') }}" class="guest-form">
        @csrf
        @foreach ($dashboards as $key)
            <button type="submit" name="dashboard" value="{{ $key }}"
                    class="guest-segment-option !flex w-full justify-start text-right hover:border-[var(--color-primary)] hover:bg-[var(--color-primary-light)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-primary)]">
                <span class="guest-segment-title">{{ $labels[$key] ?? $key }}</span>
            </button>
        @endforeach
    </form>
@endsection
