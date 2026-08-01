<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    @include('components.meta')
    <title>@yield('title', 'لوحة الإدارة') — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    $nav = [
        ['route' => 'admin.home', 'label' => 'الرئيسية', 'match' => 'admin.home', 'icon' => 'home'],
        ['route' => 'admin.users.index', 'label' => 'المستخدمون', 'match' => 'admin.users.*', 'icon' => 'users'],
        ['route' => 'admin.roles.index', 'label' => 'الأدوار والصلاحيات', 'match' => 'admin.roles.*', 'icon' => 'shield'],
        ['route' => 'admin.audit-logs.index', 'label' => 'سجل التدقيق', 'match' => 'admin.audit-logs.*', 'icon' => 'scroll'],
        ['route' => 'admin.ops.index', 'label' => 'التشغيل والمراقبة', 'match' => 'admin.ops.*', 'icon' => 'cpu'],
        ['route' => 'admin.comms.index', 'label' => 'الإشعارات والبريد', 'match' => 'admin.comms.*', 'icon' => 'bell'],
        ['route' => 'admin.security.index', 'label' => 'الأمان والنسخ', 'match' => 'admin.security.*', 'icon' => 'lock'],
    ];
@endphp
<body class="min-h-screen bg-[var(--color-sand)] text-slate-900 antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        {{-- Mobile overlay --}}
        <div
            x-show="sidebarOpen"
            x-transition.opacity
            class="fixed inset-0 z-40 bg-black/45 lg:hidden"
            @click="sidebarOpen = false"
            style="display: none;"
        ></div>

        {{-- Sidebar --}}
        <aside
            class="fixed inset-y-0 right-0 z-50 flex w-72 translate-x-full flex-col bg-[var(--color-ink)] text-white transition-transform duration-300 ease-out lg:static lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
        >
            <div class="flex items-center justify-between border-b border-white/10 px-5 py-5">
                <a href="{{ route('admin.home') }}" class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-500/20 text-teal-300 ring-1 ring-teal-400/30">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/></svg>
                    </span>
                    <span>
                        <span class="block text-sm font-semibold tracking-wide">{{ config('app.name') }}</span>
                        <span class="block text-xs text-teal-200/70">لوحة الإدارة</span>
                    </span>
                </a>
                <button type="button" class="rounded-lg p-2 text-teal-100 hover:bg-white/10 lg:hidden" @click="sidebarOpen = false" aria-label="إغلاق القائمة">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                @foreach ($nav as $item)
                    @php $active = request()->routeIs($item['match']); @endphp
                    <a
                        href="{{ route($item['route']) }}"
                        class="admin-nav-link {{ $active ? 'is-active' : '' }}"
                        @click="sidebarOpen = false"
                    >
                        @include('admin.partials.nav-icon', ['icon' => $item['icon']])
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
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
                        <button type="submit" class="rounded-lg bg-white/10 px-2.5 py-1.5 text-xs text-teal-50 hover:bg-white/15" title="تسجيل الخروج">خروج</button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main --}}
        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-30 border-b border-[var(--color-line)] bg-white/90 backdrop-blur">
                <div class="flex items-center justify-between gap-3 px-4 py-3 sm:px-6">
                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-[var(--color-line)] bg-white text-slate-700 shadow-sm lg:hidden"
                            @click="sidebarOpen = true"
                            aria-label="فتح القائمة"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <div>
                            <h1 class="text-lg font-semibold text-slate-900 sm:text-xl">@yield('heading', 'لوحة الإدارة')</h1>
                            @hasSection('subheading')
                                <p class="text-sm text-slate-500">@yield('subheading')</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @yield('header-actions')
                    </div>
                </div>
            </header>

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                @include('components.alert')
                <div class="admin-enter">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>
