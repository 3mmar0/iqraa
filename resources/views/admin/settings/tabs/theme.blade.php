<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
    @csrf
    @method('PUT')
    <input type="hidden" name="tab" value="theme">

    <div class="grid gap-4 sm:grid-cols-3">
        <div>
            <label for="theme_primary" class="mb-1 block text-sm font-medium text-slate-700">الأساسي (Teal)</label>
            <input type="color" name="theme_primary" id="theme_primary" value="{{ old('theme_primary', $settings['theme.primary'] ?? '#0F766E') }}"
                class="h-10 w-full rounded border border-slate-200">
            <p class="mt-1 text-xs text-slate-500">#0F766E — طمأنينة</p>
        </div>
        <div>
            <label for="theme_secondary" class="mb-1 block text-sm font-medium text-slate-700">الثانوي (Indigo)</label>
            <input type="color" name="theme_secondary" id="theme_secondary" value="{{ old('theme_secondary', $settings['theme.secondary'] ?? '#4F46E5') }}"
                class="h-10 w-full rounded border border-slate-200">
            <p class="mt-1 text-xs text-slate-500">#4F46E5 — علم وثقة</p>
        </div>
        <div>
            <label for="theme_accent" class="mb-1 block text-sm font-medium text-slate-700">التمييز (Amber)</label>
            <input type="color" name="theme_accent" id="theme_accent" value="{{ old('theme_accent', $settings['theme.accent'] ?? '#F59E0B') }}"
                class="h-10 w-full rounded border border-slate-200">
            <p class="mt-1 text-xs text-slate-500">#F59E0B — إنجاز وأمل</p>
        </div>
    </div>

    <button type="submit" class="rounded-xl bg-teal-600 px-4 py-2 text-sm font-medium text-white hover:bg-teal-700">حفظ</button>
</form>
