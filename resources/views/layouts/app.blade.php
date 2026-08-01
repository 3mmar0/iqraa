@php
    $navLinks = [
        ['label' => 'المقررات', 'route' => 'public.courses.index'],
        ['label' => 'المحاضرون', 'route' => 'public.instructors'],
        ['label' => 'كيف تعمل', 'route' => 'public.how-it-works'],
        ['label' => 'من نحن', 'route' => 'public.about'],
        ['label' => 'الأسئلة', 'route' => 'public.faq'],
        ['label' => 'تواصل', 'route' => 'public.contact'],
    ];
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    @include('components.meta')
    <title>@yield('title', config('app.name')) — {{ config('app.name') }}</title>
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
        @keyframes soft-pan {
            from { transform: scale(1.05) translate3d(0, 0, 0); }
            to { transform: scale(1.12) translate3d(-2%, 1%, 0); }
        }
        .hero-wash { animation: soft-pan 18s ease-in-out infinite alternate; }
    </style>
</head>
<body class="min-h-screen bg-[var(--color-sand)] text-[var(--color-ink)] antialiased" x-data="{ navOpen: false }">
    <header class="sticky top-0 z-40 border-b border-white/10 bg-[var(--color-ink)]/95 text-white backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3 sm:px-6">
            <a href="{{ url('/') }}" class="site-brand text-2xl font-bold tracking-wide text-[var(--color-primary-light)]">{{ config('app.name') }}</a>

            <nav class="hidden items-center gap-1 lg:flex">
                @foreach ($navLinks as $link)
                    @php $active = request()->routeIs($link['route']); @endphp
                    <a href="{{ route($link['route']) }}"
                       class="rounded-lg px-2.5 py-1.5 text-sm {{ $active ? 'bg-white/10 text-white' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-2 text-sm">
                @auth
                    <a href="{{ route('dashboard.redirect') }}" class="rounded-lg bg-[var(--color-primary)]/25 px-3 py-1.5 text-[var(--color-primary-light)] ring-1 ring-[var(--color-primary)]/40 hover:bg-[var(--color-primary)]/35">لوحتي</a>
                    <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                        @csrf
                        <button type="submit" class="text-white/70 hover:text-white">خروج</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hidden text-white/80 hover:text-white sm:inline">تسجيل الدخول</a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-[var(--color-primary)] px-3 py-1.5 font-medium text-white hover:bg-[var(--color-primary-hover)]">إنشاء حساب</a>
                @endauth
                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-white/15 lg:hidden" @click="navOpen = !navOpen" :aria-expanded="navOpen.toString()" aria-label="القائمة">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        <div x-show="navOpen" x-transition class="border-t border-white/10 lg:hidden" style="display: none;">
            <div class="mx-auto flex max-w-6xl flex-col gap-1 px-4 py-3 sm:px-6">
                @foreach ($navLinks as $link)
                    <a href="{{ route($link['route']) }}" class="rounded-lg px-3 py-2 text-sm text-white/90 hover:bg-white/10" @click="navOpen = false">{{ $link['label'] }}</a>
                @endforeach
                @guest
                    <a href="{{ route('login') }}" class="rounded-lg px-3 py-2 text-sm text-white/90 hover:bg-white/10">تسجيل الدخول</a>
                @endguest
            </div>
        </div>
    </header>

    <main>
        <div class="mx-auto max-w-6xl px-4 pt-4 sm:px-6 empty:hidden">
            @include('components.alert')
        </div>
        @yield('content')
    </main>

    <footer class="mt-20 border-t border-[var(--color-line)] bg-[var(--color-ink)] text-white/80">
        <div class="mx-auto grid max-w-6xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-3">
            <div>
                <p class="site-brand text-2xl font-bold text-[var(--color-primary-light)]">{{ config('app.name') }}</p>
                <p class="mt-3 max-w-xs text-sm leading-relaxed text-white/60">منصة تعلم عربية تمنحك مساراً واضحاً من الاكتشاف إلى الإتمام — بطمأنينة وخطوات مرتبة.</p>
            </div>
            <div>
                <p class="mb-3 text-sm font-semibold text-white">استكشف</p>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('public.courses.index') }}" class="hover:text-white">كتالوج المقررات</a></li>
                    <li><a href="{{ route('public.instructors') }}" class="hover:text-white">المحاضرون</a></li>
                    <li><a href="{{ route('public.how-it-works') }}" class="hover:text-white">كيف تعمل المنصة</a></li>
                    <li><a href="{{ route('public.faq') }}" class="hover:text-white">الأسئلة الشائعة</a></li>
                </ul>
            </div>
            <div>
                <p class="mb-3 text-sm font-semibold text-white">المنصة</p>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('public.about') }}" class="hover:text-white">من نحن</a></li>
                    <li><a href="{{ route('public.contact') }}" class="hover:text-white">تواصل معنا</a></li>
                    <li><a href="{{ route('public.privacy') }}" class="hover:text-white">الخصوصية</a></li>
                    <li><a href="{{ route('public.terms') }}" class="hover:text-white">الشروط</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-white/10">
            <div class="mx-auto flex max-w-6xl flex-col gap-2 px-4 py-5 text-xs text-teal-100/50 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <p>© {{ date('Y') }} {{ config('app.name') }}</p>
                <a href="{{ route('register') }}" class="hover:text-teal-100">ابدأ حسابك مجاناً</a>
            </div>
        </div>
    </footer>
</body>
</html>
