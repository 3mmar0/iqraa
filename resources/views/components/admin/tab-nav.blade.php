@props(['tabs' => []])

<nav {{ $attributes->merge(['class' => 'admin-panel flex flex-wrap gap-1 p-1.5']) }} aria-label="تبويبات الصفحة">
    @foreach ($tabs as $tab)
        <a
            href="{{ $tab['href'] }}"
            @class([
                'rounded-xl px-3.5 py-2 text-sm font-semibold transition',
                'bg-[var(--color-primary)] text-white shadow-sm' => $tab['active'] ?? false,
                'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => ! ($tab['active'] ?? false),
            ])
        >
            {{ $tab['label'] }}
        </a>
    @endforeach
</nav>
