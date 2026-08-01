<dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 text-sm">
    <div class="rounded-xl bg-slate-50 p-4">
        <dt class="text-slate-500">مقررات نشطة</dt>
        <dd class="mt-1 text-2xl font-bold text-slate-900">{{ $tabData['enrollments_count'] ?? 0 }}</dd>
    </div>
    <div class="rounded-xl bg-slate-50 p-4">
        <dt class="text-slate-500">الطلبات</dt>
        <dd class="mt-1 text-2xl font-bold text-slate-900">{{ $tabData['orders_count'] ?? 0 }}</dd>
    </div>
    <div class="rounded-xl bg-slate-50 p-4">
        <dt class="text-slate-500">الاشتراك</dt>
        <dd class="mt-1 font-medium text-slate-900">
            @if ($tabData['active_subscription'] ?? null)
                {{ $tabData['active_subscription']->plan_code }} · نشط
            @else
                لا يوجد
            @endif
        </dd>
    </div>
    <div class="rounded-xl bg-slate-50 p-4">
        <dt class="text-slate-500">تاريخ التسجيل</dt>
        <dd class="mt-1 font-medium text-slate-900">{{ $student->created_at?->format('Y-m-d H:i') ?? '—' }}</dd>
    </div>
</dl>

<div class="mt-6 grid gap-4 sm:grid-cols-2 text-sm">
    <div><span class="text-slate-500">السنة الدراسية:</span> <span class="font-medium">{{ $student->academicYear?->name ?? '—' }}</span></div>
    <div><span class="text-slate-500">الفصل:</span> <span class="font-medium">{{ $student->semester?->name ?? '—' }}</span></div>
    <div><span class="text-slate-500">المصدر:</span> <span class="font-medium">{{ $student->creation_source === 'admin_created' ? 'إداري' : 'تسجيل ذاتي' }}</span></div>
    <div><span class="text-slate-500">الحالة:</span> <span class="font-medium">{{ $student->status }}</span></div>
</div>
