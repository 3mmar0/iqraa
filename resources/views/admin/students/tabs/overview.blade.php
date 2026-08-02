@php
    $enrollmentsCount = $tabData['enrollments_count'] ?? $controls['enrollments_count'] ?? 0;
    $ordersCount = $tabData['orders_count'] ?? $controls['orders_count'] ?? 0;
    $activeSub = $tabData['active_subscription'] ?? $controls['active_subscription'] ?? null;
    $statusLabel = ['active' => 'نشط', 'invited' => 'مدعو', 'disabled' => 'معطّل'][$student->status] ?? $student->status;
@endphp

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <a href="{{ route('admin.students.show', ['student' => $student, 'tab' => 'courses']) }}"
       class="rounded-xl bg-slate-50 p-4 transition hover:bg-[var(--color-primary-light)]/40">
        <dt class="text-sm text-slate-500">مقررات نشطة</dt>
        <dd class="mt-1 text-2xl font-bold text-slate-900">{{ $enrollmentsCount }}</dd>
        <p class="mt-1 text-xs text-[var(--color-primary)]">إدارة المقررات ←</p>
    </a>
    <a href="{{ route('admin.students.show', ['student' => $student, 'tab' => 'orders']) }}"
       class="rounded-xl bg-slate-50 p-4 transition hover:bg-[var(--color-primary-light)]/40">
        <dt class="text-sm text-slate-500">الطلبات</dt>
        <dd class="mt-1 text-2xl font-bold text-slate-900">{{ $ordersCount }}</dd>
        <p class="mt-1 text-xs text-[var(--color-primary)]">عرض الطلبات ←</p>
    </a>
    <a href="{{ route('admin.students.show', ['student' => $student, 'tab' => 'payments']) }}"
       class="rounded-xl bg-slate-50 p-4 transition hover:bg-[var(--color-primary-light)]/40">
        <dt class="text-sm text-slate-500">الاشتراك</dt>
        <dd class="mt-1 font-medium text-slate-900">
            @if ($activeSub)
                {{ $activeSub->plan_code }} · نشط
            @else
                لا يوجد
            @endif
        </dd>
        <p class="mt-1 text-xs text-[var(--color-primary)]">المدفوعات ←</p>
    </a>
    <div class="rounded-xl bg-slate-50 p-4">
        <dt class="text-sm text-slate-500">تاريخ التسجيل</dt>
        <dd class="mt-1 font-medium text-slate-900">{{ $student->created_at?->format('Y-m-d H:i') ?? '—' }}</dd>
    </div>
</div>

<div class="mt-6 grid gap-4 sm:grid-cols-2 text-sm">
    <div class="rounded-xl border border-slate-100 p-4">
        <span class="text-slate-500">السنة الدراسية</span>
        <p class="mt-1 font-medium">{{ $student->academicYear?->name ?? '—' }}</p>
    </div>
    <div class="rounded-xl border border-slate-100 p-4">
        <span class="text-slate-500">الفصل</span>
        <p class="mt-1 font-medium">{{ $student->semester?->name ?? '—' }}</p>
    </div>
    <div class="rounded-xl border border-slate-100 p-4">
        <span class="text-slate-500">المصدر</span>
        <p class="mt-1 font-medium">{{ $student->creation_source === 'admin_created' ? 'إداري' : 'تسجيل ذاتي' }}</p>
    </div>
    <div class="rounded-xl border border-slate-100 p-4">
        <span class="text-slate-500">الحالة</span>
        <p class="mt-1 font-medium">{{ $statusLabel }}</p>
    </div>
</div>

@if ($student->admin_notes)
    <div class="mt-6 rounded-xl border border-amber-100 bg-amber-50/60 p-4">
        <div class="mb-2 flex items-center justify-between gap-2">
            <h3 class="text-sm font-semibold text-amber-950">آخر ملاحظة إدارية</h3>
            <a href="{{ route('admin.students.show', ['student' => $student, 'tab' => 'notes']) }}" class="text-xs text-amber-800 hover:underline">تعديل</a>
        </div>
        <p class="whitespace-pre-wrap text-sm text-amber-950/90">{{ \Illuminate\Support\Str::limit($student->admin_notes, 280) }}</p>
    </div>
@endif

<p class="mt-6 text-sm text-slate-500">استخدم اللوحة الجانبية لإسناد مقرر، تغيير التصنيف، إعادة كلمة المرور، أو حفظ ملاحظة — دون مغادرة الصفحة.</p>
