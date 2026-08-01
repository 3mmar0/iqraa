<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
    @csrf
    @method('PUT')
    <input type="hidden" name="tab" value="payments">

    <label class="flex items-center gap-3 text-sm text-slate-700">
        <input type="hidden" name="payments_manual_enabled" value="0">
        <input type="checkbox" name="payments_manual_enabled" value="1" @checked(old('payments_manual_enabled', $settings['payments.manual_enabled'] ?? true)) class="rounded border-slate-300 text-[var(--color-primary)]">
        تفعيل الدفع اليدوي
    </label>

    <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-4 py-2 text-sm font-medium text-white hover:bg-[var(--color-primary-hover)]">حفظ</button>
</form>
