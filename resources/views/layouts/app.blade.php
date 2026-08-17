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
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .site-brand { font-family: var(--font-display); font-weight: 700; }
    </style>
</head>
<body class="min-h-screen bg-[var(--color-sand)] font-sans text-[var(--color-text)] antialiased" x-data="{ navOpen: false }">
@if ($isHome)
<!--
THESIS: Academy night court — ceremonial header, program tracks, rich course cards; refuses Moodle clutter.
OWN-WORLD: Night ink #161A1E, limestone #EDE7DC, parchment #F7F3EA, brass #C4A35A; Amiri display + Tajawal UI.
STORY: Visitor sees academy promise, picks a track, browses courses, registers or requests enrollment.
FIRST VIEWPORT: Night header; ink hero with brand + promise + brass CTA; tracks immediately below.
FORM: Al-Borhan IA + Night Court brass; academy redesign plan.
FINISH: unreviewed and undocumented is unfinished; this build ends with the finish review, the verdict, and DESIGN.md
-->
@endif
    <header class="academy-header">
        <div class="mx-auto flex h-16 w-full max-w-[90rem] items-center gap-4 px-4 sm:h-[4.5rem] sm:px-6 lg:px-8">
            <x-brand-logo href="{{ route('home') }}" size="sm" />

            <nav class="hidden flex-1 items-center justify-center gap-0.5 lg:flex" aria-label="القائمة الرئيسية">
                @foreach ($navLinks as $link)
                    @php $active = request()->routeIs($link['match']); @endphp
                    <a href="{{ route($link['route']) }}"
                       @if ($active) aria-current="page" @endif
                       class="academy-header-link {{ $active ? 'is-active' : '' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="ms-auto flex items-center gap-2 text-sm lg:ms-0">
                @auth
                    <a href="{{ route('dashboard.redirect') }}" class="hidden rounded-xl border border-white/15 px-3 py-1.5 font-medium text-white/90 hover:bg-white/8 sm:inline">لوحتي</a>
                    <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                        @csrf
                        <button type="submit" class="px-2 py-1.5 text-white/60 hover:text-white">خروج</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hidden px-2 py-1.5 text-white/75 hover:text-white sm:inline">تسجيل الدخول</a>
                    <a href="{{ route('register') }}" class="academy-btn-primary !py-2 !text-sm">إنشاء حساب</a>
                @endauth
                <button type="button"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-white/15 text-white lg:hidden"
                        @click="navOpen = !navOpen"
                        :aria-expanded="navOpen.toString()"
                        aria-controls="mobile-nav"
                        aria-label="القائمة">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        <div id="mobile-nav" x-show="navOpen" x-transition class="border-t border-white/10 lg:hidden" style="display: none;">
            <nav class="mx-auto flex w-full max-w-[90rem] flex-col gap-0.5 px-4 py-2 sm:px-6 lg:px-8" aria-label="قائمة الجوال">
                @foreach ($navLinks as $link)
                    @php $active = request()->routeIs($link['match']); @endphp
                    <a href="{{ route($link['route']) }}"
                       @if ($active) aria-current="page" @endif
                       class="rounded-xl px-3 py-2.5 text-sm {{ $active ? 'bg-white/10 font-semibold text-[var(--color-primary)]' : 'text-white/90 hover:bg-white/5' }}"
                       @click="navOpen = false">{{ $link['label'] }}</a>
                @endforeach
                @guest
                    <a href="{{ route('login') }}" class="rounded-xl px-3 py-2.5 text-sm text-[var(--color-primary)]" @click="navOpen = false">تسجيل الدخول</a>
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

    <footer class="academy-footer">
        <div class="mx-auto grid w-full max-w-[90rem] gap-10 px-4 py-14 sm:px-6 md:grid-cols-2 lg:grid-cols-4 lg:px-8">
            <div class="md:col-span-2 lg:col-span-1">
                <x-brand-logo href="{{ route('home') }}" size="footer" />
                <p class="mt-3 max-w-xs text-sm leading-relaxed text-white/55">منصة تعلم عربية تمنحك مساراً واضحاً من الاكتشاف إلى الإتمام — بطمأنينة وخطوات مرتبة.</p>
            </div>
            <div>
                <p class="mb-3 text-sm font-semibold text-[var(--color-primary)]">استكشف</p>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-white">الرئيسية</a></li>
                    <li><a href="{{ route('public.courses.index') }}" class="hover:text-white">كل المقررات</a></li>
                    <li><a href="{{ route('public.instructors') }}" class="hover:text-white">المحاضرون</a></li>
                    <li><a href="{{ route('public.how-it-works') }}" class="hover:text-white">كيف تعمل المنصة</a></li>
                </ul>
            </div>
            <div>
                <p class="mb-3 text-sm font-semibold text-[var(--color-primary)]">الأكاديمية</p>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('public.about') }}" class="hover:text-white">من نحن</a></li>
                    <li><a href="{{ route('public.faq') }}" class="hover:text-white">الأسئلة الشائعة</a></li>
                    <li><a href="{{ route('public.contact') }}" class="hover:text-white">تواصل معنا</a></li>
                </ul>
            </div>
            <div>
                <p class="mb-3 text-sm font-semibold text-[var(--color-primary)]">روابط سريعة</p>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('public.privacy') }}" class="hover:text-white">سياسة الخصوصية</a></li>
                    <li><a href="{{ route('public.terms') }}" class="hover:text-white">شروط الاستخدام</a></li>
                    @guest
                        <li><a href="{{ route('register') }}" class="hover:text-white">إنشاء حساب</a></li>
                    @endguest
                </ul>
            </div>
        </div>
        <div class="border-t border-white/10">
            <div class="mx-auto flex w-full max-w-[90rem] flex-col gap-2 px-4 py-5 text-xs text-white/40 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                <p>© {{ date('Y') }} {{ config('app.name') }} — جميع الحقوق محفوظة</p>
                @guest
                    <a href="{{ route('register') }}" class="text-[var(--color-primary)] hover:text-white">ابدأ حسابك مجاناً</a>
                @endguest
            </div>
        </div>
    </footer>
</body>
</html>
