@props([
    'title',
    'lead' => null,
    'dark' => false,
])

<section class="{{ $dark ? 'academy-hero' : 'border-b border-[var(--color-line)] bg-[var(--color-surface)]' }}">
    <div class="relative mx-auto max-w-[90rem] px-4 py-14 sm:px-6 sm:py-16 lg:px-8">
        @if ($dark)
            <div class="academy-rise max-w-3xl">
                <h1 class="academy-display text-3xl font-bold leading-tight text-white sm:text-4xl md:text-5xl">{{ $title }}</h1>
                @if ($lead)
                    <p class="mt-4 max-w-2xl text-base leading-relaxed text-white/70 sm:text-lg">{{ $lead }}</p>
                @endif
            </div>
        @else
            <div class="max-w-3xl">
                <h1 class="academy-display text-3xl font-bold tracking-tight text-[var(--color-text)] sm:text-4xl md:text-5xl">{{ $title }}</h1>
                @if ($lead)
                    <p class="mt-4 max-w-2xl text-base leading-relaxed text-[var(--color-text-secondary)] sm:text-lg">{{ $lead }}</p>
                @endif
            </div>
        @endif
        @if (isset($slot) && ! $slot->isEmpty())
            <div class="mt-6">{{ $slot }}</div>
        @endif
    </div>
</section>
