@extends('layouts.admin')

@section('title', $teacher->name)
@section('heading', $teacher->name)
@section('subheading', 'ملف المعلم · '.$teacher->email)

@php
    $sourceLabel = [
        'self_registered' => 'تسجيل ذاتي',
        'admin_created' => 'أنشأه المشرف',
    ][$teacher->creation_source] ?? ($teacher->creation_source ?: '—');
@endphp

@section('header-actions')
    <a href="{{ route('admin.teachers.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">رجوع للقائمة</a>
    <a href="{{ route('admin.teachers.edit', $teacher) }}" class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">تعديل البيانات</a>
@endsection

@section('content')
    @include('components.alert')

    {{-- Profile header --}}
    <div class="mb-6 overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_10px_28px_-22px_rgba(47,58,69,0.4)]">
        <div class="h-2 bg-gradient-to-l from-[var(--color-primary)] via-[var(--color-secondary)] to-[var(--color-accent)]"></div>
        <div class="p-5 sm:p-6">
            <div class="flex flex-wrap items-start gap-4">
                <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-[var(--color-secondary-light)] text-2xl font-bold text-[var(--color-secondary-hover)]">
                    {{ mb_substr($teacher->name, 0, 1) }}
                </span>
                <div class="min-w-0 flex-1">
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        <x-admin.status-badge :status="$teacher->status" />
                        <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs text-slate-600">محاضر</span>
                        <span class="text-xs text-slate-500">#{{ $teacher->id }}</span>
                        @if ($teacher->email_verified_at)
                            <span class="rounded-full bg-[var(--color-primary-light)] px-2.5 py-0.5 text-xs text-[var(--color-primary-hover)]">البريد مُتحقق</span>
                        @endif
                    </div>
                    <dl class="grid gap-3 text-sm sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <dt class="text-slate-500">البريد</dt>
                            <dd class="mt-0.5 font-medium break-all">{{ $teacher->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">الهاتف</dt>
                            <dd class="mt-0.5 font-medium">{{ $teacher->phone ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">مصدر الحساب</dt>
                            <dd class="mt-0.5 font-medium">{{ $sourceLabel }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">آخر دخول</dt>
                            <dd class="mt-0.5 font-medium">{{ $teacher->last_login_at?->diffForHumans() ?? 'لم يسجّل دخولاً' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-admin.kpi-card label="كل المقررات" :value="$analytics['courses_count']" hint="معيّنة لهذا المعلم" />
        <x-admin.kpi-card label="منشورة" :value="$analytics['published_courses']" hint="{{ $analytics['draft_courses'] }} مسودة" />
        <x-admin.kpi-card label="الطلاب" :value="$analytics['students_count']" hint="مجموع الالتحاقات" />
        <x-admin.kpi-card label="الدروس" :value="$analytics['lessons_count']" hint="عبر كل المقررات" />
    </div>

    @php
        $tabs = [
            ['key' => 'overview', 'label' => 'نظرة عامة'],
            ['key' => 'courses', 'label' => 'المقررات'],
            ['key' => 'assign', 'label' => 'تعيين مقررات'],
            ['key' => 'account', 'label' => 'الحساب'],
        ];
        $tabNav = collect($tabs)->map(fn ($t) => [
            'label' => $t['label'],
            'href' => route('admin.teachers.show', ['teacher' => $teacher, 'tab' => $t['key']]),
            'active' => $tab === $t['key'],
        ])->all();
    @endphp

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_18.5rem]">
        <div class="min-w-0">
            <x-admin.tab-nav :tabs="$tabNav" class="mb-0" />
            <div class="rounded-b-2xl rounded-t-none border border-t-0 border-[var(--color-line)] bg-white p-5 sm:p-6">
                @if ($tab === 'overview')
                    <div class="space-y-6">
                        <section>
                            <h2 class="text-base font-bold text-[var(--color-ink)]">ملخص التدريس</h2>
                            <p class="mt-1 text-sm text-slate-500">صورة سريعة لعبء المعلم على المنصة.</p>
                            <div class="mt-4 grid gap-3 sm:grid-cols-3">
                                <div class="rounded-2xl border border-[var(--color-line)] bg-[var(--color-sand)]/80 p-4">
                                    <p class="text-xs text-slate-500">نسبة النشر</p>
                                    <p class="mt-1 text-xl font-bold text-[var(--color-ink)]">
                                        @if ($analytics['courses_count'] > 0)
                                            {{ (int) round(($analytics['published_courses'] / $analytics['courses_count']) * 100) }}%
                                        @else
                                            —
                                        @endif
                                    </p>
                                </div>
                                <div class="rounded-2xl border border-[var(--color-line)] bg-[var(--color-sand)]/80 p-4">
                                    <p class="text-xs text-slate-500">متوسط الطلاب / مقرر</p>
                                    <p class="mt-1 text-xl font-bold text-[var(--color-ink)]">
                                        @if ($analytics['courses_count'] > 0)
                                            {{ round($analytics['students_count'] / $analytics['courses_count'], 1) }}
                                        @else
                                            —
                                        @endif
                                    </p>
                                </div>
                                <div class="rounded-2xl border border-[var(--color-line)] bg-[var(--color-sand)]/80 p-4">
                                    <p class="text-xs text-slate-500">متوسط الدروس / مقرر</p>
                                    <p class="mt-1 text-xl font-bold text-[var(--color-ink)]">
                                        @if ($analytics['courses_count'] > 0)
                                            {{ round($analytics['lessons_count'] / $analytics['courses_count'], 1) }}
                                        @else
                                            —
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="mb-3 flex items-center justify-between gap-3">
                                <h2 class="text-base font-bold text-[var(--color-ink)]">أحدث المقررات</h2>
                                <a href="{{ route('admin.teachers.show', ['teacher' => $teacher, 'tab' => 'courses']) }}" class="text-sm font-medium text-[var(--color-secondary)] hover:underline">عرض الكل</a>
                            </div>
                            <ul class="divide-y divide-slate-100 rounded-2xl border border-[var(--color-line)]">
                                @forelse ($teacher->instructedCourses->take(5) as $course)
                                    <li class="flex flex-wrap items-center justify-between gap-2 px-4 py-3 text-sm">
                                        <div class="min-w-0">
                                            <a href="{{ route('admin.courses.show', $course) }}" class="font-semibold text-[var(--color-ink)] hover:text-[var(--color-primary)]">{{ $course->title }}</a>
                                            <p class="mt-0.5 text-xs text-slate-500">{{ $course->enrollments_count }} طالب · {{ $course->lessons_count }} درس</p>
                                        </div>
                                        <x-admin.status-badge :status="$course->status" />
                                    </li>
                                @empty
                                    <li class="px-4 py-8 text-center text-sm text-slate-500">لا مقررات معينة بعد.</li>
                                @endforelse
                            </ul>
                        </section>

                        <section>
                            <h2 class="text-base font-bold text-[var(--color-ink)]">بيانات التواصل</h2>
                            <dl class="mt-3 grid gap-3 sm:grid-cols-2">
                                <div class="rounded-2xl border border-[var(--color-line)] p-4">
                                    <dt class="text-xs text-slate-500">البريد الإلكتروني</dt>
                                    <dd class="mt-1 break-all font-medium">{{ $teacher->email }}</dd>
                                </div>
                                <div class="rounded-2xl border border-[var(--color-line)] p-4">
                                    <dt class="text-xs text-slate-500">الهاتف</dt>
                                    <dd class="mt-1 font-medium">{{ $teacher->phone ?: '—' }}</dd>
                                </div>
                                <div class="rounded-2xl border border-[var(--color-line)] p-4">
                                    <dt class="text-xs text-slate-500">الجامعة</dt>
                                    <dd class="mt-1 font-medium">{{ $teacher->university ?: '—' }}</dd>
                                </div>
                                <div class="rounded-2xl border border-[var(--color-line)] p-4">
                                    <dt class="text-xs text-slate-500">تاريخ الإنشاء</dt>
                                    <dd class="mt-1 font-medium">{{ $teacher->created_at?->translatedFormat('d M Y') ?? '—' }}</dd>
                                </div>
                            </dl>
                        </section>
                    </div>
                @elseif ($tab === 'courses')
                    <section>
                        <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                            <div>
                                <h2 class="text-base font-bold text-[var(--color-ink)]">مقررات المعلم</h2>
                                <p class="mt-1 text-sm text-slate-500">{{ $analytics['courses_count'] }} مقرر معيّن حالياً.</p>
                            </div>
                            <a href="{{ route('admin.teachers.show', ['teacher' => $teacher, 'tab' => 'assign']) }}" class="rounded-xl border border-[var(--color-primary)]/30 bg-[var(--color-primary-light)] px-3 py-2 text-sm font-medium text-[var(--color-primary-hover)]">تعديل التعيين</a>
                        </div>

                        @if ($teacher->instructedCourses->isEmpty())
                            <x-admin.empty-state title="لا مقررات" description="عيّن مقررات من تبويب التعيين." />
                        @else
                            <div class="overflow-hidden rounded-2xl border border-[var(--color-line)]">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
                                        <tr>
                                            <th class="px-4 py-3 text-right">المقرر</th>
                                            <th class="px-4 py-3 text-right">الحالة</th>
                                            <th class="px-4 py-3 text-right">الطلاب</th>
                                            <th class="px-4 py-3 text-right">الدروس</th>
                                            <th class="px-4 py-3 text-right"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach ($teacher->instructedCourses as $course)
                                            <tr class="hover:bg-slate-50/80">
                                                <td class="px-4 py-3 font-medium text-[var(--color-ink)]">{{ $course->title }}</td>
                                                <td class="px-4 py-3"><x-admin.status-badge :status="$course->status" /></td>
                                                <td class="px-4 py-3 tabular-nums">{{ $course->enrollments_count }}</td>
                                                <td class="px-4 py-3 tabular-nums">{{ $course->lessons_count }}</td>
                                                <td class="px-4 py-3 text-left">
                                                    <a href="{{ route('admin.courses.show', $course) }}" class="text-xs font-semibold text-[var(--color-secondary)] hover:underline">فتح</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </section>
                @elseif ($tab === 'assign')
                    <section x-data="{ q: '' }">
                        <div class="mb-4">
                            <h2 class="text-base font-bold text-[var(--color-ink)]">تعيين المقررات</h2>
                            <p class="mt-1 text-sm text-slate-500">حدّد المقررات التي يدرّسها هذا المعلم ثم احفظ.</p>
                        </div>

                        <div class="mb-4">
                            <label class="mb-1 block text-xs font-medium text-slate-500" for="course-filter">بحث سريع</label>
                            <input id="course-filter" type="search" x-model="q" placeholder="اسم المقرر..."
                                   class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
                        </div>

                        <form method="POST" action="{{ route('admin.teachers.assign-courses', $teacher) }}">
                            @csrf
                            <div class="mb-4 max-h-[28rem] space-y-2 overflow-y-auto rounded-2xl border border-[var(--color-line)] p-3">
                                @forelse ($courses as $course)
                                    <label
                                        class="flex cursor-pointer items-start gap-3 rounded-xl border border-transparent px-3 py-2.5 transition hover:border-[var(--color-line)] hover:bg-[var(--color-sand)]"
                                        x-show="!q || {{ Js::from($course->title) }}.includes(q)"
                                    >
                                        <input type="checkbox" name="course_ids[]" value="{{ $course->id }}"
                                               class="mt-1 size-4 rounded border-slate-300 text-[var(--color-primary)] focus:ring-[var(--color-primary)]/30"
                                               @checked($course->instructor_user_id === $teacher->id)>
                                        <span class="min-w-0 flex-1">
                                            <span class="block text-sm font-semibold text-[var(--color-ink)]">{{ $course->title }}</span>
                                            <span class="mt-0.5 block text-xs text-slate-500">
                                                <x-admin.status-badge :status="$course->status" class="align-middle" />
                                                @if ($course->instructor_user_id && $course->instructor_user_id !== $teacher->id)
                                                    · حالياً: {{ $course->instructor?->name ?? 'محاضر آخر' }}
                                                @elseif ($course->instructor_user_id === $teacher->id)
                                                    · معيّن لهذا المعلم
                                                @else
                                                    · بدون محاضر
                                                @endif
                                            </span>
                                        </span>
                                    </label>
                                @empty
                                    <p class="px-2 py-6 text-center text-sm text-slate-500">لا مقررات في النظام.</p>
                                @endforelse
                            </div>
                            <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">حفظ التعيين</button>
                        </form>
                    </section>
                @else
                    <div class="space-y-6">
                        <section>
                            <h2 class="text-base font-bold text-[var(--color-ink)]">تفاصيل الحساب</h2>
                            <dl class="mt-3 grid gap-3 sm:grid-cols-2">
                                <div class="rounded-2xl border border-[var(--color-line)] p-4">
                                    <dt class="text-xs text-slate-500">الحالة</dt>
                                    <dd class="mt-1"><x-admin.status-badge :status="$teacher->status" /></dd>
                                </div>
                                <div class="rounded-2xl border border-[var(--color-line)] p-4">
                                    <dt class="text-xs text-slate-500">مصدر الإنشاء</dt>
                                    <dd class="mt-1 font-medium">{{ $sourceLabel }}</dd>
                                </div>
                                <div class="rounded-2xl border border-[var(--color-line)] p-4">
                                    <dt class="text-xs text-slate-500">أُنشئ في</dt>
                                    <dd class="mt-1 font-medium">{{ $teacher->created_at?->translatedFormat('d M Y — H:i') ?? '—' }}</dd>
                                </div>
                                <div class="rounded-2xl border border-[var(--color-line)] p-4">
                                    <dt class="text-xs text-slate-500">آخر تحديث</dt>
                                    <dd class="mt-1 font-medium">{{ $teacher->updated_at?->diffForHumans() ?? '—' }}</dd>
                                </div>
                                <div class="rounded-2xl border border-[var(--color-line)] p-4 sm:col-span-2">
                                    <dt class="text-xs text-slate-500">ملاحظات إدارية</dt>
                                    <dd class="mt-1 whitespace-pre-wrap text-sm leading-relaxed text-[var(--color-ink)]">{{ $teacher->admin_notes ?: 'لا ملاحظات.' }}</dd>
                                </div>
                            </dl>
                        </section>

                        <section class="rounded-2xl border border-rose-200 bg-rose-50/60 p-5">
                            <h2 class="text-base font-bold text-rose-900">منطقة خطر</h2>
                            <p class="mt-1 text-sm text-rose-800/80">حذف المعلم غير متاح إن كان لديه مقررات معيّنة. أعد التعيين أولاً.</p>
                            <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}" class="mt-4" onsubmit="return confirm('حذف المعلم نهائياً؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-xl border border-rose-300 bg-white px-4 py-2.5 text-sm font-semibold text-rose-800 hover:bg-rose-100">حذف المعلم</button>
                            </form>
                        </section>
                    </div>
                @endif
            </div>
        </div>

        <aside class="space-y-4 xl:sticky xl:top-24 xl:self-start">
            <div class="rounded-2xl border border-[var(--color-line)] bg-white p-4 shadow-[0_8px_24px_-18px_rgba(47,58,69,0.35)]">
                <h2 class="mb-3 text-sm font-bold text-[var(--color-ink)]">إجراءات سريعة</h2>
                <div class="space-y-2">
                    <a href="{{ route('admin.teachers.edit', $teacher) }}" class="flex w-full items-center justify-center rounded-xl border border-slate-200 px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">تعديل الملف</a>

                    @if ($teacher->id !== auth()->id() && $teacher->status === 'active' && ! $teacher->hasRole('super_admin') && Route::has('admin.users.impersonate'))
                        <form method="POST" action="{{ route('admin.users.impersonate', $teacher) }}">
                            @csrf
                            <button type="submit" class="w-full rounded-xl border border-amber-200 bg-amber-50 px-3 py-2.5 text-sm font-medium text-amber-900 hover:bg-amber-100">دخول كـ هذا المعلم</button>
                        </form>
                    @endif

                    @if ($teacher->status !== 'disabled')
                        <form method="POST" action="{{ route('admin.teachers.suspend', $teacher) }}" onsubmit="return confirm('تعليق حساب المعلم؟');">
                            @csrf
                            <button type="submit" class="w-full rounded-xl border border-rose-200 bg-rose-50 px-3 py-2.5 text-sm font-medium text-rose-800 hover:bg-rose-100">تعليق الحساب</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.teachers.activate', $teacher) }}">
                            @csrf
                            <button type="submit" class="w-full rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2.5 text-sm font-medium text-emerald-800 hover:bg-emerald-100">تفعيل الحساب</button>
                        </form>
                    @endif

                    <a href="{{ route('admin.teachers.show', ['teacher' => $teacher, 'tab' => 'assign']) }}" class="flex w-full items-center justify-center rounded-xl bg-[var(--color-primary)] px-3 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">تعيين مقررات</a>
                </div>
            </div>

            <div class="rounded-2xl border border-[var(--color-line)] bg-[var(--color-sand)]/70 p-4">
                <h2 class="mb-2 text-sm font-bold text-[var(--color-ink)]">ملخص سريع</h2>
                <ul class="space-y-2 text-sm text-slate-600">
                    <li class="flex justify-between gap-2"><span>المقررات</span><strong class="text-[var(--color-ink)]">{{ $analytics['courses_count'] }}</strong></li>
                    <li class="flex justify-between gap-2"><span>منشورة</span><strong class="text-[var(--color-ink)]">{{ $analytics['published_courses'] }}</strong></li>
                    <li class="flex justify-between gap-2"><span>الطلاب</span><strong class="text-[var(--color-ink)]">{{ $analytics['students_count'] }}</strong></li>
                    <li class="flex justify-between gap-2"><span>الدروس</span><strong class="text-[var(--color-ink)]">{{ $analytics['lessons_count'] }}</strong></li>
                </ul>
            </div>
        </aside>
    </div>
@endsection
