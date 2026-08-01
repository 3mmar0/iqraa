<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
    @csrf
    @method('PUT')
    <input type="hidden" name="tab" value="theme">

    <div>
        <label for="theme_primary" class="mb-1 block text-sm font-medium text-slate-700">اللون الأساسي</label>
        <input type="color" name="theme_primary" id="theme_primary" value="{{ old('theme_primary', $settings['theme.primary'] ?? '#2A9D8F') }}"
            class="h-10 w-24 rounded border border-slate-200">
    </div>

    <button type="submit" class="rounded-xl bg-teal-600 px-4 py-2 text-sm font-medium text-white hover:bg-teal-700">حفظ</button>
</form>
