@php
    $statusLabels = ['draft' => 'مسودة', 'published' => 'منشور', 'archived' => 'مؤرشف'];
    $assignmentsPayload = $course->assignments->map(fn ($a) => [
        'id' => $a->id,
        'title' => $a->title,
        'description' => $a->description ?? '',
        'lesson_id' => $a->lesson_id ? (string) $a->lesson_id : '',
        'lesson_title' => $a->lesson?->title,
        'due_at' => $a->due_at?->format('Y-m-d\TH:i'),
        'due_label' => $a->due_at?->format('Y-m-d H:i'),
        'status' => $a->status,
        'update_url' => route('admin.assignments.update', $a),
        'destroy_url' => route('admin.assignments.destroy', $a),
        'show_url' => route('admin.assignments.show', $a),
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
        openCreate() { this.editing = null; this.open = true; },
        openEdit(item) { this.editing = item; this.open = true; },
        close() { this.open = false; this.editing = null; },
        items: @js($assignmentsPayload),
        lessons: @js($lessonOptions),
        statusLabels: @js($statusLabels),
    }"
    @keydown.escape.window="close()"
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
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" @click="openEdit(item)" class="admin-btn admin-btn-ghost admin-btn-sm">تعديل</button>
                        <a :href="item.show_url" class="admin-btn admin-btn-ghost admin-btn-sm">عرض</a>
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

    <div x-show="open" x-cloak class="fixed inset-0 z-[80] flex items-end justify-center bg-black/45 p-4 sm:items-center" @click.self="close()">
        <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-5 shadow-xl" @click.stop>
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-semibold" x-text="editing ? 'تعديل الواجب' : 'إضافة واجب'"></h3>
                <button type="button" @click="close()" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" aria-label="إغلاق">✕</button>
            </div>
            <form method="POST" :action="editing ? editing.update_url : '{{ route('admin.assignments.store') }}'" class="grid gap-3 sm:grid-cols-2" :key="editing ? ('a-'+editing.id) : 'a-new'">
                @csrf
                <template x-if="editing"><input type="hidden" name="_method" value="PUT"></template>
                @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'assignments'])
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-xs text-slate-500">العنوان</label>
                    <input name="title" required class="w-full rounded-xl border px-3 py-2.5 text-sm" :value="editing?.title || ''">
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-xs text-slate-500">الوصف</label>
                    <textarea name="description" rows="3" class="w-full rounded-xl border px-3 py-2.5 text-sm" x-effect="$el.value = editing?.description || ''"></textarea>
                </div>
                <div>
                    <label class="mb-1 block text-xs text-slate-500">الدرس (اختياري)</label>
                    <select name="lesson_id" class="w-full rounded-xl border px-3 py-2.5 text-sm" x-effect="$el.value = editing?.lesson_id || ''">
                        <option value="">—</option>
                        <template x-for="lesson in lessons" :key="lesson.id">
                            <option :value="lesson.id" x-text="lesson.title"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs text-slate-500">موعد التسليم</label>
                    <input type="datetime-local" name="due_at" required class="w-full rounded-xl border px-3 py-2.5 text-sm" :value="editing?.due_at || ''">
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-xs text-slate-500">الحالة</label>
                    <select name="status" class="w-full rounded-xl border px-3 py-2.5 text-sm" x-effect="$el.value = editing?.status || 'draft'">
                        @foreach ($statusLabels as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2 flex gap-2 pt-1">
                    <button class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white" x-text="editing ? 'حفظ' : 'إضافة'"></button>
                    <button type="button" @click="close()" class="rounded-xl border px-4 py-2.5 text-sm">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>
