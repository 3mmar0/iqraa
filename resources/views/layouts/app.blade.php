@php
    $navLinks = [
        ['label' => 'الرئيسية', 'route' => 'home', 'match' => 'home'],
        ['label' => 'المقررات', 'route' => 'public.courses.index', 'match' => 'public.courses.*'],
        ['label' => 'المحاضرون', 'route' => 'public.instructors', 'match' => 'public.instructors'],
        ['label' => 'كيف تعمل', 'route' => 'public.how-it-works', 'match' => 'public.how-it-works'],
        ['label' => 'من نحن', 'route' => 'public.about', 'match' => 'public.about'],
        ['label' => 'الأسئلة', 'route' => 'public.faq', 'match' => 'public.faq'],
        ['label' => 'تواصل', 'route' => 'public.contact', 'match' => 'public.contact'],
    ];
    $isHome = request()->routeIs('home');
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    @include('components.meta')
    <title>@yield('title', config('app.name')) — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .site-brand { font-family: 'Tajawal', ui-sans-serif, system-ui, sans-serif; font-weight: 800; }
        @keyframes home-rise {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .home-rise { animation: home-rise 0.65s cubic-bezier(0.22, 1, 0.36, 1) both; }
        .home-rise-delay { animation: home-rise 0.65s 0.1s cubic-bezier(0.22, 1, 0.36, 1) both; }
        .home-rise-delay-2 { animation: home-rise 0.65s 0.2s cubic-bezier(0.22, 1, 0.36, 1) both; }
        @media (prefers-reduced-motion: reduce) {
            .home-rise, .home-rise-delay, .home-rise-delay-2 { animation: none; }
        }
    </style>
</head>
<body class="min-h-screen bg-[var(--color-sand)] font-sans text-[var(--color-ink)] antialiased" x-data="{ navOpen: false }">
@if ($isHome)
<!--
THESIS: Reading-room calm — atmosphere hero then shelf catalog; refuses empty SaaS gradient chrome.
OWN-WORLD: Official palette Teal #2A9D8F / Blue #4F8FBF / Sage #A8C3A0 / Light #F4F6F8 / Dark Text #2F3A45 / Soft #DDEEEB; soft radii; stack atmosphere imagery.
STORY: Visitor feels a quiet learning hall, sees courses on the shelf, browses or registers.
FIRST VIEWPORT: Full-bleed reading-room photo under soft light wash; brand huge; one headline; dual CTAs.
FORM: Reading Room Catalog + official palette; polish of home; seed 3a712c27 lineage.
FINISH: unreviewed and undocumented is unfinished; this build ends with the finish review, the verdict, and DESIGN.md
-->
@endif
    <header class="sticky top-0 z-40 border-b border-[var(--color-line)] bg-white text-[var(--color-ink)]">
        <div class="mx-auto flex h-16 w-full max-w-[90rem] items-center gap-4 px-4 sm:h-20 sm:px-6 lg:px-8">
            <x-brand-logo href="{{ route('home') }}" size="sm" />

            <nav class="hidden flex-1 items-center justify-center gap-1 lg:flex" aria-label="القائمة الرئيسية">
                @foreach ($navLinks as $link)
                    @php $active = request()->routeIs($link['match']); @endphp
                    <a href="{{ route($link['route']) }}"
                       @if ($active) aria-current="page" @endif
                       class="px-3 py-1.5 text-sm transition {{ $active
                            ? 'font-semibold text-[var(--color-primary)]'
                            : 'text-[var(--color-text-secondary)] hover:text-[var(--color-ink)]' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="ms-auto flex items-center gap-2 text-sm lg:ms-0">
                @auth
                    <a href="{{ route('dashboard.redirect') }}" class="rounded-xl bg-[var(--color-primary-light)] px-3 py-1.5 font-medium text-[var(--color-primary-hover)] hover:bg-[var(--color-primary)]/15">لوحتي</a>
                    <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                        @csrf
                        <button type="submit" class="px-2 py-1.5 text-[var(--color-text-secondary)] hover:text-[var(--color-ink)]">خروج</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hidden px-2 py-1.5 text-[var(--color-secondary)] hover:text-[var(--color-secondary-hover)] sm:inline">تسجيل الدخول</a>
                    <a href="{{ route('register') }}" class="rounded-xl bg-[var(--color-primary)] px-3 py-1.5 font-medium text-white hover:bg-[var(--color-primary-hover)]">إنشاء حساب</a>
                @endauth
                <button type="button"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-[var(--color-line)] lg:hidden"
                        @click="navOpen = !navOpen"
                        :aria-expanded="navOpen.toString()"
                        aria-controls="mobile-nav"
                        aria-label="القائمة">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        <div id="mobile-nav" x-show="navOpen" x-transition class="border-t border-[var(--color-line)] lg:hidden" style="display: none;">
            <nav class="mx-auto flex w-full max-w-[90rem] flex-col gap-0.5 px-4 py-2 sm:px-6 lg:px-8" aria-label="قائمة الجوال">
                @foreach ($navLinks as $link)
                    @php $active = request()->routeIs($link['match']); @endphp
                    <a href="{{ route($link['route']) }}"
                       @if ($active) aria-current="page" @endif
                       class="rounded-xl px-3 py-2.5 text-sm {{ $active ? 'bg-[var(--color-primary-light)] font-semibold text-[var(--color-primary-hover)]' : 'text-[var(--color-ink)] hover:bg-[var(--color-sand)]' }}"
                       @click="navOpen = false">{{ $link['label'] }}</a>
                @endforeach
                @guest
                    <a href="{{ route('login') }}" class="rounded-xl px-3 py-2.5 text-sm text-[var(--color-secondary)]" @click="navOpen = false">تسجيل الدخول</a>
                @endguest
            </nav>
        </div>
    </header>

    <main>
        <div class="mx-auto w-full max-w-[90rem] px-4 pt-4 sm:px-6 lg:px-8 empty:hidden">
            @include('components.alert')
        </div>
        @yield('content')
    </main>

    <footer class="mt-0 border-t border-[var(--color-line)] bg-[var(--color-ink)] text-white/80">
        <div class="mx-auto grid w-full max-w-[90rem] gap-10 px-4 py-14 sm:px-6 md:grid-cols-3 lg:px-8">
            <div>
                <x-brand-logo href="{{ route('home') }}" size="footer" />
                <p class="mt-3 max-w-xs text-sm leading-relaxed text-white/60">منصة تعلم عربية تمنحك مساراً واضحاً من الاكتشاف إلى الإتمام — بطمأنينة وخطوات مرتبة.</p>
            </div>
            <div>
                <p class="mb-3 text-sm font-semibold text-white">استكشف</p>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-white">الرئيسية</a></li>
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
            <div class="mx-auto flex w-full max-w-[90rem] flex-col gap-2 px-4 py-5 text-xs text-white/45 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                <p>© {{ date('Y') }} {{ config('app.name') }}</p>
                <a href="{{ route('register') }}" class="hover:text-white">ابدأ حسابك مجاناً</a>
            </div>
        </div>
    </footer>
</body>
</html>
