@props(['message' => 'لا توجد عناصر بعد.', 'action' => null, 'actionLabel' => null])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-600']) }}>
    <p>{{ $message }}</p>
    @if ($action && $actionLabel)
        <a href="{{ $action }}" class="mt-3 inline-block text-teal-700 hover:underline">{{ $actionLabel }}</a>
    @endif
    {{ $slot }}
</div>