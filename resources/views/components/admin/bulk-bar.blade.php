<div {{ $attributes->merge(['class' => 'flex flex-wrap items-center justify-between gap-3 rounded-xl border border-[var(--color-primary)]/30 bg-[var(--color-primary-light)] px-4 py-3 text-sm text-[var(--color-ink)]']) }}>
    {{ $slot }}
</div>
