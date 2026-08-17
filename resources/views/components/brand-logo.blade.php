@props([
    'href' => null,
    'size' => 'md',
])

@php
    $sizes = [
        'xs' => 'text-xl',
        'sm' => 'text-2xl sm:text-3xl',
        'md' => 'text-3xl',
        'lg' => 'text-4xl sm:text-5xl',
        'xl' => 'text-5xl sm:text-6xl',
        'hero' => 'text-6xl sm:text-7xl md:text-8xl',
        'sidebar' => 'text-3xl',
        'footer' => 'text-4xl sm:text-5xl',
        'guest' => 'text-5xl sm:text-6xl',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $name = config('app.name');
    $tagClass = trim('site-brand inline-flex shrink-0 items-center font-display font-bold leading-none tracking-tight text-[var(--color-primary)] '.$sizeClass.' '.$attributes->get('class'));
@endphp

@if ($href)
    <a href="{{ $href }}" class="{{ $tagClass }} focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-primary)]" aria-label="{{ $name }}">
        {{ $name }}
    </a>
@else
    <span {{ $attributes->except('class')->merge(['class' => $tagClass]) }} aria-label="{{ $name }}">
        {{ $name }}
    </span>
@endif
