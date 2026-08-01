<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
    @csrf
    @method('PUT')
    <input type="hidden" name="tab" value="maintenance">

    <div>
        <label for="maintenance_message" class="mb-1 block text-sm font-medium text-slate-700">رسالة الصيانة</label>
        <textarea name="maintenance_message" id="maintenance_message" rows="3" required
            class="w-full max-w-lg rounded-xl border border-slate-200 px-3 py-2 text-sm">{{ old('maintenance_message', $settings['maintenance.message'] ?? '') }}</textarea>
        <p class="mt-1 text-xs text-slate-500">تُعرض للزوار عند تفعيل وضع الصيانة من تبويب المنصة.</p>
    </div>

    <button type="submit" class="rounded-xl bg-teal-600 px-4 py-2 text-sm font-medium text-white hover:bg-teal-700">حفظ</button>
</form>
