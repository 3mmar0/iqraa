<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
    @csrf
    @method('PUT')
    <input type="hidden" name="tab" value="telegram">

    <label class="flex items-center gap-3 text-sm text-slate-700">
        <input type="hidden" name="telegram_enabled" value="0">
        <input type="checkbox" name="telegram_enabled" value="1" @checked(old('telegram_enabled', $settings['telegram.enabled'] ?? false)) class="rounded border-slate-300 text-[var(--color-primary)]">
        تفعيل تيليجرام
    </label>

    <div>
        <label for="telegram_bot_token" class="mb-1 block text-sm font-medium text-slate-700">رمز البوت</label>
        <input type="password" name="telegram_bot_token" id="telegram_bot_token" value="{{ old('telegram_bot_token', $settings['telegram.bot_token'] ?? '') }}"
            class="w-full max-w-md rounded-xl border border-slate-200 px-3 py-2 text-sm" autocomplete="off">
        <p class="mt-1 text-xs text-slate-500">يُخفى في الواجهة بعد الحفظ.</p>
    </div>

    <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-4 py-2 text-sm font-medium text-white hover:bg-[var(--color-primary-hover)]">حفظ</button>
</form>
