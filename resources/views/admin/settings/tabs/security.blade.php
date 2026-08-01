<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
    @csrf
    @method('PUT')
    <input type="hidden" name="tab" value="security">

    <label class="flex items-center gap-3 text-sm text-slate-700">
        <input type="hidden" name="security_force_https" value="0">
        <input type="checkbox" name="security_force_https" value="1" @checked(old('security_force_https', $settings['security.force_https'] ?? true)) class="rounded border-slate-300 text-[var(--color-primary)]">
        إجبار HTTPS
    </label>

    <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-4 py-2 text-sm font-medium text-white hover:bg-[var(--color-primary-hover)]">حفظ</button>
</form>
