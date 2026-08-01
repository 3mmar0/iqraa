@extends('layouts.admin')

@section('title', 'الطلاب')
@section('heading', 'الطلاب')
@section('subheading', 'إدارة حسابات الطلاب والالتحاق والاشتراكات')

@section('header-actions')
    <a href="{{ route('admin.students.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-teal-800">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        طالب جديد
    </a>
@endsection

@section('content')
    <div x-data="{ selected: [] }">
    <x-admin.filter-bar class="mb-5">
        <form method="GET" action="{{ route('admin.students.index') }}" class="grid w-full gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div class="sm:col-span-2">
                <label class="mb-1 block text-xs font-medium text-slate-500" for="q">بحث</label>
                <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="الاسم، البريد، الهاتف، المعرّف..."
                       class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500" for="status">الحالة</label>
                <select id="status" name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                    <option value="">الكل</option>
                    <option value="active" @selected(request('status') === 'active')>نشط</option>
                    <option value="invited" @selected(request('status') === 'invited')>مدعو</option>
                    <option value="disabled" @selected(request('status') === 'disabled')>معطّل</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500" for="group_id">المجموعة</label>
                <select id="group_id" name="group_id" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                    <option value="">الكل</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}" @selected((string) request('group_id') === (string) $group->id)>{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500" for="paid">الاشتراك</label>
                <select id="paid" name="paid" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                    <option value="">الكل</option>
                    <option value="1" @selected(request('paid') === '1')>مدفوع / نشط</option>
                    <option value="0" @selected(request('paid') === '0')>بدون اشتراك</option>
                </select>
            </div>
            <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-4">
                <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-slate-800">تطبيق</button>
                <a href="{{ route('admin.students.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50">مسح</a>
                @if (Route::has('admin.students.bulk.export'))
                    <a href="{{ route('admin.students.bulk.export', request()->query()) }}" class="rounded-xl border border-teal-200 bg-teal-50 px-4 py-2.5 text-sm font-medium text-teal-800 hover:bg-teal-100">تصدير CSV</a>
                @endif
            </div>
        </form>
    </x-admin.filter-bar>

    <form id="bulk-form" method="POST">
        @csrf
        <x-admin.bulk-bar class="mb-4" x-show="selected.length > 0" style="display: none;">
            <span x-text="`${selected.length} طالب محدد`"></span>
            <div class="flex flex-wrap gap-2">
                @if (Route::has('admin.students.bulk.activate'))
                    <button formaction="{{ route('admin.students.bulk.activate') }}" type="submit" class="rounded-lg bg-emerald-700 px-3 py-1.5 text-xs font-medium text-white">تفعيل</button>
                @endif
                @if (Route::has('admin.students.bulk.deactivate'))
                    <button formaction="{{ route('admin.students.bulk.deactivate') }}" type="submit" class="rounded-lg bg-amber-700 px-3 py-1.5 text-xs font-medium text-white">تعطيل</button>
                @endif
                @if (Route::has('admin.students.bulk.destroy'))
                    <button formaction="{{ route('admin.students.bulk.destroy') }}" type="submit" onclick="return confirm('حذف الطلاب المحددين؟');" class="rounded-lg bg-rose-700 px-3 py-1.5 text-xs font-medium text-white">حذف</button>
                @endif
            </div>
        </x-admin.bulk-bar>
    </form>

    <x-admin.data-table>
            <thead class="bg-slate-50 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3 w-10"><span class="sr-only">تحديد</span></th>
                    <th class="px-4 py-3">الطالب</th>
                    <th class="px-4 py-3">الهاتف</th>
                    <th class="px-4 py-3">البريد</th>
                    <th class="px-4 py-3">الجامعة</th>
                    <th class="px-4 py-3">المجموعة</th>
                    <th class="px-4 py-3">الحالة</th>
                    <th class="px-4 py-3">التسجيل</th>
                    <th class="px-4 py-3">آخر دخول</th>
                    <th class="px-4 py-3">الاشتراك</th>
                    <th class="px-4 py-3">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($students as $student)
                    @php
                        $statusMap = [
                            'active' => 'bg-emerald-50 text-emerald-800',
                            'invited' => 'bg-amber-50 text-amber-800',
                            'disabled' => 'bg-rose-50 text-rose-800',
                        ];
                        $statusLabel = ['active' => 'نشط', 'invited' => 'مدعو', 'disabled' => 'معطّل'][$student->status] ?? $student->status;
                        $activeSub = $student->subscriptions->firstWhere('status', 'active');
                    @endphp
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-4 py-3">
                            <input type="checkbox" name="ids[]" value="{{ $student->id }}" form="bulk-form"
                                   class="rounded border-slate-300 text-teal-700" x-model.number="selected">
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-teal-100 text-sm font-semibold text-teal-800">
                                    {{ mb_substr($student->name, 0, 1) }}
                                </span>
                                <div>
                                    <a href="{{ route('admin.students.show', $student) }}" class="font-medium text-slate-900 hover:text-teal-700">{{ $student->name }}</a>
                                    <div class="text-xs text-slate-400">#{{ $student->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $student->phone ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $student->email }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $student->university ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $student->group?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $statusMap[$student->status] ?? 'bg-slate-100 text-slate-700' }}">{{ $statusLabel }}</span>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $student->created_at?->format('Y-m-d') ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $student->last_login_at?->diffForHumans() ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if ($activeSub)
                                <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-800">نشط</span>
                            @else
                                <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.students.show', $student) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-white">عرض</a>
                                <a href="{{ route('admin.students.edit', $student) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-white">تعديل</a>
                                @if ($student->id !== auth()->id() && $student->status === 'active')
                                    <form method="POST" action="{{ route('admin.students.impersonate', $student) }}">
                                        @csrf
                                        <input type="hidden" name="context" value="student">
                                        <button type="submit" class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-medium text-amber-900 hover:bg-amber-100">دخول كـ</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="px-4 py-12 text-center text-slate-500">لا توجد نتائج مطابقة.</td>
                    </tr>
                @endforelse
            </tbody>
        </x-admin.data-table>

    @if ($students->hasPages())
        <div class="mt-4">{{ $students->links() }}</div>
    @endif
    </div>
@endsection
