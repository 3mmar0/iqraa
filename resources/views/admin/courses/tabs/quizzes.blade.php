<div class="mb-3 flex items-center justify-between">
    <h3 class="font-semibold">الاختبارات ({{ $course->quizzes->count() }})</h3>
    @if (Route::has('admin.quizzes.create'))
        <a href="{{ route('admin.quizzes.create') }}" class="text-sm text-teal-700 hover:underline">اختبار جديد</a>
    @endif
</div>
<ul class="divide-y divide-slate-100 text-sm">
    @forelse ($course->quizzes as $quiz)
        <li class="flex items-center justify-between py-2">
            <span>{{ $quiz->title }}</span>
            <div class="flex gap-2">
                @if (Route::has('admin.quizzes.show'))
                    <a href="{{ route('admin.quizzes.show', $quiz) }}" class="text-teal-700 hover:underline">عرض</a>
                @endif
                <span class="text-xs text-slate-500">{{ $quiz->status }}</span>
            </div>
        </li>
    @empty
        <li class="py-6 text-slate-500">لا اختبارات.</li>
    @endforelse
</ul>
