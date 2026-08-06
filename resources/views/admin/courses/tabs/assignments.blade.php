@php
    $statusLabels = ['draft' => 'مسودة', 'published' => 'منشور', 'archived' => 'مؤرشف'];
    $submissionStatusLabels = [
        'submitted' => 'مُسلَّم',
        'graded' => 'مُقيَّم',
        'resubmit_requested' => 'طلب إعادة',
    ];
    $assignmentsPayload = $course->assignments->map(fn ($a) => [
        'id' => $a->id,
        'title' => $a->title,
        'description' => $a->description ?? '',
        'lesson_id' => $a->lesson_id ? (string) $a->lesson_id : '',
        'lesson_title' => $a->lesson?->title,
        'due_at' => $a->due_at?->format('Y-m-d\TH:i'),
        'due_label' => $a->due_at?->format('Y-m-d H:i'),
        'status' => $a->status,
        'update_url' => route(($coursePanel ?? 'admin').'.assignments.update', $a),
        'destroy_url' => route(($coursePanel ?? 'admin').'.assignments.destroy', $a),
        'show_url' => route(($coursePanel ?? 'admin').'.assignments.show', $a),
        'submissions' => $a->relationLoaded('submissions')
            ? $a->submissions->map(fn ($s) => [
                'id' => $s->id,
                'student' => $s->user?->name ?? '—',
                'status' => $s->status,
                'score' => $s->score,
                'submitted_at' => $s->submitted_at?->format('Y-m-d H:i'),
                'is_late' => $a->due_at && $s->submitted_at && $s->submitted_at->gt($a->due_at),
                'body' => $s->body ?? '',
                'grade_url' => route(($coursePanel ?? 'admin').'.assignments.submissions.grade', [$a, $s]),
                'resubmit_url' => route(($coursePanel ?? 'admin').'.assignments.submissions.resubmit', [$a, $s]),
            ])->values()
            : [],
    ])->values();
    $lessonOptions = $course->lessons->map(fn ($l) => [
        'id' => (string) $l->id,
        'title' => $l->title,
    ])->values();
@endphp

<div
    class="space-y-4"
    x-data="{
        open: false,
        editing: null,
        detail: null,
        openCreate() { this.editing = null; this.open = true; },
        openEdit(item) { this.editing = item; this.open = true; },
        close() { this.open = false; this.editing = null; },
        openDetail(item) { this.detail = item; },
        closeDetail() { this.detail = null; },
        items: @js($assignmentsPayload),
        lessons: @js($lessonOptions),
        statusLabels: @js($statusLabels),
        submissionStatusLabels: @js($submissionStatusLabels),
    }"
    @keydown.escape.window="if (detail) { closeDetail() } else { close() }"
>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h3 class="font-semibold text-slate-900">الواجبات (<span x-text="items.length">{{ $course->assignments->count() }}</span>)</h3>
        <button type="button" @click="openCreate()" class="admin-btn admin-btn-primary">إضافة واجب</button>
    </div>

    <div class="space-y-3">
        <template x-for="item in items" :key="item.id">
            <div class="rounded-2xl border border-slate-200 bg-gradient-to-l from-slate-50/80 to-white p-4 transition hover:border-[var(--color-primary)]/30 hover:shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold text-slate-900" x-text="item.title"></p>
                        <p class="mt-1 text-xs text-slate-500">
                            <span x-text="statusLabels[item.status] || item.status"></span>
                            · التسليم: <span x-text="item.due_label || '—'"></span>
                            <span x-show="item.lesson_title"> · <span x-text="item.lesson_title"></span></span>
                            · <span x-text="(item.submissions || []).length"></span> تسليم
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" @click="openEdit(item)" class="admin-btn admin-btn-ghost admin-btn-sm">تعديل</button>
                        <button type="button" @click="openDetail(item)" class="admin-btn admin-btn-primary admin-btn-sm">التفاصيل والتسليمات</button>
                        <a :href="item.show_url" class="admin-btn admin-btn-ghost admin-btn-sm">صفحة مستقلة</a>
                        <form method="POST" :action="item.destroy_url" onsubmit="return confirm('حذف الواجب؟');">
                            @csrf
                            @method('DELETE')
                            @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'assignments'])
                            <button class="admin-btn admin-btn-danger admin-btn-sm">حذف</button>
                        </form>
                    </div>
                </div>
            </div>
        </template>
        <p x-show="items.length === 0" class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/50 py-12 text-center text-sm text-slate-500">لا واجبات بعد.</p>
    </div>

    <x-admin.modal show="open">
        <x-slot:header>
            <h3 class="text-base font-semibold text-slate-900" x-text="editing ? 'تعديل الواجب' : 'إضافة واجب'"></h3>
            <p class="mt-0.5 text-xs text-slate-500">أدخل بيانات الواجب ثم احفظ</p>
        </x-slot:header>

        <form method="POST" :action="editing ? editing.update_url : '{{ route(($coursePanel ?? 'admin').'.assignments.store') }}'" class="grid gap-4 sm:grid-cols-2" :key="editing ? ('a-'+editing.id) : 'a-new'">
            @csrf
            <template x-if="editing"><input type="hidden" name="_method" value="PUT"></template>
            @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'assignments'])
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <div class="sm:col-span-2">
                <label class="admin-label">العنوان</label>
                <input name="title" required class="admin-input" placeholder="عنوان الواجب" :value="editing?.title || ''">
            </div>
            <div class="sm:col-span-2">
                <label class="admin-label">الوصف</label>
                <textarea name="description" rows="3" class="admin-input" placeholder="وصف مختصر..." x-effect="$el.value = editing?.description || ''"></textarea>
            </div>
            <div>
                <label class="admin-label">الدرس (اختياري)</label>
                <select name="lesson_id" class="admin-input" x-effect="$el.value = editing?.lesson_id || ''">
                    <option value="">—</option>
                    <template x-for="lesson in lessons" :key="lesson.id">
                        <option :value="lesson.id" x-text="lesson.title"></option>
                    </template>
                </select>
            </div>
            <div>
                <label class="admin-label">موعد التسليم</label>
                <input type="datetime-local" name="due_at" required class="admin-input" :value="editing?.due_at || ''">
            </div>
            <div class="sm:col-span-2">
                <label class="admin-label">الحالة</label>
                <select name="status" class="admin-input" x-effect="$el.value = editing?.status || 'draft'">
                    @foreach ($statusLabels as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2 flex flex-wrap gap-2 border-t border-slate-100 pt-4">
                <button class="admin-btn admin-btn-primary" x-text="editing ? 'حفظ' : 'إضافة'"></button>
                <button type="button" @click="close()" class="admin-btn admin-btn-ghost">إلغاء</button>
            </div>
        </form>
    </x-admin.modal>

    <x-admin.modal show="detail" maxWidth="max-w-3xl" close="closeDetail()">
        <x-slot:header>
            <h3 class="text-base font-semibold text-slate-900" x-text="detail?.title || 'تفاصيل الواجب'"></h3>
            <p class="mt-0.5 text-xs text-slate-500">البيانات وتسليمات الطلاب</p>
        </x-slot:header>

        <div class="space-y-5" x-show="detail">
            <dl class="grid gap-3 sm:grid-cols-2">
                <div class="rounded-xl border border-slate-100 bg-slate-50/70 px-4 py-3">
                    <dt class="text-xs text-slate-500">الحالة</dt>
                    <dd class="mt-1 text-sm font-semibold" x-text="statusLabels[detail?.status] || detail?.status"></dd>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50/70 px-4 py-3">
                    <dt class="text-xs text-slate-500">موعد التسليم</dt>
                    <dd class="mt-1 text-sm font-semibold" x-text="detail?.due_label || '—'"></dd>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50/70 px-4 py-3">
                    <dt class="text-xs text-slate-500">الدرس</dt>
                    <dd class="mt-1 text-sm font-semibold" x-text="detail?.lesson_title || '—'"></dd>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50/70 px-4 py-3 sm:col-span-2">
                    <dt class="text-xs text-slate-500">الوصف</dt>
                    <dd class="mt-1 whitespace-pre-line text-sm text-slate-700" x-text="detail?.description || 'لا يوجد وصف.'"></dd>
                </div>
            </dl>

            <div>
                <h4 class="mb-3 font-semibold text-slate-900">التسليمات (<span x-text="(detail?.submissions || []).length"></span>)</h4>
                <div class="space-y-3">
                    <template x-for="sub in (detail?.submissions || [])" :key="sub.id">
                        <div class="rounded-xl border border-slate-200 p-3">
                            <div class="flex flex-wrap items-start justify-between gap-2">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900" x-text="sub.student"></p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        <span x-text="submissionStatusLabels[sub.status] || sub.status"></span>
                                        · <span x-text="sub.submitted_at || '—'"></span>
                                        <span x-show="sub.is_late" class="text-amber-700"> · متأخر</span>
                                        · الدرجة: <span x-text="sub.score ?? '—'"></span>
                                    </p>
                                    <p class="mt-2 text-xs text-slate-600 whitespace-pre-line" x-show="sub.body" x-text="sub.body"></p>
                                </div>
                            </div>
                            <div class="mt-3 flex flex-wrap items-end gap-2 border-t border-slate-100 pt-3">
                                <form method="POST" :action="sub.grade_url" class="flex flex-wrap items-end gap-2">
                                    @csrf
                                    @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'assignments'])
                                    <div>
                                        <label class="admin-label">الدرجة</label>
                                        <input type="number" min="0" max="100" step="0.01" name="score" required class="admin-input w-28" :value="sub.score ?? ''">
                                    </div>
                                    <button class="admin-btn admin-btn-primary admin-btn-sm">رصد الدرجة</button>
                                </form>
                                <form method="POST" :action="sub.resubmit_url" onsubmit="return confirm('طلب إعادة التسليم؟');">
                                    @csrf
                                    @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'assignments'])
                                    <button class="admin-btn admin-btn-ghost admin-btn-sm">طلب إعادة تسليم</button>
                                </form>
                            </div>
                        </div>
                    </template>
                    <p x-show="!(detail?.submissions || []).length" class="rounded-xl border border-dashed border-slate-200 py-8 text-center text-sm text-slate-500">لا تسليمات بعد.</p>
                </div>
            </div>
        </div>
    </x-admin.modal>
</div>
