@extends('layouts.instructor')

@section('title', $course->title)

@section('content')
    <h1 class="mb-2 text-2xl font-bold text-[var(--color-ink)]">{{ $course->title }}</h1>
    <p class="mb-6 text-sm text-slate-600">الحالة: {{ $course->status }}</p>

    <section class="mb-8">
        <h2 class="mb-3 text-lg font-semibold">الدروس</h2>
        @forelse ($course->lessons as $lesson)
            <div class="mb-3 rounded-xl border border-slate-200 bg-white p-4">
                <h3 class="font-medium">{{ $lesson->position }}. {{ $lesson->title }}</h3>
                <p class="text-sm text-slate-600">{{ $lesson->mediaAssets->count() }} ملفات</p>
                @if (\Illuminate\Support\Facades\Route::has('instructor.media.store'))
                    <form method="POST" action="{{ route('instructor.media.store', $lesson) }}" enctype="multipart/form-data" class="admin-uploader mt-3 space-y-3" x-data="{ fileName: '', dragging: false }">
                        @csrf
                        <div
                            class="admin-dropzone"
                            :class="{ 'is-dragging': dragging, 'is-filled': !! fileName }"
                            @dragenter.prevent="dragging = true"
                            @dragover.prevent="dragging = true"
                            @dragleave.prevent="dragging = false"
                            @drop.prevent="dragging = false; $refs.file.files = $event.dataTransfer.files; fileName = $event.dataTransfer.files[0]?.name || ''"
                            @click="$refs.file.click()"
                        >
                            <input type="file" name="file" required class="sr-only" x-ref="file" @change="fileName = $event.target.files[0]?.name || ''" @click.stop>
                            <div class="admin-dropzone-empty" x-show="! fileName">
                                <span class="admin-dropzone-icon" aria-hidden="true">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                                    </svg>
                                </span>
                                <p class="admin-dropzone-title">اسحب الملف هنا أو انقر للاختيار</p>
                            </div>
                            <div class="admin-dropzone-file" x-show="fileName" x-cloak @click.stop>
                                <p class="truncate text-sm font-semibold text-slate-900" x-text="fileName"></p>
                            </div>
                        </div>
                        <button type="submit" class="admin-btn admin-btn-primary admin-btn-sm">رفع ملف</button>
                    </form>
                @endif
            </div>
        @empty
            <x-empty-state message="لا دروس بعد." />
        @endforelse

        @if (\Illuminate\Support\Facades\Route::has('instructor.lessons.store'))
            <form method="POST" action="{{ route('instructor.lessons.store', $course) }}" class="mt-4 max-w-lg space-y-3 rounded-xl border border-dashed border-slate-300 bg-white p-4">
                @csrf
                <h3 class="font-medium">إضافة درس</h3>
                <input type="text" name="title" required placeholder="عنوان الدرس" class="w-full rounded border border-slate-300 px-3 py-2">
                <textarea name="description" rows="2" placeholder="وصف" class="w-full rounded border border-slate-300 px-3 py-2"></textarea>
                <button type="submit" class="rounded bg-[var(--color-primary)] px-3 py-1.5 text-sm text-white">إضافة</button>
            </form>
        @endif
    </section>

    <section>
        <h2 class="mb-3 text-lg font-semibold">الاختبارات</h2>
        @forelse ($course->quizzes as $quiz)
            <div class="mb-2 rounded-xl border border-slate-200 bg-white p-4">
                <p class="font-medium">{{ $quiz->title }}</p>
                <p class="text-sm text-slate-600">{{ $quiz->questions->count() }} أسئلة</p>
            </div>
        @empty
            <x-empty-state message="لا اختبارات بعد." />
        @endforelse

        @if (\Illuminate\Support\Facades\Route::has('instructor.quizzes.store'))
            <form method="POST" action="{{ route('instructor.quizzes.store', $course) }}" class="mt-4 max-w-lg space-y-3 rounded-xl border border-dashed border-slate-300 bg-white p-4">
                @csrf
                <h3 class="font-medium">إنشاء اختبار</h3>
                <input type="text" name="title" required placeholder="عنوان الاختبار" class="w-full rounded border border-slate-300 px-3 py-2">
                <input type="number" name="duration_minutes" placeholder="المدة بالدقائق" class="w-full rounded border border-slate-300 px-3 py-2">
                <input type="text" name="question_body" placeholder="نص السؤال الأول (اختياري)" class="w-full rounded border border-slate-300 px-3 py-2">
                <button type="submit" class="rounded bg-[var(--color-primary)] px-3 py-1.5 text-sm text-white">إنشاء</button>
            </form>
        @endif
    </section>
@endsection