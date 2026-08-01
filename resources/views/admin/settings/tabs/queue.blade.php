<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
    @csrf
    @method('PUT')
    <input type="hidden" name="tab" value="queue">

    <div>
        <label for="queue_driver" class="mb-1 block text-sm font-medium text-slate-700">محرك الطابور</label>
        <select name="queue_driver" id="queue_driver" class="w-full max-w-xs rounded-xl border border-slate-200 px-3 py-2 text-sm">
            @foreach (['redis', 'database', 'sync'] as $driver)
                <option value="{{ $driver }}" @selected(old('queue_driver', $settings['queue.driver'] ?? 'redis') === $driver)>{{ $driver }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-4 py-2 text-sm font-medium text-white hover:bg-[var(--color-primary-hover)]">حفظ</button>
</form>
