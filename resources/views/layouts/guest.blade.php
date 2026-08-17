<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')) — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="guest-shell font-sans text-[var(--color-text)] antialiased">
    <div class="guest-frame">
        <aside class="guest-aside" aria-hidden="true">
            <div class="absolute inset-0 bg-[var(--color-ink)]"></div>
            <div class="guest-aside-veil"></div>
            <div class="guest-aside-content">
                <x-brand-logo href="{{ url('/') }}" size="xl" />
                <p class="guest-aside-line academy-display">تعلّم بطمأنينة، وتقدّم بخطوات واضحة.</p>
                <p class="guest-aside-sub">مسار عربي مرتّب للمقررات والدروس والمتابعة — من أول خطوة حتى الإتمام.</p>
            </div>
        </aside>

        <div class="guest-stage">
            <div class="guest-stage-inner guest-rise">
                <div class="guest-mobile-brand lg:hidden">
                    <x-brand-logo href="{{ url('/') }}" size="lg" />
                </div>

                @if (session('status'))
                    <div class="guest-alert guest-alert-ok" role="status">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="guest-alert guest-alert-err" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="guest-panel guest-rise-delay">
                    @yield('content')
                </div>

                <p class="guest-home-link">
                    <a href="{{ url('/') }}">العودة إلى الرئيسية</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
