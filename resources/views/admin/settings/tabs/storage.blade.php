<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
    @csrf
    @method('PUT')
    <input type="hidden" name="tab" value="storage">

    <div>
        <label for="storage_disk" class="mb-1 block text-sm font-medium text-slate-700">قرص التخزين</label>
        <select name="storage_disk" id="storage_disk" class="w-full max-w-xs rounded-xl border border-slate-200 px-3 py-2 text-sm">
            @foreach (['local_private', 'local_public', 's3'] as $disk)
                <option value="{{ $disk }}" @selected(old('storage_disk', $settings['storage.disk'] ?? 'local_private') === $disk)>{{ $disk }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-4 py-2 text-sm font-medium text-white hover:bg-[var(--color-primary-hover)]">حفظ</button>
</form>
