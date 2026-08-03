<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')) — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .site-brand { font-family: 'Tajawal', ui-sans-serif, system-ui, sans-serif; font-weight: 800; }
        .guest-shell { --guest-wash: url('{{ asset('images/home/reading-room-wash.webp') }}'); }
    </style>
</head>
<body class="guest-shell font-sans text-[var(--color-ink)] antialiased">
    <main class="relative z-10 mx-auto flex min-h-dvh max-w-md flex-col justify-center px-4 py-12 sm:px-6">
        <div class="guest-rise mb-8 text-center">
            <a href="{{ url('/') }}" class="site-brand inline-block text-3xl tracking-tight text-[var(--color-primary)] sm:text-4xl">
                {{ config('app.name') }}
            </a>
            <p class="mt-2 text-sm text-[var(--color-text-secondary)]">منصة تعليمية عربية</p>
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-2xl border border-[var(--color-primary)]/25 bg-[var(--color-primary-light)] px-4 py-3 text-sm text-[var(--color-primary-hover)]" role="status">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-2xl border border-[var(--color-danger)]/30 bg-[var(--color-sand)] px-4 py-3 text-sm text-[var(--color-danger)]" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="guest-panel guest-rise-delay p-6 sm:p-8">
            @yield('content')
        </div>
    </main>
</body>
</html>
