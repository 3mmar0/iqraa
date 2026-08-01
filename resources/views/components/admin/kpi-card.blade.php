@props(['label', 'value', 'href' => null, 'hint' => null])

@php
    $classes = 'rounded-2xl border border-[var(--color-line)] bg-white p-5 shadow-[0_8px_24px_-16px_rgba(47,58,69,0.35)]';
    $classes .= $href ? ' group transition hover:-translate-y-0.5 hover:border-[var(--color-primary)]' : '';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        <p class="text-sm text-slate-500">{{ $label }}</p>
        <p class="mt-2 text-3xl font-bold text-slate-900">{{ $value }}</p>
        @if ($hint)
            <p class="mt-1 text-xs text-[var(--color-primary)] group-hover:underline">{{ $hint }}</p>
        @endif
    </a>
@else
    <div {{ $attributes->merge(['class' => $classes]) }}>
        <p class="text-sm text-slate-500">{{ $label }}</p>
        <p class="mt-2 text-3xl font-bold text-slate-900">{{ $value }}</p>
        @if ($hint)
            <p class="mt-1 text-xs text-slate-500">{{ $hint }}</p>
        @endif
    </div>
@endif
