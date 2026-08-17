@props(['label', 'value', 'href' => null, 'hint' => null])

@php
    $classes = 'rounded-xl border border-[var(--color-line)] bg-[var(--color-surface)] p-5 shadow-[0_8px_24px_-16px_rgba(22,26,30,0.2)]';
    $classes .= $href ? ' group transition hover:-translate-y-0.5 hover:border-[var(--color-primary)]' : '';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        <p class="text-sm text-[var(--color-muted)]">{{ $label }}</p>
        <p class="mt-2 text-3xl font-bold text-[var(--color-text)]">{{ $value }}</p>
        @if ($hint)
            <p class="mt-1 text-xs text-[var(--color-primary)] group-hover:underline">{{ $hint }}</p>
        @endif
    </a>
@else
    <div {{ $attributes->merge(['class' => $classes]) }}>
        <p class="text-sm text-[var(--color-muted)]">{{ $label }}</p>
        <p class="mt-2 text-3xl font-bold text-[var(--color-text)]">{{ $value }}</p>
        @if ($hint)
            <p class="mt-1 text-xs text-[var(--color-muted)]">{{ $hint }}</p>
        @endif
    </div>
@endif
