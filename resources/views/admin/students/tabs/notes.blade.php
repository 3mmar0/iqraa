<form method="POST" action="{{ route('admin.students.notes', $student) }}" class="space-y-4">
    @csrf
    <div>
        <label for="admin_notes" class="mb-1 block text-sm font-medium text-slate-700">ملاحظات إدارية</label>
        <p class="mb-2 text-xs text-slate-500">مرئية للإدارة فقط — لا تظهر للطالب.</p>
        <textarea id="admin_notes" name="admin_notes" rows="10"
                  class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20"
                  placeholder="مثال: تواصل بخصوص الدفع، يحتاج متابعة في المقرر…">{{ old('admin_notes', $tabData['admin_notes'] ?? $student->admin_notes) }}</textarea>
    </div>
    <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">حفظ الملاحظات</button>
</form>
