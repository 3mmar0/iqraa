<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    @include('components.meta')

    <title>@yield('title', config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3">
            <a href="{{ url('/') }}" class="text-lg font-semibold text-teal-800">{{ config('app.name') }}</a>
            <div class="hidden sm:block">
                @include('components.dashboard-switcher')
            </div>
            <nav class="flex items-center gap-4 text-sm">
                @auth
                    <span>{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-teal-700 hover:underline">تسجيل الخروج</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-teal-700 hover:underline">تسجيل الدخول</a>
                    <a href="{{ route('register') }}" class="rounded bg-teal-700 px-3 py-1.5 text-white">إنشاء حساب</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8">
        @include('components.alert')
        @yield('content')
    </main>
</body>
</html>
