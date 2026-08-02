<div {{ $attributes->merge(['class' => 'admin-panel overflow-hidden']) }}>
    <div class="overflow-x-auto">
        <table class="admin-table min-w-full divide-y divide-slate-100 text-sm text-slate-700">
            {{ $slot }}
        </table>
    </div>
</div>
