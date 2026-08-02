@php
    $statusLabels = ['draft' => 'مسودة', 'published' => 'منشور'];
    $quizzesPayload = $course->quizzes->map(fn ($q) => [
        'id' => $q->id,
        'title' => $q->title,
        'duration_minutes' => $q->duration_minutes,
        'status' => $q->status,
        'show_correct_answers' => (bool) $q->show_correct_answers,
        'update_url' => route('admin.quizzes.update', $q),
        'destroy_url' => route('admin.quizzes.destroy', $q),
        'show_url' => route('admin.quizzes.show', $q),
        'publish_url' => route('admin.quizzes.publish', $q),
        'unpublish_url' => route('admin.quizzes.unpublish', $q),
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
        items: @js($quizzesPayload),
        statusLabels: @js($statusLabels),
    }"
    @keydown.escape.window="close()"
>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h3 class="font-semibold text-slate-900">الاختبارات (<span x-text="items.length">{{ $course->quizzes->count() }}</span>)</h3>
        <button type="button" @click="openCreate()" class="admin-btn admin-btn-primary">إضافة اختبار</button>
    </div>

    <div class="space-y-3">
        <template x-for="item in items" :key="item.id">
            <div class="rounded-2xl border border-slate-200 bg-gradient-to-l from-slate-50/80 to-white p-4 transition hover:border-[var(--color-primary)]/30 hover:shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold text-slate-900" x-text="item.title"></p>
                        <p class="mt-1 text-xs text-slate-500">
                            <span x-text="statusLabels[item.status] || item.status"></span>
                            <span x-show="item.duration_minutes"> · <span x-text="item.duration_minutes"></span> دقيقة</span>
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" @click="openEdit(item)" class="admin-btn admin-btn-ghost admin-btn-sm">تعديل</button>
                        <a :href="item.show_url" class="admin-btn admin-btn-ghost admin-btn-sm">أسئلة</a>
                        <form method="POST" :action="item.status === 'published' ? item.unpublish_url : item.publish_url">
                            @csrf
                            <button class="admin-btn admin-btn-ghost admin-btn-sm" x-text="item.status === 'published' ? 'إلغاء النشر' : 'نشر'"></button>
                        </form>
                        <form method="POST" :action="item.destroy_url" onsubmit="return confirm('حذف الاختبار؟');">
                            @csrf
                            @method('DELETE')
                            @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'quizzes'])
                            <button class="admin-btn admin-btn-danger admin-btn-sm">حذف</button>
                        </form>
                    </div>
                </div>
            </div>
        </template>
        <p x-show="items.length === 0" class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/50 py-12 text-center text-sm text-slate-500">لا اختبارات بعد.</p>
    </div>

    <x-admin.modal show="open">
        <x-slot:header>
            <h3 class="text-base font-semibold text-slate-900" x-text="editing ? 'تعديل الاختبار' : 'إضافة اختبار'"></h3>
            <p class="mt-0.5 text-xs text-slate-500">العنوان، المدة، وحالة النشر</p>
        </x-slot:header>

        <form method="POST" :action="editing ? editing.update_url : '{{ route('admin.quizzes.store') }}'" class="grid gap-4 sm:grid-cols-2" :key="editing ? ('q-'+editing.id) : 'q-new'">
            @csrf
            <template x-if="editing"><input type="hidden" name="_method" value="PUT"></template>
            @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'quizzes'])
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <div class="sm:col-span-2">
                <label class="admin-label">العنوان</label>
                <input name="title" required class="admin-input" placeholder="عنوان الاختبار" :value="editing?.title || ''">
            </div>
            <div>
                <label class="admin-label">المدة (دقائق)</label>
                <input type="number" min="1" name="duration_minutes" class="admin-input" :value="editing?.duration_minutes || ''">
            </div>
            <div>
                <label class="admin-label">الحالة</label>
                <select name="status" class="admin-input" x-effect="$el.value = editing?.status || 'draft'">
                    <option value="draft">مسودة</option>
                    <option value="published">منشور</option>
                </select>
            </div>
            <label class="flex items-center gap-2.5 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-sm text-slate-700 sm:col-span-2">
                <input id="quiz_show_answers" type="checkbox" name="show_correct_answers" value="1" class="rounded border-slate-300 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" :checked="!!editing?.show_correct_answers">
                إظهار الإجابات الصحيحة بعد التسليم
            </label>
            <div class="sm:col-span-2 flex flex-wrap gap-2 border-t border-slate-100 pt-4">
                <button class="admin-btn admin-btn-primary" x-text="editing ? 'حفظ' : 'إضافة'"></button>
                <button type="button" @click="close()" class="admin-btn admin-btn-ghost">إلغاء</button>
            </div>
        </form>
    </x-admin.modal>
</div>
