@extends('layouts.guest')

@section('title', 'اختر اللوحة')

@section('content')
    <h1 class="text-2xl font-bold tracking-tight text-[var(--color-ink)]">اختر لوحة التحكم</h1>
    <p class="mt-2 text-sm leading-relaxed text-[var(--color-text-secondary)]">حسابك مرتبط بأكثر من دور — اختر اللوحة المناسبة الآن.</p>

    <form method="POST" action="{{ route('dashboard.choose') }}" class="mt-6 space-y-3">
        @csrf
        @foreach ($dashboards as $key)
            <button type="submit" name="dashboard" value="{{ $key }}"
                    class="block w-full rounded-2xl border border-[var(--color-line)] bg-[var(--color-sand)] px-4 py-3.5 text-right text-sm font-medium text-[var(--color-ink)] transition hover:border-[var(--color-primary)] hover:bg-[var(--color-primary-light)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-primary)]">
                {{ $labels[$key] ?? $key }}
            </button>
        @endforeach
    </form>
@endsection
