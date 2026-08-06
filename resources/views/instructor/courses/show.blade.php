@extends('layouts.instructor')

@section('title', $course->title)
@section('heading', $course->title)
@section('subheading', 'مساحة المقرر — دروس، اختبارات، وملفات')

@section('header-actions')
    <a href="{{ route('instructor.courses.index') }}" class="rounded-2xl border border-[var(--color-line)] bg-white px-3.5 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">كل المقررات</a>
    <x-admin.status-badge :status="$course->status" />
@endsection

@section('content')
    <div class="mx-auto max-w-6xl space-y-8">
        <section class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
                <p class="text-xs text-slate-500">الدروس</p>
                <p class="mt-1 text-2xl font-bold text-[var(--color-ink)]">{{ $course->lessons->count() }}</p>
            </div>
            <div class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
                <p class="text-xs text-slate-500">الاختبارات</p>
                <p class="mt-1 text-2xl font-bold text-[var(--color-ink)]">{{ $course->quizzes->count() }}</p>
            </div>
            <div class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
                <p class="text-xs text-slate-500">الطلاب</p>
                <p class="mt-1 text-2xl font-bold text-[var(--color-ink)]">{{ $course->enrollments->count() }}</p>
            </div>
        </section>

        @if ($course->description)
            <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
                <h2 class="font-bold text-[var(--color-ink)]">عن المقرر</h2>
                <p class="mt-2 text-sm leading-relaxed text-[var(--color-text-secondary)] whitespace-pre-wrap">{{ $course->description }}</p>
            </section>
        @endif

        <div class="grid gap-8 lg:grid-cols-[minmax(0,1.35fr)_minmax(0,1fr)]">
            <section class="space-y-4">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold text-[var(--color-ink)]">الدروس</h2>
                        <p class="mt-1 text-sm text-slate-500">رتّب المحتوى وارفع الملفات لكل درس.</p>
                    </div>
                </div>

                @forelse ($course->lessons as $lesson)
                    <article class="rounded-2xl border border-[var(--color-line)] bg-white p-5 shadow-[0_10px_28px_-22px_rgba(47,58,69,0.35)]">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-medium text-slate-500">درس {{ $lesson->position }}</p>
                                <h3 class="mt-0.5 text-lg font-bold text-[var(--color-ink)]">{{ $lesson->title }}</h3>
                                @if ($lesson->description)
                                    <p class="mt-1 text-sm text-slate-500">{{ \Illuminate\Support\Str::limit($lesson->description, 120) }}</p>
                                @endif
                                <p class="mt-2 text-xs text-slate-500">{{ $lesson->mediaAssets->count() }} ملف مرفق</p>
                            </div>
                        </div>

                        @if (\Illuminate\Support\Facades\Route::has('instructor.media.store'))
                            <form method="POST" action="{{ route('instructor.media.store', $lesson) }}" enctype="multipart/form-data" class="admin-uploader mt-4 space-y-3" x-data="{ fileName: '', dragging: false }">
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
                                        <p class="admin-dropzone-title">اسحب ملفاً أو انقر للاختيار</p>
                                    </div>
                                    <div class="admin-dropzone-file" x-show="fileName" x-cloak @click.stop>
                                        <p class="truncate text-sm font-semibold text-slate-900" x-text="fileName"></p>
                                    </div>
                                </div>
                                <button type="submit" class="admin-btn admin-btn-primary admin-btn-sm">رفع الملف</button>
                            </form>
                        @endif
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-[var(--color-line)] bg-white px-5 py-10 text-center text-sm text-slate-500">لا دروس بعد — أضف أول درس من النموذج الجانبي.</div>
                @endforelse
            </section>

            <div class="space-y-6">
                @if (\Illuminate\Support\Facades\Route::has('instructor.lessons.store'))
                    <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
                        <h2 class="font-bold text-[var(--color-ink)]">إضافة درس</h2>
                        <form method="POST" action="{{ route('instructor.lessons.store', $course) }}" class="mt-4 space-y-3">
                            @csrf
                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-500" for="lesson-title">العنوان</label>
                                <input id="lesson-title" type="text" name="title" required placeholder="عنوان الدرس" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-500" for="lesson-desc">الوصف</label>
                                <textarea id="lesson-desc" name="description" rows="3" placeholder="وصف مختصر" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20"></textarea>
                            </div>
                            <button type="submit" class="w-full rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">إضافة الدرس</button>
                        </form>
                    </section>
                @endif

                <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
                    <h2 class="font-bold text-[var(--color-ink)]">الاختبارات</h2>
                    <div class="mt-3 space-y-2">
                        @forelse ($course->quizzes as $quiz)
                            <div class="rounded-xl border border-[var(--color-line)] bg-[var(--color-sand)]/70 px-3 py-3">
                                <p class="text-sm font-semibold text-[var(--color-ink)]">{{ $quiz->title }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">{{ $quiz->questions->count() }} أسئلة</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">لا اختبارات بعد.</p>
                        @endforelse
                    </div>

                    @if (\Illuminate\Support\Facades\Route::has('instructor.quizzes.store'))
                        <form method="POST" action="{{ route('instructor.quizzes.store', $course) }}" class="mt-4 space-y-3 border-t border-slate-100 pt-4">
                            @csrf
                            <p class="text-sm font-semibold text-[var(--color-ink)]">إنشاء اختبار</p>
                            <input type="text" name="title" required placeholder="عنوان الاختبار" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                            <input type="number" name="duration_minutes" placeholder="المدة بالدقائق" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                            <input type="text" name="question_body" placeholder="نص السؤال الأول (اختياري)" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                            <button type="submit" class="w-full rounded-xl border border-[var(--color-primary)]/30 bg-[var(--color-primary-light)] px-4 py-2.5 text-sm font-semibold text-[var(--color-primary-hover)]">إنشاء</button>
                        </form>
                    @endif
                </section>
            </div>
        </div>
    </div>
@endsection
