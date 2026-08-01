<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
    @csrf
    @method('PUT')
    <input type="hidden" name="tab" value="cache">

    <div>
        <label for="cache_driver" class="mb-1 block text-sm font-medium text-slate-700">محرك الذاكرة المؤقتة</label>
        <select name="cache_driver" id="cache_driver" class="w-full max-w-xs rounded-xl border border-slate-200 px-3 py-2 text-sm">
            @foreach (['redis', 'file', 'database'] as $driver)
                <option value="{{ $driver }}" @selected(old('cache_driver', $settings['cache.driver'] ?? 'redis') === $driver)>{{ $driver }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-4 py-2 text-sm font-medium text-white hover:bg-[var(--color-primary-hover)]">حفظ</button>
</form>
