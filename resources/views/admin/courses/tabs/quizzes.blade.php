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
        <h3 class="font-semibold">الاختبارات (<span x-text="items.length">{{ $course->quizzes->count() }}</span>)</h3>
        <button type="button" @click="openCreate()" class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white">إضافة اختبار</button>
    </div>

    <div class="space-y-3">
        <template x-for="item in items" :key="item.id">
            <div class="rounded-xl border border-slate-200 p-4">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="font-medium" x-text="item.title"></p>
                        <p class="mt-1 text-xs text-slate-500">
                            <span x-text="statusLabels[item.status] || item.status"></span>
                            <span x-show="item.duration_minutes"> · <span x-text="item.duration_minutes"></span> دقيقة</span>
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" @click="openEdit(item)" class="rounded-lg border px-2.5 py-1.5 text-xs">تعديل</button>
                        <a :href="item.show_url" class="rounded-lg border px-2.5 py-1.5 text-xs">أسئلة</a>
                        <form method="POST" :action="item.status === 'published' ? item.unpublish_url : item.publish_url">
                            @csrf
                            <button class="rounded-lg border px-2.5 py-1.5 text-xs" x-text="item.status === 'published' ? 'إلغاء النشر' : 'نشر'"></button>
                        </form>
                        <form method="POST" :action="item.destroy_url" onsubmit="return confirm('حذف الاختبار؟');">
                            @csrf
                            @method('DELETE')
                            @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'quizzes'])
                            <button class="rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-xs text-rose-800">حذف</button>
                        </form>
                    </div>
                </div>
            </div>
        </template>
        <p x-show="items.length === 0" class="rounded-xl border border-dashed border-slate-200 py-10 text-center text-sm text-slate-500">لا اختبارات بعد.</p>
    </div>

    <div x-show="open" x-cloak class="fixed inset-0 z-[80] flex items-end justify-center bg-black/45 p-4 sm:items-center" @click.self="close()">
        <div class="w-full max-w-lg rounded-2xl bg-white p-5 shadow-xl" @click.stop>
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-semibold" x-text="editing ? 'تعديل الاختبار' : 'إضافة اختبار'"></h3>
                <button type="button" @click="close()" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" aria-label="إغلاق">✕</button>
            </div>
            <form method="POST" :action="editing ? editing.update_url : '{{ route('admin.quizzes.store') }}'" class="grid gap-3 sm:grid-cols-2" :key="editing ? ('q-'+editing.id) : 'q-new'">
                @csrf
                <template x-if="editing"><input type="hidden" name="_method" value="PUT"></template>
                @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'quizzes'])
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-xs text-slate-500">العنوان</label>
                    <input name="title" required class="w-full rounded-xl border px-3 py-2.5 text-sm" :value="editing?.title || ''">
                </div>
                <div>
                    <label class="mb-1 block text-xs text-slate-500">المدة (دقائق)</label>
                    <input type="number" min="1" name="duration_minutes" class="w-full rounded-xl border px-3 py-2.5 text-sm" :value="editing?.duration_minutes || ''">
                </div>
                <div>
                    <label class="mb-1 block text-xs text-slate-500">الحالة</label>
                    <select name="status" class="w-full rounded-xl border px-3 py-2.5 text-sm" x-effect="$el.value = editing?.status || 'draft'">
                        <option value="draft">مسودة</option>
                        <option value="published">منشور</option>
                    </select>
                </div>
                <div class="flex items-center gap-2 sm:col-span-2">
                    <input id="quiz_show_answers" type="checkbox" name="show_correct_answers" value="1" class="rounded border-slate-300" :checked="!!editing?.show_correct_answers">
                    <label for="quiz_show_answers" class="text-sm">إظهار الإجابات الصحيحة بعد التسليم</label>
                </div>
                <div class="sm:col-span-2 flex gap-2 pt-1">
                    <button class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white" x-text="editing ? 'حفظ' : 'إضافة'"></button>
                    <button type="button" @click="close()" class="rounded-xl border px-4 py-2.5 text-sm">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>
