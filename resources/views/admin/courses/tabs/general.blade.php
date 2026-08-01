@php
    $statusLabels = ['draft' => 'مسودة', 'published' => 'منشور', 'archived' => 'مؤرشف', 'hidden' => 'مخفي'];
@endphp

<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2">
        @if ($course->image_path)
            <img src="{{ asset('storage/'.$course->image_path) }}" alt="" class="mb-4 h-40 w-full rounded-xl object-cover">
        @endif
        <p class="text-sm text-slate-600 whitespace-pre-line">{{ $course->description ?: 'لا يوجد وصف.' }}</p>
        <dl class="mt-4 grid gap-3 sm:grid-cols-2 text-sm">
            <div><dt class="text-slate-500">المحاضر</dt><dd class="font-medium">{{ $course->instructor?->name ?? '—' }}</dd></div>
            <div><dt class="text-slate-500">التصنيف</dt><dd class="font-medium">{{ $course->category?->name ?? '—' }}</dd></div>
            <div><dt class="text-slate-500">السنة الدراسية</dt><dd class="font-medium">{{ $course->academicYear?->name ?? '—' }}</dd></div>
            <div><dt class="text-slate-500">الفصل</dt><dd class="font-medium">{{ $course->semester?->name ?? $course->term_label ?? '—' }}</dd></div>
            <div><dt class="text-slate-500">السعر</dt><dd class="font-medium">{{ $course->price !== null ? number_format((float) $course->price, 2).' ر.س' : '—' }}</dd></div>
            <div><dt class="text-slate-500">الحالة</dt><dd class="font-medium">{{ $statusLabels[$course->status] ?? $course->status }}</dd></div>
            <div><dt class="text-slate-500">الساعات</dt><dd class="font-medium">{{ $course->hours ?? '—' }}</dd></div>
            <div><dt class="text-slate-500">الجدول</dt><dd class="font-medium">{{ $course->schedule_text ?? '—' }}</dd></div>
        </dl>
    </div>
    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-3">
            <x-admin.kpi-card label="الدروس" :value="$course->lessons_count" />
            <x-admin.kpi-card label="الطلاب" :value="$course->enrollments_count" />
        </div>
        @if (Route::has('admin.courses.assign-teacher'))
            <form method="POST" action="{{ route('admin.courses.assign-teacher', $course) }}" class="rounded-xl border p-4">
                @csrf
                <p class="mb-2 text-sm font-medium">تعيين محاضر</p>
                <select name="instructor_user_id" class="mb-2 w-full rounded-lg border px-3 py-2 text-sm">
                    @foreach (\App\Models\User::whereHas('roles', fn ($q) => $q->whereIn('slug', ['instructor', 'super_admin']))->orderBy('name')->get() as $instructor)
                        <option value="{{ $instructor->id }}" @selected($course->instructor_user_id === $instructor->id)>{{ $instructor->name }}</option>
                    @endforeach
                </select>
                <button class="rounded-lg bg-teal-700 px-3 py-1.5 text-xs text-white">حفظ</button>
            </form>
        @endif
    </div>
</div>
