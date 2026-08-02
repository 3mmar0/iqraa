@extends('layouts.admin')

@section('title', $lesson->title)
@section('heading', $lesson->title)
@section('subheading', $lesson->course?->title)

@section('header-actions')
    <a href="{{ route('admin.lessons.edit', $lesson) }}" class="rounded-xl border bg-white px-4 py-2.5 text-sm">تعديل</a>
@endsection

@section('content')
    @php
        $sections = [
            ['label' => 'عام', 'href' => route('admin.lessons.show', [$lesson, 'section' => 'general']), 'active' => $section === 'general'],
            ['label' => 'الفيديو', 'href' => route('admin.lessons.show', [$lesson, 'section' => 'video']), 'active' => $section === 'video'],
            ['label' => 'الملفات', 'href' => route('admin.lessons.show', [$lesson, 'section' => 'files']), 'active' => $section === 'files'],
            ['label' => 'الموارد', 'href' => route('admin.lessons.show', [$lesson, 'section' => 'resources']), 'active' => $section === 'resources'],
            ['label' => 'الاختبار', 'href' => route('admin.lessons.show', [$lesson, 'section' => 'quiz']), 'active' => $section === 'quiz'],
            ['label' => 'الملاحظات', 'href' => route('admin.lessons.show', [$lesson, 'section' => 'notes']), 'active' => $section === 'notes'],
            ['label' => 'التعليقات', 'href' => route('admin.lessons.show', [$lesson, 'section' => 'comments']), 'active' => $section === 'comments'],
            ['label' => 'الإعدادات', 'href' => route('admin.lessons.show', [$lesson, 'section' => 'settings']), 'active' => $section === 'settings'],
        ];
        $videos = $lesson->mediaAssets->where('type', 'video');
        $files = $lesson->mediaAssets->where('type', '!=', 'video');
    @endphp

    <div class="mb-4 flex flex-wrap gap-2">
        @if (Route::has('admin.lessons.lock') && ! $lesson->is_locked)
            <form method="POST" action="{{ route('admin.lessons.lock', $lesson) }}">@csrf<button class="rounded-lg border px-3 py-1.5 text-xs">قفل</button></form>
        @endif
        @if (Route::has('admin.lessons.unlock') && $lesson->is_locked)
            <form method="POST" action="{{ route('admin.lessons.unlock', $lesson) }}">@csrf<button class="rounded-lg border px-3 py-1.5 text-xs">فتح</button></form>
        @endif
        @if (Route::has('admin.lessons.duplicate'))
            <form method="POST" action="{{ route('admin.lessons.duplicate', $lesson) }}">@csrf<button class="rounded-lg border px-3 py-1.5 text-xs">نسخ</button></form>
        @endif
    </div>

    <x-admin.tab-nav :tabs="$sections" class="mb-6" />

    <div class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
        @if ($section === 'general')
            <dl class="grid gap-3 sm:grid-cols-2 text-sm">
                <div><dt class="text-slate-500">المقرر</dt><dd class="font-medium">{{ $lesson->course?->title }}</dd></div>
                <div><dt class="text-slate-500">الترتيب</dt><dd class="font-medium">{{ $lesson->position }}</dd></div>
                <div><dt class="text-slate-500">الحالة</dt><dd class="font-medium">{{ $lesson->status }}</dd></div>
                <div><dt class="text-slate-500">تاريخ النشر</dt><dd class="font-medium">{{ $lesson->published_at?->format('Y-m-d H:i') ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">مقفل</dt><dd class="font-medium">{{ $lesson->is_locked ? 'نعم' : 'لا' }}</dd></div>
            </dl>
            <p class="mt-4 text-sm text-slate-600 whitespace-pre-line">{{ $lesson->description ?: 'لا يوجد وصف.' }}</p>
        @elseif ($section === 'video')
            <form method="POST" action="{{ route('admin.lessons.media.store', $lesson) }}" enctype="multipart/form-data" class="mb-6 rounded-xl border border-[var(--color-line)] bg-[var(--color-sand)] p-4">
                @csrf
                <p class="mb-3 text-sm font-medium text-[var(--color-ink)]">رفع فيديو</p>
                <input type="hidden" name="type" value="video">
                <input type="file" name="file" accept="video/*" required class="mb-3 block w-full text-sm">
                <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-4 py-2 text-sm font-medium text-white hover:bg-[var(--color-primary-hover)]">رفع</button>
            </form>
            <ul class="space-y-4 text-sm">
                @forelse ($videos as $asset)
                    <li class="rounded-xl border border-slate-200 p-3">
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <span class="font-medium">{{ $asset->original_name ?? basename($asset->path) }}</span>
                            <form method="POST" action="{{ route('admin.lessons.media.destroy', [$lesson, $asset]) }}" onsubmit="return confirm('حذف الملف؟');">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-lg border border-rose-200 px-2 py-1 text-xs text-rose-700">حذف</button>
                            </form>
                        </div>
                        <x-admin.media-preview :asset="$asset" />
                    </li>
                @empty
                    <li class="py-6 text-slate-500">لا فيديو مرفق.</li>
                @endforelse
            </ul>
        @elseif ($section === 'files')
            <form method="POST" action="{{ route('admin.lessons.media.store', $lesson) }}" enctype="multipart/form-data" class="mb-6 rounded-xl border border-[var(--color-line)] bg-[var(--color-sand)] p-4">
                @csrf
                <p class="mb-3 text-sm font-medium text-[var(--color-ink)]">رفع ملف / PDF / صورة</p>
                <div class="mb-3 grid gap-3 sm:grid-cols-2">
                    <select name="type" class="rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        <option value="pdf">PDF</option>
                        <option value="image">صورة</option>
                        <option value="attachment">مرفق</option>
                    </select>
                    <input type="file" name="file" accept=".pdf,image/*,.doc,.docx,.zip" required class="block w-full text-sm">
                </div>
                <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-4 py-2 text-sm font-medium text-white hover:bg-[var(--color-primary-hover)]">رفع</button>
            </form>
            <ul class="space-y-4 text-sm">
                @forelse ($files as $asset)
                    <li class="rounded-xl border border-slate-200 p-3">
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <span class="font-medium">{{ $asset->original_name ?? basename($asset->path) }} <span class="text-xs text-slate-500">({{ $asset->type }})</span></span>
                            <form method="POST" action="{{ route('admin.lessons.media.destroy', [$lesson, $asset]) }}" onsubmit="return confirm('حذف الملف؟');">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-lg border border-rose-200 px-2 py-1 text-xs text-rose-700">حذف</button>
                            </form>
                        </div>
                        <x-admin.media-preview :asset="$asset" compact />
                    </li>
                @empty
                    <li class="py-6 text-slate-500">لا ملفات.</li>
                @endforelse
            </ul>
        @elseif ($section === 'resources')
            <p class="text-sm text-slate-500">روابط وموارد إضافية للدرس.</p>
        @elseif ($section === 'quiz')
            @if ($lesson->quiz)
                <p class="font-medium">{{ $lesson->quiz->title }}</p>
                <p class="mt-1 text-sm text-slate-500">{{ $lesson->quiz->questions->count() }} سؤال · {{ $lesson->quiz->duration_minutes ?? '—' }} دقيقة</p>
            @else
                <p class="text-slate-500">لا اختبار مرتبط.</p>
                @if (Route::has('admin.lessons.attach-quiz'))
                    <form method="POST" action="{{ route('admin.lessons.attach-quiz', $lesson) }}" class="mt-4">
                        @csrf
                        <select name="quiz_id" class="mb-2 rounded-lg border px-3 py-2 text-sm">
                            @foreach (\App\Models\Quiz::orderBy('title')->get() as $quiz)
                                <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                            @endforeach
                        </select>
                        <button class="rounded-lg bg-[var(--color-primary)] px-3 py-1.5 text-xs text-white">ربط اختبار</button>
                    </form>
                @endif
            @endif
        @elseif ($section === 'notes')
            <p class="text-slate-500">ملاحظات الدرس للإدارة.</p>
        @elseif ($section === 'comments')
            <p class="text-slate-500">تعليقات الطلاب على الدرس.</p>
        @elseif ($section === 'settings')
            @if (Route::has('admin.lessons.schedule-publish'))
                <form method="POST" action="{{ route('admin.lessons.schedule-publish', $lesson) }}" class="mb-4 rounded-xl border p-4">
                    @csrf
                    <p class="mb-2 text-sm font-medium">جدولة النشر</p>
                    <input type="datetime-local" name="published_at" value="{{ $lesson->published_at?->format('Y-m-d\TH:i') }}" class="mb-2 rounded-lg border px-3 py-2 text-sm">
                    <button class="rounded-lg bg-[var(--color-primary)] px-3 py-1.5 text-xs text-white">حفظ</button>
                </form>
            @endif
            <form method="POST" action="{{ route('admin.lessons.destroy', $lesson) }}" onsubmit="return confirm('حذف الدرس؟');">
                @csrf
                @method('DELETE')
                <button class="rounded-xl bg-rose-700 px-4 py-2 text-sm text-white">حذف الدرس</button>
            </form>
        @endif
    </div>
@endsection
