@php
    $statusLabels = ['draft' => 'مسودة', 'published' => 'منشور'];
@endphp

<div class="space-y-6">
    <section class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
        <h3 class="mb-3 text-sm font-semibold text-slate-900">إضافة اختبار</h3>
        <form method="POST" action="{{ route('admin.quizzes.store') }}" class="grid gap-3 sm:grid-cols-2">
            @csrf
            @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'quizzes'])
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <div class="sm:col-span-2">
                <label class="mb-1 block text-xs text-slate-500">العنوان</label>
                <input name="title" required class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm" placeholder="عنوان الاختبار">
            </div>
            <div>
                <label class="mb-1 block text-xs text-slate-500">المدة (دقائق)</label>
                <input type="number" min="1" name="duration_minutes" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm" placeholder="اختياري">
            </div>
            <div>
                <label class="mb-1 block text-xs text-slate-500">الحالة</label>
                <select name="status" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm">
                    <option value="draft">مسودة</option>
                    <option value="published">منشور</option>
                </select>
            </div>
            <div class="flex items-center gap-2 sm:col-span-2">
                <input id="show_answers_new" type="checkbox" name="show_correct_answers" value="1" class="rounded border-slate-300">
                <label for="show_answers_new" class="text-sm">إظهار الإجابات الصحيحة بعد التسليم</label>
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white">إضافة الاختبار</button>
            </div>
        </form>
    </section>

    <section>
        <h3 class="mb-3 font-semibold">الاختبارات ({{ $course->quizzes->count() }})</h3>
        <div class="space-y-3">
            @forelse ($course->quizzes as $quiz)
                <div class="rounded-xl border border-slate-200 p-4" x-data="{ editing: false }">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <p class="font-medium">{{ $quiz->title }}</p>
                            <p class="mt-1 text-xs text-slate-500">
                                {{ $statusLabels[$quiz->status] ?? $quiz->status }}
                                @if ($quiz->duration_minutes) · {{ $quiz->duration_minutes }} دقيقة @endif
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            <button type="button" @click="editing = !editing" class="rounded-lg border px-2.5 py-1.5 text-xs">تعديل</button>
                            <a href="{{ route('admin.quizzes.show', $quiz) }}" class="rounded-lg border px-2.5 py-1.5 text-xs">أسئلة</a>
                            @if ($quiz->status === 'published')
                                <form method="POST" action="{{ route('admin.quizzes.unpublish', $quiz) }}">@csrf<button class="rounded-lg border px-2.5 py-1.5 text-xs">إلغاء النشر</button></form>
                            @else
                                <form method="POST" action="{{ route('admin.quizzes.publish', $quiz) }}">@csrf<button class="rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1.5 text-xs text-emerald-800">نشر</button></form>
                            @endif
                            <form method="POST" action="{{ route('admin.quizzes.destroy', $quiz) }}" onsubmit="return confirm('حذف الاختبار؟');">
                                @csrf
                                @method('DELETE')
                                @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'quizzes'])
                                <button class="rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-xs text-rose-800">حذف</button>
                            </form>
                        </div>
                    </div>

                    <div x-show="editing" x-cloak class="mt-4 border-t border-slate-100 pt-4">
                        <form method="POST" action="{{ route('admin.quizzes.update', $quiz) }}" class="grid gap-3 sm:grid-cols-2">
                            @csrf
                            @method('PUT')
                            @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'quizzes'])
                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                            <div class="sm:col-span-2">
                                <label class="mb-1 block text-xs text-slate-500">العنوان</label>
                                <input name="title" value="{{ $quiz->title }}" required class="w-full rounded-xl border px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs text-slate-500">المدة</label>
                                <input type="number" min="1" name="duration_minutes" value="{{ $quiz->duration_minutes }}" class="w-full rounded-xl border px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs text-slate-500">الحالة</label>
                                <select name="status" class="w-full rounded-xl border px-3 py-2 text-sm">
                                    <option value="draft" @selected($quiz->status === 'draft')>مسودة</option>
                                    <option value="published" @selected($quiz->status === 'published')>منشور</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-2 sm:col-span-2">
                                <input id="show_answers_{{ $quiz->id }}" type="checkbox" name="show_correct_answers" value="1" @checked($quiz->show_correct_answers) class="rounded border-slate-300">
                                <label for="show_answers_{{ $quiz->id }}" class="text-sm">إظهار الإجابات الصحيحة</label>
                            </div>
                            <div class="sm:col-span-2 flex gap-2">
                                <button class="rounded-xl bg-[var(--color-primary)] px-4 py-2 text-sm font-semibold text-white">حفظ</button>
                                <button type="button" @click="editing = false" class="rounded-xl border px-4 py-2 text-sm">إلغاء</button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <p class="rounded-xl border border-dashed border-slate-200 py-10 text-center text-sm text-slate-500">لا اختبارات بعد.</p>
            @endforelse
        </div>
    </section>
</div>
