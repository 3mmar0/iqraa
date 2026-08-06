@php
    $statusLabels = ['draft' => 'مسودة', 'published' => 'منشور'];
    $typeLabels = ['single' => 'اختيار واحد', 'multiple' => 'اختيار متعدد', 'text' => 'نص'];
    $quizzesPayload = $course->quizzes->map(fn ($q) => [
        'id' => $q->id,
        'title' => $q->title,
        'duration_minutes' => $q->duration_minutes,
        'status' => $q->status,
        'show_correct_answers' => (bool) $q->show_correct_answers,
        'update_url' => route(($coursePanel ?? 'admin').'.quizzes.update', $q),
        'destroy_url' => route(($coursePanel ?? 'admin').'.quizzes.destroy', $q),
        'show_url' => route(($coursePanel ?? 'admin').'.quizzes.show', $q),
        'publish_url' => route(($coursePanel ?? 'admin').'.quizzes.publish', $q),
        'unpublish_url' => route(($coursePanel ?? 'admin').'.quizzes.unpublish', $q),
        'questions_store_url' => route(($coursePanel ?? 'admin').'.quizzes.questions.store', $q),
        'questions_reorder_url' => route(($coursePanel ?? 'admin').'.quizzes.questions.reorder', $q),
        'questions' => $q->questions->map(fn ($question) => [
            'id' => $question->id,
            'type' => $question->type === 'short_text' ? 'text' : $question->type,
            'body' => $question->body,
            'points' => $question->points,
            'position' => $question->position,
            'update_url' => route(($coursePanel ?? 'admin').'.quizzes.questions.update', [$q, $question]),
            'destroy_url' => route(($coursePanel ?? 'admin').'.quizzes.questions.destroy', [$q, $question]),
            'options' => $question->options->map(fn ($opt) => [
                'body' => $opt->body,
                'is_correct' => (bool) $opt->is_correct,
            ])->values(),
        ])->values(),
    ])->values();
@endphp

<div
    class="space-y-4"
    x-data="{
        open: false,
        editing: null,
        managing: null,
        questionOpen: false,
        editingQuestion: null,
        questionForm: { type: 'single', body: '', points: 1, options: [{ body: '', is_correct: true }, { body: '', is_correct: false }] },
        openCreate() { this.editing = null; this.open = true; },
        openEdit(item) { this.editing = item; this.open = true; },
        close() { this.open = false; this.editing = null; },
        openQuestions(item) { this.managing = item; this.closeQuestionForm(); },
        closeQuestions() { this.managing = null; this.closeQuestionForm(); },
        openCreateQuestion() {
            this.editingQuestion = null;
            this.questionForm = { type: 'single', body: '', points: 1, options: [{ body: '', is_correct: true }, { body: '', is_correct: false }] };
            this.questionOpen = true;
        },
        openEditQuestion(q) {
            this.editingQuestion = q;
            this.questionForm = {
                type: q.type || 'single',
                body: q.body || '',
                points: q.points || 1,
                options: (q.options && q.options.length)
                    ? q.options.map(o => ({ body: o.body, is_correct: !!o.is_correct }))
                    : [{ body: '', is_correct: true }, { body: '', is_correct: false }],
            };
            this.questionOpen = true;
        },
        closeQuestionForm() { this.questionOpen = false; this.editingQuestion = null; },
        addOption() { this.questionForm.options.push({ body: '', is_correct: false }); },
        removeOption(index) { if (this.questionForm.options.length > 2) this.questionForm.options.splice(index, 1); },
        moveQuestion(index, direction) {
            if (!this.managing) return;
            const list = [...this.managing.questions];
            const target = index + direction;
            if (target < 0 || target >= list.length) return;
            const tmp = list[index];
            list[index] = list[target];
            list[target] = tmp;
            this.managing.questions = list;
            this.$nextTick(() => this.$refs.reorderForm?.submit());
        },
        items: @js($quizzesPayload),
        statusLabels: @js($statusLabels),
        typeLabels: @js($typeLabels),
    }"
    @keydown.escape.window="if (questionOpen) { closeQuestionForm() } else if (managing) { closeQuestions() } else { close() }"
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
                            · <span x-text="(item.questions || []).length"></span> سؤال
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" @click="openEdit(item)" class="admin-btn admin-btn-ghost admin-btn-sm">تعديل</button>
                        <button type="button" @click="openQuestions(item)" class="admin-btn admin-btn-primary admin-btn-sm">الأسئلة</button>
                        <form method="POST" :action="item.status === 'published' ? item.unpublish_url : item.publish_url">
                            @csrf
                            <button class="admin-btn admin-btn-ghost admin-btn-sm" x-text="item.status === 'published' ? 'إلغاء النشر' : 'نشر'"></button>
                        </form>
                        <a :href="item.show_url" class="admin-btn admin-btn-ghost admin-btn-sm">تفاصيل إضافية</a>
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

        <form method="POST" :action="editing ? editing.update_url : '{{ route(($coursePanel ?? 'admin').'.quizzes.store') }}'" class="grid gap-4 sm:grid-cols-2" :key="editing ? ('q-'+editing.id) : 'q-new'">
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

    <x-admin.modal show="managing" maxWidth="max-w-3xl" close="closeQuestions()">
        <x-slot:header>
            <h3 class="text-base font-semibold text-slate-900">إدارة الأسئلة</h3>
            <p class="mt-0.5 text-xs text-slate-500" x-text="managing?.title || ''"></p>
        </x-slot:header>

        <div class="space-y-4" x-show="managing">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <p class="text-sm text-slate-600"><span x-text="(managing?.questions || []).length"></span> سؤال</p>
                <button type="button" @click="openCreateQuestion()" class="admin-btn admin-btn-primary admin-btn-sm">إضافة سؤال</button>
            </div>

            <form x-ref="reorderForm" method="POST" :action="managing?.questions_reorder_url">
                @csrf
                @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'quizzes'])
                <template x-for="q in (managing?.questions || [])" :key="'hid-'+q.id">
                    <input type="hidden" name="question_ids[]" :value="q.id">
                </template>
            </form>

            <div class="space-y-2">
                <template x-for="(q, index) in (managing?.questions || [])" :key="q.id">
                    <div class="rounded-xl border border-slate-200 bg-white p-3">
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-slate-900">
                                    <span x-text="index + 1"></span>. <span x-text="q.body"></span>
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    <span x-text="typeLabels[q.type] || q.type"></span>
                                    · <span x-text="q.points"></span> نقطة
                                    · <span x-text="(q.options || []).length"></span> خيارات
                                </p>
                            </div>
                            <div class="flex flex-wrap gap-1">
                                <button type="button" class="admin-btn admin-btn-ghost admin-btn-sm" @click="moveQuestion(index, -1)" :disabled="index === 0">أعلى</button>
                                <button type="button" class="admin-btn admin-btn-ghost admin-btn-sm" @click="moveQuestion(index, 1)" :disabled="index === (managing.questions.length - 1)">أسفل</button>
                                <button type="button" class="admin-btn admin-btn-ghost admin-btn-sm" @click="openEditQuestion(q)">تعديل</button>
                                <form method="POST" :action="q.destroy_url" onsubmit="return confirm('حذف السؤال؟');">
                                    @csrf
                                    @method('DELETE')
                                    @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'quizzes'])
                                    <button class="admin-btn admin-btn-danger admin-btn-sm">حذف</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </template>
                <p x-show="!(managing?.questions || []).length" class="rounded-xl border border-dashed border-slate-200 py-8 text-center text-sm text-slate-500">لا أسئلة بعد. أضف سؤالاً قبل نشر الاختبار.</p>
            </div>
        </div>
    </x-admin.modal>

    <x-admin.modal show="questionOpen" maxWidth="max-w-2xl" close="closeQuestionForm()">
        <x-slot:header>
            <h3 class="text-base font-semibold text-slate-900" x-text="editingQuestion ? 'تعديل السؤال' : 'إضافة سؤال'"></h3>
            <p class="mt-0.5 text-xs text-slate-500">النوع، النص، الدرجة، والخيارات</p>
        </x-slot:header>

        <form
            method="POST"
            :action="editingQuestion ? editingQuestion.update_url : (managing?.questions_store_url || '#')"
            class="grid gap-4"
            :key="editingQuestion ? ('qq-'+editingQuestion.id) : 'qq-new'"
        >
            @csrf
            <template x-if="editingQuestion"><input type="hidden" name="_method" value="PUT"></template>
            @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'quizzes'])

            <div>
                <label class="admin-label">النوع</label>
                <select name="type" class="admin-input" x-model="questionForm.type">
                    <option value="single">اختيار واحد</option>
                    <option value="multiple">اختيار متعدد</option>
                    <option value="text">نص</option>
                </select>
            </div>
            <div>
                <label class="admin-label">نص السؤال</label>
                <textarea name="body" rows="3" required class="admin-input" x-model="questionForm.body" placeholder="اكتب نص السؤال..."></textarea>
            </div>
            <div>
                <label class="admin-label">الدرجة</label>
                <input type="number" min="1" name="points" required class="admin-input" x-model="questionForm.points">
            </div>

            <div class="space-y-2" x-show="questionForm.type !== 'text'">
                <div class="flex items-center justify-between gap-2">
                    <label class="admin-label mb-0">الخيارات</label>
                    <button type="button" class="admin-btn admin-btn-ghost admin-btn-sm" @click="addOption()">إضافة خيار</button>
                </div>
                <template x-for="(opt, oi) in questionForm.options" :key="oi">
                    <div class="flex flex-wrap items-center gap-2 rounded-xl border border-slate-200 bg-slate-50/70 p-2.5">
                        <input type="text" class="admin-input flex-1" :name="'options['+oi+'][body]'" x-model="opt.body" placeholder="نص الخيار" :required="questionForm.type !== 'text'">
                        <label class="flex items-center gap-1.5 text-xs text-slate-700">
                            <input type="checkbox" value="1" :name="'options['+oi+'][is_correct]'" x-model="opt.is_correct" class="rounded border-slate-300 text-[var(--color-primary)]">
                            صحيح
                        </label>
                        <button type="button" class="admin-btn admin-btn-ghost admin-btn-sm" @click="removeOption(oi)">حذف</button>
                    </div>
                </template>
            </div>

            <div class="flex flex-wrap gap-2 border-t border-slate-100 pt-4">
                <button class="admin-btn admin-btn-primary" x-text="editingQuestion ? 'حفظ السؤال' : 'إضافة السؤال'"></button>
                <button type="button" @click="closeQuestionForm()" class="admin-btn admin-btn-ghost">إلغاء</button>
            </div>
        </form>
    </x-admin.modal>
</div>
