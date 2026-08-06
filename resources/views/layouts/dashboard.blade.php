<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    @include('components.meta')
    <title>@yield('title', $dashboardLabel ?? 'لوحة التحكم') — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .site-brand { font-family: 'Tajawal', ui-sans-serif, system-ui, sans-serif; font-weight: 800; }
        @keyframes student-home-rise {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .student-home-rise { animation: student-home-rise 0.55s cubic-bezier(0.22, 1, 0.36, 1) both; }
        .student-home-rise-delay { animation: student-home-rise 0.55s 0.08s cubic-bezier(0.22, 1, 0.36, 1) both; }
        @media (prefers-reduced-motion: reduce) {
            .student-home-rise, .student-home-rise-delay { animation: none; }
        }
    </style>
</head>
<body class="min-h-screen bg-[var(--color-sand)] font-sans text-[var(--color-text)] antialiased" data-dashboard="{{ $dashboardTheme ?? 'student' }}" x-data="{ sidebarOpen: false }" :class="{ 'max-lg:overflow-hidden': sidebarOpen }">
    @if (session()->has('impersonator_id'))
        <div class="relative z-[60] bg-[var(--color-accent)] px-4 py-2 text-center text-sm font-medium text-amber-950">
            أنت تتصفح الآن كـ <strong>{{ auth()->user()->name }}</strong>
            <form method="POST" action="{{ route('impersonation.leave') }}" class="mr-3 inline">
                @csrf
                <button type="submit" class="rounded-lg bg-amber-950/10 px-3 py-1 text-xs font-semibold hover:bg-amber-950/20">العودة لحساب المدير</button>
            </form>
        </div>
    @endif

    <div class="flex min-h-screen" @keydown.escape.window="sidebarOpen = false">
        <div
            x-show="sidebarOpen"
            x-transition.opacity
            class="fixed inset-0 z-40 bg-black/45 lg:hidden"
            @click="sidebarOpen = false"
            style="display: none;"
            aria-hidden="true"
        ></div>

        <aside
            id="dashboard-sidebar"
            class="dashboard-sidebar fixed inset-y-0 right-0 z-50 flex w-72 flex-col text-white transition-transform duration-300 ease-out max-lg:translate-x-full lg:static lg:translate-x-0"
            :class="{ 'max-lg:!translate-x-0 max-lg:shadow-2xl': sidebarOpen }"
            :aria-hidden="(!sidebarOpen).toString()"
            @if(session()->has('impersonator_id')) style="top: 2.5rem;" @endif
        >
            <div class="flex items-center justify-between gap-3 border-b border-white/10 px-4 py-4">
                <div class="flex min-w-0 flex-1 flex-col gap-2">
                    <a href="{{ route($dashboardHome) }}" class="inline-flex w-fit max-w-full items-center rounded-xl bg-white/95 px-2.5 py-2">
                        <x-brand-logo size="sidebar" />
                    </a>
                    <span class="dashboard-brand-sub px-1 text-xs text-white/70">{{ $dashboardLabel }}</span>
                </div>
                <button type="button" class="rounded-lg p-2 text-teal-100 hover:bg-white/10 lg:hidden" @click="sidebarOpen = false" aria-label="إغلاق القائمة">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-4" aria-label="قائمة {{ $dashboardLabel }}">
                @php
                    $navIsGrouped = isset($dashboardNav[0]['items']);
                    $navGroups = $navIsGrouped
                        ? $dashboardNav
                        : [['section' => null, 'items' => $dashboardNav]];
                @endphp
                <div class="space-y-5">
                    @foreach ($navGroups as $group)
                        <div class="space-y-1">
                            @if (! empty($group['section']))
                                <p class="admin-nav-section">{{ $group['section'] }}</p>
                            @endif
                            @foreach ($group['items'] as $item)
                                @php
                                    $active = request()->routeIs($item['match']);
                                    if ($active && isset($item['query'])) {
                                        foreach ($item['query'] as $key => $value) {
                                            $current = request()->query($key, $key === 'type' ? 'students' : null);
                                            if ((string) $current !== (string) $value) {
                                                $active = false;
                                                break;
                                            }
                                        }
                                    }
                                    $url = isset($item['params']) ? route($item['route'], $item['params']) : route($item['route']);
                                @endphp
                                <a href="{{ $url }}" class="admin-nav-link {{ $active ? 'is-active' : '' }}" @click="sidebarOpen = false">
                                    @include('components.nav-icon', ['icon' => $item['icon'] ?? 'home'])
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </nav>

            <div class="border-t border-white/10 p-4">
                @include('components.dashboard-switcher', ['dark' => true])
                <div class="mt-3 flex items-center justify-between gap-2 rounded-xl bg-white/5 px-3 py-2.5">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-teal-200/60">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg bg-white/10 px-2.5 py-1.5 text-xs text-teal-50 hover:bg-white/15">خروج</button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-30 border-b border-[var(--color-line)] bg-white/90 backdrop-blur">
                <div class="flex items-center justify-between gap-3 px-4 py-3 sm:px-6">
                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            class="relative z-50 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-[var(--color-line)] bg-white text-slate-700 shadow-sm lg:hidden"
                            @click="sidebarOpen = !sidebarOpen"
                            :aria-expanded="sidebarOpen.toString()"
                            aria-controls="dashboard-sidebar"
                            aria-label="فتح القائمة"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <div>
                            <h1 class="text-lg font-semibold text-[var(--color-ink)] sm:text-xl">@yield('heading', $dashboardLabel)</h1>
                            @hasSection('subheading')
                                <p class="text-sm text-[var(--color-text-secondary)]">@yield('subheading')</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex max-w-[58%] flex-wrap items-center justify-end gap-2 sm:max-w-none">@yield('header-actions')</div>
                </div>
            </header>

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                @include('components.alert')
                <div class="admin-enter">@yield('content')</div>
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
