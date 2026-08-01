<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
    @csrf
    @method('PUT')
    <input type="hidden" name="tab" value="authentication">

    <label class="flex items-center gap-3 text-sm text-slate-700">
        <input type="hidden" name="authentication_email_verification_required" value="0">
        <input type="checkbox" name="authentication_email_verification_required" value="1"
            @checked(old('authentication_email_verification_required', $settings['authentication.email_verification_required'] ?? true))
            class="rounded border-slate-300 text-teal-600">
        يتطلب تأكيد البريد الإلكتروني
    </label>

    <button type="submit" class="rounded-xl bg-teal-600 px-4 py-2 text-sm font-medium text-white hover:bg-teal-700">حفظ</button>
</form>
