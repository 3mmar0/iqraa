@props(['title', 'id'])

<section {{ $attributes->merge(['class' => 'rounded-2xl border border-[var(--color-line)] bg-white p-5 shadow-[0_8px_24px_-16px_rgba(12,31,28,0.45)]']) }}>
    <h2 class="mb-4 text-base font-semibold text-slate-900">{{ $title }}</h2>
    <div class="relative h-64 w-full">
        <canvas id="{{ $id }}" aria-label="{{ $title }}"></canvas>
    </div>
</section>
