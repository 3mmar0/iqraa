@extends('layouts.app')

@section('title', $course->title)

@section('content')
    <h1 class="mb-2 text-2xl font-bold text-teal-900">{{ $course->title }}</h1>
    <p class="mb-6 text-sm text-slate-600">الحالة: {{ $course->status }}</p>

    <section class="mb-8">
        <h2 class="mb-3 text-lg font-semibold">الدروس</h2>
        @forelse ($course->lessons as $lesson)
            <div class="mb-3 rounded-xl border border-slate-200 bg-white p-4">
                <h3 class="font-medium">{{ $lesson->position }}. {{ $lesson->title }}</h3>
                <p class="text-sm text-slate-600">{{ $lesson->mediaAssets->count() }} ملفات</p>
                @if (\Illuminate\Support\Facades\Route::has('instructor.media.store'))
                    <form method="POST" action="{{ route('instructor.media.store', $lesson) }}" enctype="multipart/form-data" class="mt-3 flex flex-wrap items-end gap-2">
                        @csrf
                        <input type="file" name="file" required class="text-sm">
                        <button type="submit" class="rounded bg-slate-800 px-3 py-1.5 text-sm text-white">رفع ملف</button>
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
                <button type="submit" class="rounded bg-teal-700 px-3 py-1.5 text-sm text-white">إضافة</button>
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
                <button type="submit" class="rounded bg-teal-700 px-3 py-1.5 text-sm text-white">إنشاء</button>
            </form>
        @endif
    </section>
@endsection