<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_8px_24px_-16px_rgba(12,31,28,0.45)]']) }}>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-100 text-sm text-slate-700">
            {{ $slot }}
        </table>
    </div>
</div>
