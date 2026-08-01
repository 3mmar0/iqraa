<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
    @csrf
    @method('PUT')
    <input type="hidden" name="tab" value="backup">

    <label class="flex items-center gap-3 text-sm text-slate-700">
        <input type="hidden" name="backup_enabled" value="0">
        <input type="checkbox" name="backup_enabled" value="1" @checked(old('backup_enabled', $settings['backup.enabled'] ?? true)) class="rounded border-slate-300 text-teal-600">
        تفعيل النسخ الاحتياطي المجدول
    </label>

    <p class="text-sm text-slate-500">يتم تنفيذ النسخ عبر سكربتات الخادم. راجع قسم التشغيل للمراقبة.</p>

    <button type="submit" class="rounded-xl bg-teal-600 px-4 py-2 text-sm font-medium text-white hover:bg-teal-700">حفظ</button>
</form>
