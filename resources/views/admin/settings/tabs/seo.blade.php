<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
    @csrf
    @method('PUT')
    <input type="hidden" name="tab" value="seo">

    <div>
        <label for="seo_meta_description" class="mb-1 block text-sm font-medium text-slate-700">وصف المeta</label>
        <textarea name="seo_meta_description" id="seo_meta_description" rows="3"
            class="w-full max-w-lg rounded-xl border border-slate-200 px-3 py-2 text-sm">{{ old('seo_meta_description', $settings['seo.meta_description'] ?? '') }}</textarea>
    </div>

    <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-4 py-2 text-sm font-medium text-white hover:bg-[var(--color-primary-hover)]">حفظ</button>
</form>
