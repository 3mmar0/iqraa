<div class="space-y-4">
    <p class="text-sm leading-6 text-slate-600">اطّلع على سجلات النظام والتدقيق من الصفحات المخصصة. لا توجد إعدادات للحفظ في هذا التبويب.</p>

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.system-logs.index') }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-[var(--color-primary-light)]">
            سجلات النظام
        </a>
        <a href="{{ route('admin.audit-logs.index') }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-[var(--color-primary-light)]">
            سجل التدقيق
        </a>
        <a href="{{ route('admin.ops.index') }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-[var(--color-primary-light)]">
            التشغيل
        </a>
    </div>
</div>
