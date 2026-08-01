<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
    @csrf
    @method('PUT')
    <input type="hidden" name="tab" value="email">

    <div>
        <label for="email_from_name" class="mb-1 block text-sm font-medium text-slate-700">اسم المرسل</label>
        <input type="text" name="email_from_name" id="email_from_name" value="{{ old('email_from_name', $settings['email.from_name'] ?? '') }}"
            class="w-full max-w-md rounded-xl border border-slate-200 px-3 py-2 text-sm">
    </div>

    <div>
        <label for="email_from_address" class="mb-1 block text-sm font-medium text-slate-700">عنوان المرسل</label>
        <input type="email" name="email_from_address" id="email_from_address" value="{{ old('email_from_address', $settings['email.from_address'] ?? '') }}"
            class="w-full max-w-md rounded-xl border border-slate-200 px-3 py-2 text-sm">
    </div>

    <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-4 py-2 text-sm font-medium text-white hover:bg-[var(--color-primary-hover)]">حفظ</button>
</form>
