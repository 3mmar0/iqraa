<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
    @csrf
    @method('PUT')
    <input type="hidden" name="tab" value="platform">

    <label class="flex items-center gap-3 text-sm text-slate-700">
        <input type="hidden" name="platform_maintenance_mode" value="0">
        <input type="checkbox" name="platform_maintenance_mode" value="1" @checked(old('platform_maintenance_mode', $settings['platform.maintenance_mode'] ?? false)) class="rounded border-slate-300 text-teal-600">
        وضع الصيانة
    </label>

    <label class="flex items-center gap-3 text-sm text-slate-700">
        <input type="hidden" name="platform_registration_open" value="0">
        <input type="checkbox" name="platform_registration_open" value="1" @checked(old('platform_registration_open', $settings['platform.registration_open'] ?? true)) class="rounded border-slate-300 text-teal-600">
        التسجيل مفتوح
    </label>

    <button type="submit" class="rounded-xl bg-teal-600 px-4 py-2 text-sm font-medium text-white hover:bg-teal-700">حفظ</button>
</form>
