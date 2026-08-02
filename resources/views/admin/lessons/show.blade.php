@extends('layouts.admin')

@section('title', $lesson->title)
@section('heading', $lesson->title)
@section('subheading', $lesson->course?->title)

@section('header-actions')
    <a href="{{ route('admin.lessons.index', ['course_id' => $lesson->course_id]) }}" class="admin-btn admin-btn-ghost">رجوع</a>
    <a href="{{ route('admin.lessons.edit', $lesson) }}" class="admin-btn admin-btn-primary">تعديل</a>
@endsection

@section('content')
    @php
        $statusLabels = ['draft' => 'مسودة', 'published' => 'منشور', 'scheduled' => 'مجدول', 'archived' => 'مؤرشف'];
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

    <div class="admin-content-enter space-y-5">
        <x-admin.action-toolbar>
            <x-admin.status-badge :status="$lesson->status" :label="$statusLabels[$lesson->status] ?? $lesson->status" />
            @if ($lesson->is_locked)
                <span class="admin-chip admin-chip-warning">مقفل</span>
            @endif
            @if (Route::has('admin.lessons.lock') && ! $lesson->is_locked)
                <form method="POST" action="{{ route('admin.lessons.lock', $lesson) }}">@csrf<button class="admin-btn admin-btn-ghost admin-btn-sm">قفل</button></form>
            @endif
            @if (Route::has('admin.lessons.unlock') && $lesson->is_locked)
                <form method="POST" action="{{ route('admin.lessons.unlock', $lesson) }}">@csrf<button class="admin-btn admin-btn-ghost admin-btn-sm">فتح</button></form>
            @endif
            @if (Route::has('admin.lessons.duplicate'))
                <form method="POST" action="{{ route('admin.lessons.duplicate', $lesson) }}">@csrf<button class="admin-btn admin-btn-ghost admin-btn-sm">نسخ</button></form>
            @endif
        </x-admin.action-toolbar>

        <x-admin.tab-nav :tabs="$sections" />

        <div class="admin-panel p-5 sm:p-6">
            @if ($section === 'general')
                <dl class="grid gap-3 sm:grid-cols-2">
                    @foreach ([
                        ['المقرر', $lesson->course?->title],
                        ['الترتيب', $lesson->position],
                        ['الحالة', $statusLabels[$lesson->status] ?? $lesson->status],
                        ['تاريخ النشر', $lesson->published_at?->format('Y-m-d H:i') ?? '—'],
                        ['مقفل', $lesson->is_locked ? 'نعم' : 'لا'],
                    ] as [$label, $value])
                        <div class="rounded-xl border border-slate-100 bg-slate-50/70 px-4 py-3">
                            <dt class="text-xs font-medium text-slate-500">{{ $label }}</dt>
                            <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
                <div class="mt-5 rounded-xl border border-slate-100 bg-white px-4 py-4">
                    <p class="mb-2 text-xs font-semibold text-slate-500">الوصف</p>
                    <p class="text-sm leading-7 text-slate-600 whitespace-pre-line">{{ $lesson->description ?: 'لا يوجد وصف.' }}</p>
                </div>
            @elseif ($section === 'video')
                <x-admin.media-uploader
                    class="mb-6"
                    :upload-url="route('admin.lessons.media.store', $lesson)"
                    default-type="video"
                    :show-type-select="false"
                    accept="video/*,.mp4,.webm,.mov,.mkv,.m4v"
                    button-label="رفع الفيديو"
                />
                <ul class="space-y-4 text-sm">
                    @forelse ($videos as $asset)
                        <li class="rounded-2xl border border-slate-200 p-4">
                            <div class="mb-3 flex items-center justify-between gap-3">
                                <span class="font-semibold text-slate-900">{{ $asset->original_name ?? basename($asset->path) }}</span>
                                <form method="POST" action="{{ route('admin.lessons.media.destroy', [$lesson, $asset]) }}" onsubmit="return confirm('حذف الملف؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="admin-btn admin-btn-danger admin-btn-sm">حذف</button>
                                </form>
                            </div>
                            <x-admin.media-preview :asset="$asset" />
                        </li>
                    @empty
                        <li>
                            <x-admin.empty-state title="لا فيديو مرفق" description="ارفع فيديو الدرس من الأعلى." />
                        </li>
                    @endforelse
                </ul>
            @elseif ($section === 'files')
                <x-admin.media-uploader
                    class="mb-6"
                    :upload-url="route('admin.lessons.media.store', $lesson)"
                    accept=".pdf,image/*,.doc,.docx,.zip,application/pdf"
                    button-label="رفع الملف"
                />
                <ul class="space-y-4 text-sm">
                    @forelse ($files as $asset)
                        <li class="rounded-2xl border border-slate-200 p-4">
                            <div class="mb-3 flex items-center justify-between gap-3">
                                <span class="font-semibold text-slate-900">{{ $asset->original_name ?? basename($asset->path) }} <span class="text-xs font-normal text-slate-500">({{ $asset->type }})</span></span>
                                <form method="POST" action="{{ route('admin.lessons.media.destroy', [$lesson, $asset]) }}" onsubmit="return confirm('حذف الملف؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="admin-btn admin-btn-danger admin-btn-sm">حذف</button>
                                </form>
                            </div>
                            <x-admin.media-preview :asset="$asset" compact />
                        </li>
                    @empty
                        <li>
                            <x-admin.empty-state title="لا ملفات" description="ارفع ملفات الدرس من الأعلى." />
                        </li>
                    @endforelse
                </ul>
            @elseif ($section === 'resources')
                <x-admin.empty-state title="الموارد" description="روابط وموارد إضافية للدرس ستظهر هنا." />
            @elseif ($section === 'quiz')
                @if ($lesson->quiz)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-5">
                        <p class="text-base font-semibold text-slate-900">{{ $lesson->quiz->title }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ $lesson->quiz->questions->count() }} سؤال · {{ $lesson->quiz->duration_minutes ?? '—' }} دقيقة</p>
                        <a href="{{ route('admin.quizzes.show', $lesson->quiz) }}" class="admin-btn admin-btn-ghost admin-btn-sm mt-4">عرض الاختبار</a>
                    </div>
                @else
                    <x-admin.empty-state title="لا اختبار مرتبط" description="اربط اختباراً بهذا الدرس.">
                        @if (Route::has('admin.lessons.attach-quiz'))
                            <x-slot:actions>
                                <form method="POST" action="{{ route('admin.lessons.attach-quiz', $lesson) }}" class="flex flex-wrap items-end gap-2">
                                    @csrf
                                    <select name="quiz_id" class="admin-input min-w-[14rem]">
                                        @foreach (\App\Models\Quiz::orderBy('title')->get() as $quiz)
                                            <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                                        @endforeach
                                    </select>
                                    <button class="admin-btn admin-btn-primary">ربط اختبار</button>
                                </form>
                            </x-slot:actions>
                        @endif
                    </x-admin.empty-state>
                @endif
            @elseif ($section === 'notes')
                <x-admin.empty-state title="الملاحظات" description="ملاحظات الدرس للإدارة ستظهر هنا." />
            @elseif ($section === 'comments')
                <x-admin.empty-state title="التعليقات" description="تعليقات الطلاب على الدرس ستظهر هنا." />
            @elseif ($section === 'settings')
                @if (Route::has('admin.lessons.schedule-publish'))
                    <form method="POST" action="{{ route('admin.lessons.schedule-publish', $lesson) }}" class="mb-5 rounded-2xl border border-slate-200 p-5">
                        @csrf
                        <p class="mb-3 text-sm font-semibold text-slate-900">جدولة النشر</p>
                        <input type="datetime-local" name="published_at" value="{{ $lesson->published_at?->format('Y-m-d\TH:i') }}" class="admin-input mb-3 max-w-xs">
                        <button class="admin-btn admin-btn-primary">حفظ</button>
                    </form>
                @endif
                <form method="POST" action="{{ route('admin.lessons.destroy', $lesson) }}" onsubmit="return confirm('حذف الدرس؟');">
                    @csrf
                    @method('DELETE')
                    <button class="admin-btn admin-btn-danger">حذف الدرس</button>
                </form>
            @endif
        </div>
    </div>
@endsection
