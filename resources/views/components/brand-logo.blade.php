@props([
    'href' => null,
    'size' => 'md',
])

@php
    $heights = [
        'xs' => 'h-8',
        'sm' => 'h-10 sm:h-11',
        'md' => 'h-12',
        'lg' => 'h-20',
        'xl' => 'h-28 sm:h-32',
        'hero' => 'h-40 sm:h-52 md:h-60',
        'sidebar' => 'h-16',
        'footer' => 'h-20',
        'guest' => 'h-28 sm:h-36',
    ];
    $heightClass = $heights[$size] ?? $heights['md'];
    $alt = config('app.name').' — تعلم ما يطمئنك';
    $imgClass = trim($heightClass.' w-auto max-w-full object-contain '.$attributes->get('class'));
@endphp

@if ($href)
    <a href="{{ $href }}" class="inline-flex shrink-0 items-center focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-primary)]">
        <img
            src="{{ asset('images/logo.png') }}"
            alt="{{ $alt }}"
            class="{{ $imgClass }}"
            width="320"
            height="320"
            decoding="async"
        >
    </a>
@else
    <img
        src="{{ asset('images/logo.png') }}"
        alt="{{ $alt }}"
        {{ $attributes->except('class')->merge(['class' => $imgClass]) }}
        width="320"
        height="320"
        decoding="async"
    >
@endif
