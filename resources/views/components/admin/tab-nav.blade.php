@props(['tabs' => []])

<nav {{ $attributes->merge(['class' => 'flex flex-wrap gap-1 border-b border-[var(--color-line)]']) }} aria-label="تبويبات الصفحة">
    @foreach ($tabs as $tab)
        <a
            href="{{ $tab['href'] }}"
            @class([
                'rounded-t-lg px-4 py-2.5 text-sm font-medium transition',
                'border-b-2 border-[var(--color-primary)] bg-[var(--color-primary-light)]/60 text-[var(--color-primary-hover)]' => $tab['active'] ?? false,
                'text-slate-600 hover:bg-[var(--color-sand)] hover:text-slate-900' => ! ($tab['active'] ?? false),
            ])
        >
            {{ $tab['label'] }}
        </a>
    @endforeach
</nav>
