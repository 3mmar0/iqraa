@php
    $videos = $course->lessons->flatMap(
        fn ($l) => $l->mediaAssets->where('type', 'video')->map(fn ($a) => ['asset' => $a, 'lesson' => $l])
    );
@endphp

<div class="space-y-6">
    <section class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
        <h3 class="mb-3 text-sm font-semibold text-slate-900">رفع فيديو</h3>
        @if ($course->lessons->isEmpty())
            <p class="text-sm text-amber-800">أضف درساً أولاً من تبويب الدروس ثم ارفع الفيديو عليه.</p>
        @else
            <form method="POST" enctype="multipart/form-data" class="grid gap-3 sm:grid-cols-2"
                  x-data="{ lessonId: @js((string) $course->lessons->first()->id) }"
                  :action="'{{ url('/admin/lessons') }}/' + lessonId + '/media'">
                @csrf
                <input type="hidden" name="type" value="video">
                <div>
                    <label class="mb-1 block text-xs text-slate-500">الدرس</label>
                    <select name="lesson_id" x-model="lessonId" required class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm">
                        @foreach ($course->lessons as $lesson)
                            <option value="{{ $lesson->id }}">{{ $lesson->position }}. {{ $lesson->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs text-slate-500">ملف الفيديو</label>
                    <input type="file" name="file" accept="video/*" required class="block w-full text-sm">
                </div>
                <div class="sm:col-span-2">
                    <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white">رفع الفيديو</button>
                </div>
            </form>
        @endif
    </section>

    <section>
        <h3 class="mb-3 font-semibold">الفيديوهات ({{ $videos->count() }})</h3>
        <ul class="divide-y divide-slate-100 text-sm">
            @forelse ($videos as $row)
                <li class="flex flex-wrap items-center justify-between gap-2 py-3">
                    <div>
                        <p class="font-medium">{{ $row['asset']->original_name ?? basename($row['asset']->path) }}</p>
                        <p class="text-xs text-slate-500">
                            {{ $row['lesson']->title }}
                            · {{ number_format(($row['asset']->size ?? 0) / 1048576, 1) }} MB
                        </p>
                    </div>
                    <form method="POST" action="{{ route('admin.lessons.media.destroy', [$row['lesson'], $row['asset']]) }}" onsubmit="return confirm('حذف الفيديو؟');">
                        @csrf
                        @method('DELETE')
                        <button class="rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-xs text-rose-800">حذف</button>
                    </form>
                </li>
            @empty
                <li class="py-8 text-center text-slate-500">لا فيديوهات بعد.</li>
            @endforelse
        </ul>
    </section>
</div>
