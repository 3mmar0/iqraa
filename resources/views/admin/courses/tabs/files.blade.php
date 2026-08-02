@php
    $files = $course->lessons->flatMap(
        fn ($l) => $l->mediaAssets->where('type', '!=', 'video')->map(fn ($a) => ['asset' => $a, 'lesson' => $l])
    );
@endphp

<div class="space-y-6">
    <section>
        <h3 class="mb-3 text-sm font-semibold text-slate-900">رفع ملف لمقرر</h3>
        @if ($course->lessons->isEmpty())
            <p class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">أضف درساً أولاً من تبويب الدروس ثم ارفع الملفات عليه.</p>
        @else
            <x-admin.media-uploader
                :lesson-base-url="url('/admin/lessons')"
                :lesson-id="$course->lessons->first()->id"
                :show-lesson-select="true"
                :lessons="$course->lessons"
                accept=".pdf,image/*,.doc,.docx,.zip,application/pdf"
                button-label="رفع الملف"
            />
        @endif
    </section>

    <section>
        <h3 class="mb-3 font-semibold">الملفات ({{ $files->count() }})</h3>
        <div class="space-y-4">
            @forelse ($files as $row)
                <article class="rounded-xl border border-slate-200 p-4">
                    <div class="mb-3 flex flex-wrap items-start justify-between gap-2">
                        <div>
                            <p class="font-medium">{{ $row['asset']->original_name ?? basename($row['asset']->path) }}</p>
                            <p class="text-xs text-slate-500">{{ $row['lesson']->title }} · {{ $row['asset']->type }}</p>
                        </div>
                        <form method="POST" action="{{ route('admin.lessons.media.destroy', [$row['lesson'], $row['asset']]) }}" onsubmit="return confirm('حذف الملف؟');">
                            @csrf
                            @method('DELETE')
                            <button class="rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-xs text-rose-800">حذف</button>
                        </form>
                    </div>
                    <x-admin.media-preview :asset="$row['asset']" compact />
                </article>
            @empty
                <p class="py-8 text-center text-sm text-slate-500">لا ملفات مرفقة.</p>
            @endforelse
        </div>
    </section>
</div>
