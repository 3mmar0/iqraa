<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-b from-teal-50 to-slate-100 text-slate-900 antialiased">
    <main class="mx-auto flex min-h-screen max-w-md flex-col justify-center px-4 py-10">
        <div class="mb-6 text-center">
            <a href="{{ url('/') }}" class="text-2xl font-bold text-teal-800">{{ config('app.name') }}</a>
            <p class="mt-1 text-sm text-slate-600">منصة تعليمية عربية</p>
        </div>
        @include('components.alert')
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @yield('content')
        </div>
    </main>
</body>
</html>
