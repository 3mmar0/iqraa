<div class="mb-3 flex items-center justify-between">
    <h3 class="font-semibold">الدروس ({{ $course->lessons->count() }})</h3>
    <a href="{{ route('admin.lessons.index', ['course_id' => $course->id]) }}" class="text-sm text-[var(--color-primary)] hover:underline">إدارة الدروس</a>
</div>
<ul class="divide-y divide-slate-100">
    @forelse ($course->lessons as $lesson)
        <li class="flex items-center justify-between py-3 text-sm">
            <span>{{ $lesson->position }}. {{ $lesson->title }}
                @if ($lesson->is_locked)<span class="mr-2 rounded bg-amber-100 px-2 py-0.5 text-xs text-amber-800">مقفل</span>@endif
            </span>
            <div class="flex gap-2">
                @if (Route::has('admin.lessons.show'))
                    <a href="{{ route('admin.lessons.show', $lesson) }}" class="text-[var(--color-primary)] hover:underline">عرض</a>
                @endif
                <a href="{{ route('admin.lessons.edit', $lesson) }}" class="text-[var(--color-primary)] hover:underline">تعديل</a>
            </div>
        </li>
    @empty
        <li class="py-6 text-slate-500">لا دروس بعد.</li>
    @endforelse
</ul>
