@extends('layouts.instructor')

@section('title', 'لوحة الأداء')
@section('heading', 'لوحة الأداء')
@section('subheading', 'ملخص تشغيلي لمقرراتك وعبء التدريس')

@section('header-actions')
    <a href="{{ route('instructor.reports.index') }}" class="rounded-2xl border border-[var(--color-line)] bg-white px-3.5 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">التقارير</a>
@endsection

@section('content')
    <div class="mx-auto max-w-6xl space-y-8">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin.kpi-card label="المقررات" :value="$stats['courses']" :hint="$byStatus['published'].' منشور · '.$byStatus['draft'].' مسودة'" />
            <x-admin.kpi-card label="الطلاب" :value="$stats['students']" hint="التحاقات نشطة" />
            <x-admin.kpi-card label="الدروس والاختبارات" :value="$stats['lessons'] + $stats['quizzes']" :hint="$stats['lessons'].' درس · '.$stats['quizzes'].' اختبار'" />
            <x-admin.kpi-card label="بانتظار التقييم" :value="$stats['pending_submissions']" :hint="$stats['assignments'].' واجب · '.$stats['sessions'].' جلسة'" />
        </div>

        <section class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-2xl border border-[var(--color-line)] bg-white p-5 lg:col-span-2">
                <h2 class="font-bold text-[var(--color-ink)]">أداء المقررات</h2>
                <p class="mt-1 text-sm text-slate-500">طلاب ودروس واختبارات لكل مقرر.</p>
                @if ($courses->isEmpty())
                    <p class="mt-6 text-sm text-slate-500">لا مقررات لعرضها.</p>
                @else
                    <div class="mt-4 overflow-hidden rounded-xl border border-[var(--color-line)]">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
                                <tr>
                                    <th class="px-4 py-3 text-right">المقرر</th>
                                    <th class="px-4 py-3 text-right">الحالة</th>
                                    <th class="px-4 py-3 text-right">طلاب</th>
                                    <th class="px-4 py-3 text-right">دروس</th>
                                    <th class="px-4 py-3 text-right">اختبارات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($courses as $course)
                                    <tr class="hover:bg-slate-50/80">
                                        <td class="px-4 py-3">
                                            <a href="{{ route('instructor.courses.show', $course) }}" class="font-semibold text-[var(--color-ink)] hover:text-[var(--color-primary)]">{{ $course->title }}</a>
                                        </td>
                                        <td class="px-4 py-3"><x-admin.status-badge :status="$course->status" /></td>
                                        <td class="px-4 py-3 tabular-nums">{{ $course->enrollments_count }}</td>
                                        <td class="px-4 py-3 tabular-nums">{{ $course->lessons_count }}</td>
                                        <td class="px-4 py-3 tabular-nums">{{ $course->quizzes_count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="space-y-4">
                <div class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
                    <h2 class="font-bold text-[var(--color-ink)]">حالة المقررات</h2>
                    <ul class="mt-4 space-y-3 text-sm">
                        <li class="flex justify-between"><span class="text-slate-500">منشور</span><strong>{{ $byStatus['published'] }}</strong></li>
                        <li class="flex justify-between"><span class="text-slate-500">مسودة</span><strong>{{ $byStatus['draft'] }}</strong></li>
                        <li class="flex justify-between"><span class="text-slate-500">مؤرشف</span><strong>{{ $byStatus['archived'] }}</strong></li>
                    </ul>
                </div>
                <div class="rounded-2xl border border-[var(--color-line)] bg-[var(--color-sand)] p-5">
                    <h2 class="font-bold text-[var(--color-ink)]">نشاط إضافي</h2>
                    <ul class="mt-4 space-y-3 text-sm">
                        <li class="flex justify-between"><span class="text-slate-500">واجبات</span><strong>{{ $stats['assignments'] }}</strong></li>
                        <li class="flex justify-between"><span class="text-slate-500">جلسات مباشرة</span><strong>{{ $stats['sessions'] }}</strong></li>
                        <li class="flex justify-between"><span class="text-slate-500">إعلانات</span><strong>{{ $stats['announcements'] }}</strong></li>
                    </ul>
                </div>
            </div>
        </section>
    </div>
@endsection
