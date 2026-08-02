@php
    $statusLabels = ['draft' => 'مسودة', 'published' => 'منشور', 'archived' => 'مؤرشف'];
@endphp

<div class="space-y-6">
    <section class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
        <h3 class="mb-3 text-sm font-semibold text-slate-900">إضافة واجب</h3>
        <form method="POST" action="{{ route('admin.assignments.store') }}" class="grid gap-3 sm:grid-cols-2">
            @csrf
            @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'assignments'])
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <div class="sm:col-span-2">
                <label class="mb-1 block text-xs text-slate-500">العنوان</label>
                <input name="title" required class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm" placeholder="عنوان الواجب">
            </div>
            <div class="sm:col-span-2">
                <label class="mb-1 block text-xs text-slate-500">الوصف</label>
                <textarea name="description" rows="2" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm"></textarea>
            </div>
            <div>
                <label class="mb-1 block text-xs text-slate-500">الدرس (اختياري)</label>
                <select name="lesson_id" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm">
                    <option value="">—</option>
                    @foreach ($course->lessons as $lesson)
                        <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs text-slate-500">موعد التسليم</label>
                <input type="datetime-local" name="due_at" required class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-xs text-slate-500">الحالة</label>
                <select name="status" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm">
                    <option value="draft">مسودة</option>
                    <option value="published">منشور</option>
                </select>
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white">إضافة الواجب</button>
            </div>
        </form>
    </section>

    <section>
        <h3 class="mb-3 font-semibold">الواجبات ({{ $course->assignments->count() }})</h3>
        <div class="space-y-3">
            @forelse ($course->assignments as $assignment)
                <div class="rounded-xl border border-slate-200 p-4" x-data="{ editing: false }">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <p class="font-medium">{{ $assignment->title }}</p>
                            <p class="mt-1 text-xs text-slate-500">
                                {{ $statusLabels[$assignment->status] ?? $assignment->status }}
                                · التسليم: {{ $assignment->due_at?->format('Y-m-d H:i') ?? '—' }}
                                @if ($assignment->lesson)
                                    · {{ $assignment->lesson->title }}
                                @endif
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            <button type="button" @click="editing = !editing" class="rounded-lg border px-2.5 py-1.5 text-xs">تعديل</button>
                            <a href="{{ route('admin.assignments.show', $assignment) }}" class="rounded-lg border px-2.5 py-1.5 text-xs">عرض</a>
                            <form method="POST" action="{{ route('admin.assignments.destroy', $assignment) }}" onsubmit="return confirm('حذف الواجب؟');">
                                @csrf
                                @method('DELETE')
                                @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'assignments'])
                                <button class="rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-xs text-rose-800">حذف</button>
                            </form>
                        </div>
                    </div>

                    <div x-show="editing" x-cloak class="mt-4 border-t border-slate-100 pt-4">
                        <form method="POST" action="{{ route('admin.assignments.update', $assignment) }}" class="grid gap-3 sm:grid-cols-2">
                            @csrf
                            @method('PUT')
                            @include('admin.courses._return_fields', ['course' => $course, 'tab' => 'assignments'])
                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                            <div class="sm:col-span-2">
                                <label class="mb-1 block text-xs text-slate-500">العنوان</label>
                                <input name="title" value="{{ $assignment->title }}" required class="w-full rounded-xl border px-3 py-2 text-sm">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="mb-1 block text-xs text-slate-500">الوصف</label>
                                <textarea name="description" rows="2" class="w-full rounded-xl border px-3 py-2 text-sm">{{ $assignment->description }}</textarea>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs text-slate-500">الدرس</label>
                                <select name="lesson_id" class="w-full rounded-xl border px-3 py-2 text-sm">
                                    <option value="">—</option>
                                    @foreach ($course->lessons as $lesson)
                                        <option value="{{ $lesson->id }}" @selected($assignment->lesson_id === $lesson->id)>{{ $lesson->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs text-slate-500">موعد التسليم</label>
                                <input type="datetime-local" name="due_at" value="{{ $assignment->due_at?->format('Y-m-d\TH:i') }}" required class="w-full rounded-xl border px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs text-slate-500">الحالة</label>
                                <select name="status" class="w-full rounded-xl border px-3 py-2 text-sm">
                                    @foreach ($statusLabels as $value => $label)
                                        <option value="{{ $value }}" @selected($assignment->status === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-2 flex gap-2">
                                <button class="rounded-xl bg-[var(--color-primary)] px-4 py-2 text-sm font-semibold text-white">حفظ</button>
                                <button type="button" @click="editing = false" class="rounded-xl border px-4 py-2 text-sm">إلغاء</button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <p class="rounded-xl border border-dashed border-slate-200 py-10 text-center text-sm text-slate-500">لا واجبات بعد.</p>
            @endforelse
        </div>
    </section>
</div>
