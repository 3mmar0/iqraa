@php
    $field = fn (string $key) => str_replace('.', '_', $key);
@endphp

<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
    @csrf
    @method('PUT')
    <input type="hidden" name="tab" value="general">

    <div>
        <label for="general_site_name" class="mb-1 block text-sm font-medium text-slate-700">اسم الموقع</label>
        <input type="text" name="general_site_name" id="general_site_name" value="{{ old('general_site_name', $settings['general.site_name'] ?? '') }}"
            class="w-full max-w-md rounded-xl border border-slate-200 px-3 py-2 text-sm">
    </div>

    <div>
        <label for="general_support_email" class="mb-1 block text-sm font-medium text-slate-700">بريد الدعم</label>
        <input type="email" name="general_support_email" id="general_support_email" value="{{ old('general_support_email', $settings['general.support_email'] ?? '') }}"
            class="w-full max-w-md rounded-xl border border-slate-200 px-3 py-2 text-sm">
    </div>

    <button type="submit" class="rounded-xl bg-teal-600 px-4 py-2 text-sm font-medium text-white hover:bg-teal-700">حفظ</button>
</form>
