<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    @include('components.meta')
    <title>@yield('title', config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&family=Amiri:wght@700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .site-brand { font-family: 'Amiri', 'IBM Plex Sans Arabic', serif; }
        @keyframes rise {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .anim-rise { animation: rise 0.7s cubic-bezier(0.22, 1, 0.36, 1) both; }
        .anim-rise-delay { animation: rise 0.7s 0.12s cubic-bezier(0.22, 1, 0.36, 1) both; }
        .anim-rise-delay-2 { animation: rise 0.7s 0.24s cubic-bezier(0.22, 1, 0.36, 1) both; }
    </style>
</head>
<body class="min-h-screen bg-[var(--color-sand)] text-slate-900 antialiased">
    <header class="sticky top-0 z-40 border-b border-white/10 bg-[var(--color-ink)]/95 text-white backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3 sm:px-6">
            <a href="{{ url('/') }}" class="site-brand text-2xl font-bold tracking-wide text-teal-100">{{ config('app.name') }}</a>
            <nav class="flex items-center gap-3 text-sm">
                <a href="{{ route('public.courses.index') }}" class="hidden text-teal-100/80 hover:text-white sm:inline">المقررات</a>
                @auth
                    <a href="{{ route('dashboard.redirect') }}" class="rounded-lg bg-teal-500/20 px-3 py-1.5 text-teal-100 ring-1 ring-teal-400/30 hover:bg-teal-500/30">لوحتي</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-teal-100/70 hover:text-white">خروج</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-teal-100/80 hover:text-white">تسجيل الدخول</a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-teal-500 px-3 py-1.5 font-medium text-[var(--color-ink)] hover:bg-teal-400">إنشاء حساب</a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        @include('components.alert')
        @yield('content')
    </main>

    <footer class="mt-16 border-t border-[var(--color-line)] bg-white">
        <div class="mx-auto flex max-w-6xl flex-col gap-3 px-4 py-8 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <p class="site-brand text-lg text-[var(--color-ink)]">{{ config('app.name') }}</p>
            <div class="flex gap-4">
                <a href="{{ route('public.courses.index') }}" class="hover:text-teal-800">كتالوج المقررات</a>
                <a href="{{ route('login') }}" class="hover:text-teal-800">تسجيل الدخول</a>
            </div>
        </div>
    </footer>
</body>
</html>
