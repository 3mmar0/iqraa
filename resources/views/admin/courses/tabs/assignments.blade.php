<div class="mb-3 flex items-center justify-between">
    <h3 class="font-semibold">الواجبات ({{ $course->assignments->count() }})</h3>
    @if (Route::has('admin.assignments.create'))
        <a href="{{ route('admin.assignments.create') }}" class="text-sm text-[var(--color-primary)] hover:underline">واجب جديد</a>
    @endif
</div>
<ul class="divide-y divide-slate-100 text-sm">
    @forelse ($course->assignments as $assignment)
        <li class="flex items-center justify-between py-2">
            <span>{{ $assignment->title }}</span>
            <div class="flex gap-2">
                @if (Route::has('admin.assignments.show'))
                    <a href="{{ route('admin.assignments.show', $assignment) }}" class="text-[var(--color-primary)] hover:underline">عرض</a>
                @endif
                <span class="text-xs text-slate-500">{{ $assignment->due_at?->format('Y-m-d') }}</span>
            </div>
        </li>
    @empty
        <li class="py-6 text-slate-500">لا واجبات.</li>
    @endforelse
</ul>
