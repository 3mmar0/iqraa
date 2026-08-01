<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
    @csrf
    @method('PUT')
    <input type="hidden" name="tab" value="media">

    <div>
        <label for="media_max_upload_mb" class="mb-1 block text-sm font-medium text-slate-700">الحد الأقصى للرفع (ميغابايت)</label>
        <input type="number" name="media_max_upload_mb" id="media_max_upload_mb" min="1" max="2048"
            value="{{ old('media_max_upload_mb', $settings['media.max_upload_mb'] ?? 200) }}"
            class="w-full max-w-xs rounded-xl border border-slate-200 px-3 py-2 text-sm">
    </div>

    <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-4 py-2 text-sm font-medium text-white hover:bg-[var(--color-primary-hover)]">حفظ</button>
</form>
