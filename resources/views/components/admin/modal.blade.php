@props([
    'show' => 'open',
    'maxWidth' => 'max-w-lg',
    'close' => 'close()',
])

<template x-teleport="body">
    <div
        x-show="{{ $show }}"
        x-cloak
        class="admin-modal-backdrop"
        role="dialog"
        aria-modal="true"
        style="display: none;"
        x-transition.opacity.duration.200ms
        x-effect="document.body.classList.toggle('overflow-hidden', !!({{ $show }}))"
        @keydown.escape.window="if ({{ $show }}) { {{ $close }} }"
        {{ $attributes }}
    >
        <div class="absolute inset-0" @click="{{ $close }}" aria-hidden="true"></div>
        <div
            class="admin-modal-panel {{ $maxWidth }}"
            @click.stop
            x-show="{{ $show }}"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-6 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-6 sm:translate-y-0 sm:scale-95"
        >
            <div class="sticky top-0 z-10 flex items-start justify-between gap-3 border-b border-slate-100 bg-gradient-to-l from-[var(--color-teal-50)]/70 via-white to-white px-5 py-4">
                <div class="min-w-0 flex-1 pt-0.5">
                    {{ $header ?? '' }}
                </div>
                <button
                    type="button"
                    class="shrink-0 rounded-xl p-2 text-slate-500 transition hover:bg-white hover:text-slate-800"
                    aria-label="إغلاق"
                    @click="{{ $close }}"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="px-5 py-5">
                {{ $slot }}
            </div>
        </div>
    </div>
</template>
