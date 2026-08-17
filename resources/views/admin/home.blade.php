@extends('layouts.admin')

@section('title', 'لوحة الإدارة')
@section('heading', 'لوحة التحكم')
@section('subheading', 'نظرة عامة على صحة المنصة والنشاط الأخير')

@section('header-actions')
    <a href="{{ route('admin.home', array_filter(['refresh' => 1, 'from' => $from ?? null, 'to' => $to ?? null])) }}"
       class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
        تحديث
    </a>
    @if (Route::has('admin.dashboard.export.pdf'))
        <form method="POST" action="{{ route('admin.dashboard.export.pdf') }}" class="inline">
            @csrf
            <input type="hidden" name="from" value="{{ $from ?? '' }}">
            <input type="hidden" name="to" value="{{ $to ?? '' }}">
            <button type="submit" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">PDF</button>
        </form>
    @endif
    @if (Route::has('admin.dashboard.export.excel'))
        <form method="POST" action="{{ route('admin.dashboard.export.excel') }}" class="inline">
            @csrf
            <input type="hidden" name="from" value="{{ $from ?? '' }}">
            <input type="hidden" name="to" value="{{ $to ?? '' }}">
            <button type="submit" class="admin-btn admin-btn-primary">Excel</button>
        </form>
    @endif
@endsection

@section('content')
    <form method="GET" action="{{ route('admin.home') }}" class="mb-6 flex flex-wrap items-end gap-3 rounded-2xl border border-[var(--color-line)] bg-white p-4">
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500" for="from">من تاريخ</label>
            <input id="from" type="date" name="from" value="{{ $from ?? '' }}"
                   class="rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500" for="to">إلى تاريخ</label>
            <input id="to" type="date" name="to" value="{{ $to ?? '' }}"
                   class="rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
        </div>
        <button type="submit" class="admin-btn admin-btn-primary">تطبيق</button>
        <a href="{{ route('admin.home') }}" class="admin-btn admin-btn-ghost">مسح</a>
    </form>

    @php
        $studentHref = Route::has('admin.students.index') ? route('admin.students.index') : (Route::has('admin.users.index') ? route('admin.users.index', ['type' => 'students']) : '#');
        $formatMoney = fn ($v) => number_format((float) $v, 2).' ر.س';
    @endphp

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4 2xl:grid-cols-5">
        <x-admin.kpi-card label="الطلاب" :value="number_format($cards['students'])" :href="$studentHref" hint="إدارة الطلاب" />
        <x-admin.kpi-card label="المقررات" :value="number_format($cards['courses'])" :href="route('admin.courses.index')" />
        <x-admin.kpi-card label="التصنيفات" :value="number_format($cards['categories'])" />
        <x-admin.kpi-card label="الدروس" :value="number_format($cards['lessons'])" :href="route('admin.lessons.index')" />
        <x-admin.kpi-card label="الفيديوهات" :value="number_format($cards['videos'])" />
        <x-admin.kpi-card label="الطلبات" :value="number_format($cards['orders'])" />
        <x-admin.kpi-card label="اشتراكات نشطة" :value="number_format($cards['subscriptions_active'])" />
        <x-admin.kpi-card label="إيرادات إجمالية" :value="$formatMoney($cards['revenue_total'])" />
        <x-admin.kpi-card label="إيرادات اليوم" :value="$formatMoney($cards['revenue_today'])" />
        <x-admin.kpi-card label="إيرادات الشهر" :value="$formatMoney($cards['revenue_month'])" />
        <x-admin.kpi-card label="الاختبارات" :value="number_format($cards['quizzes'])" />
        <x-admin.kpi-card label="نشطون اليوم (DAU)" :value="number_format($cards['dau'])" />
        <x-admin.kpi-card label="تذاكر مفتوحة" :value="number_format($cards['tickets_open'])" />
        <x-admin.kpi-card label="إشعارات غير مقروءة" :value="number_format($cards['notifications_unread'])" />
        <x-admin.kpi-card label="وظائف فاشلة" :value="number_format($failedJobs)" :href="route('admin.ops.index')" hint="مراقبة التشغيل" />
    </div>

    @php
        $chartTitles = [
            'revenue' => 'الإيرادات (7 أيام)',
            'student_growth' => 'نمو الطلاب',
            'orders' => 'الطلبات',
            'dau' => 'المستخدمون النشطون يومياً',
            'quiz_attempts' => 'محاولات الاختبارات',
            'subscriptions' => 'الاشتراكات',
        ];
    @endphp

    <div class="mt-8 grid gap-6 lg:grid-cols-2 xl:grid-cols-3">
        @foreach ($chartTitles as $key => $title)
            @php $series = $charts[$key] ?? ['labels' => [], 'counts' => []]; @endphp
            <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5 shadow-[0_8px_24px_-16px_rgba(12,31,28,0.45)]">
                <h2 class="mb-4 text-base font-semibold text-slate-900">{{ $title }}</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-xs">
                        <thead>
                            <tr class="text-slate-500">
                                <th class="px-2 py-1 text-right">التاريخ</th>
                                <th class="px-2 py-1 text-right">القيمة</th>
                                <th class="px-2 py-1 text-right w-32">اتجاه</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($series['labels'] as $i => $label)
                                @php $count = $series['counts'][$i] ?? 0; $max = max(1, max($series['counts'] ?: [1])); @endphp
                                <tr>
                                    <td class="px-2 py-1.5">{{ $label }}</td>
                                    <td class="px-2 py-1.5 font-medium">{{ number_format($count) }}</td>
                                    <td class="px-2 py-1.5">
                                        <div class="h-2 rounded-full bg-slate-100">
                                            <div class="h-2 rounded-full bg-[var(--color-primary)]" style="width: {{ min(100, ($count / $max) * 100) }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-3">
        <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5 lg:col-span-1">
            <h2 class="mb-4 text-base font-semibold text-slate-900">اختصارات سريعة</h2>
            <div class="grid gap-2">
                @foreach ($quickActions as $action)
                    <a href="{{ $action['href'] }}" class="rounded-xl bg-[var(--color-sand)] px-4 py-3 text-sm font-medium text-slate-800 ring-1 ring-[var(--color-line)] hover:bg-[var(--color-primary-light)]">{{ $action['label'] }}</a>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5 lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-base font-semibold text-slate-900">أحدث الطلاب</h2>
                <a href="{{ $studentHref }}" class="text-sm font-medium text-[var(--color-primary)] hover:underline">عرض الكل</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($recent['students'] as $student)
                    <div class="flex items-center justify-between gap-3 py-3 text-sm">
                        <div class="min-w-0">
                            <p class="truncate font-medium text-slate-900">{{ $student->name }}</p>
                            <p class="truncate text-xs text-slate-500">{{ $student->email }} · {{ $student->created_at?->diffForHumans() }}</p>
                        </div>
                        @if (Route::has('admin.students.show'))
                            <a href="{{ route('admin.students.show', $student) }}" class="shrink-0 text-[var(--color-primary)] hover:underline">عرض</a>
                        @endif
                    </div>
                @empty
                    <p class="py-6 text-sm text-slate-500">لا يوجد طلاب بعد.</p>
                @endforelse
            </div>
        </section>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        @foreach ([
            'payments' => ['title' => 'أحدث المدفوعات', 'empty' => 'لا مدفوعات.'],
            'quiz_attempts' => ['title' => 'أحدث محاولات الاختبارات', 'empty' => 'لا محاولات.'],
            'comments' => ['title' => 'أحدث التعليقات', 'empty' => 'لا تعليقات.'],
            'tickets' => ['title' => 'أحدث التذاكر', 'empty' => 'لا تذاكر.'],
        ] as $key => $meta)
            <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
                <h2 class="mb-4 text-base font-semibold text-slate-900">{{ $meta['title'] }}</h2>
                <div class="divide-y divide-slate-100 text-sm">
                    @forelse ($recent[$key] as $item)
                        <div class="py-3">
                            @if ($key === 'payments')
                                <p class="font-medium text-slate-900">{{ number_format((float) $item->amount, 2) }} ر.س · {{ $item->user?->name ?? '—' }}</p>
                                <p class="text-xs text-slate-500">{{ $item->status }} · {{ $item->created_at?->diffForHumans() }}</p>
                            @elseif ($key === 'quiz_attempts')
                                <p class="font-medium text-slate-900">{{ $item->quiz?->title ?? 'اختبار' }}</p>
                                <p class="text-xs text-slate-500">الدرجة: {{ $item->score ?? '—' }} · {{ $item->created_at?->diffForHumans() }}</p>
                            @elseif ($key === 'comments')
                                <p class="font-medium text-slate-900">{{ $item->user?->name ?? '—' }} على {{ $item->lesson?->title ?? 'درس' }}</p>
                                <p class="text-xs text-slate-500">{{ Str::limit($item->body ?? '', 80) }} · {{ $item->created_at?->diffForHumans() }}</p>
                            @elseif ($key === 'tickets')
                                <p class="font-medium text-slate-900">{{ $item->subject }}</p>
                                <p class="text-xs text-slate-500">{{ $item->student?->name ?? '—' }} · {{ $item->status }} · {{ $item->created_at?->diffForHumans() }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="py-6 text-slate-500">{{ $meta['empty'] }}</p>
                    @endforelse
                </div>
            </section>
        @endforeach
    </div>
@endsection
