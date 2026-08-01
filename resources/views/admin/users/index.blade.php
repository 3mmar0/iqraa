@extends('layouts.admin')

@section('title', $pageTitle)
@section('heading', $pageTitle)
@section('subheading', $type === 'students' ? 'إدارة حسابات الطلاب والدخول نيابةً عنهم' : 'إدارة فريق العمل والأدوار والدخول نيابةً عنهم')

@section('header-actions')
    <a href="{{ route('admin.users.create', ['type' => $type]) }}" class="inline-flex items-center gap-2 rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-teal-800">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        {{ $type === 'students' ? 'طالب جديد' : 'عضو جديد' }}
    </a>
@endsection

@section('content')
    <div class="mb-5 flex gap-2">
        <a href="{{ route('admin.users.index', ['type' => 'students']) }}"
           class="rounded-xl px-4 py-2 text-sm font-medium {{ $type === 'students' ? 'bg-teal-700 text-white' : 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50' }}">الطلاب</a>
        <a href="{{ route('admin.users.index', ['type' => 'staff']) }}"
           class="rounded-xl px-4 py-2 text-sm font-medium {{ $type === 'staff' ? 'bg-teal-700 text-white' : 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50' }}">فريق العمل</a>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-5 grid gap-3 rounded-2xl border border-[var(--color-line)] bg-white p-4 sm:grid-cols-2 lg:grid-cols-4">
        <input type="hidden" name="type" value="{{ $type }}">
        <div class="sm:col-span-2">
            <label class="mb-1 block text-xs font-medium text-slate-500" for="q">بحث</label>
            <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="الاسم، البريد، الهاتف..."
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
            <label class="mb-1 block text-xs font-medium text-slate-500" for="role">الدور</label>
            <select id="role" name="role" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                <option value="">الكل</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->slug }}" @selected(request('role') === $role->slug)>{{ $role->name_ar }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-4">
            <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-slate-800">تطبيق التصفية</button>
            <a href="{{ route('admin.users.index', ['type' => $type]) }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50">مسح</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_8px_24px_-16px_rgba(12,31,28,0.35)]">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">المستخدم</th>
                        <th class="px-4 py-3">الأدوار</th>
                        <th class="px-4 py-3">الحالة</th>
                        <th class="px-4 py-3">المصدر</th>
                        <th class="px-4 py-3">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-900">{{ $user->name }}</div>
                                <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                @if ($user->phone)
                                    <div class="text-xs text-slate-400">{{ $user->phone }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @forelse ($user->roles as $role)
                                        <span class="rounded-full bg-teal-50 px-2 py-0.5 text-xs font-medium text-teal-800">{{ $role->name_ar }}</span>
                                    @empty
                                        <span class="text-xs text-slate-400">بدون دور</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusMap = [
                                        'active' => 'bg-emerald-50 text-emerald-800',
                                        'invited' => 'bg-amber-50 text-amber-800',
                                        'disabled' => 'bg-rose-50 text-rose-800',
                                    ];
                                    $statusLabel = ['active' => 'نشط', 'invited' => 'مدعو', 'disabled' => 'معطّل'][$user->status] ?? $user->status;
                                @endphp
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $statusMap[$user->status] ?? 'bg-slate-100 text-slate-700' }}">{{ $statusLabel }}</span>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500">
                                {{ $user->creation_source === 'admin_created' ? 'إداري' : 'تسجيل ذاتي' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap items-center gap-2">
                                    @if ($user->id !== auth()->id() && $user->status === 'active')
                                        <form method="POST" action="{{ route('admin.users.impersonate', $user) }}">
                                            @csrf
                                            <button type="submit" class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-medium text-amber-900 hover:bg-amber-100">دخول كـ</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.users.edit', $user) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-white hover:shadow-sm">تعديل</a>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-medium text-rose-700 hover:bg-rose-50">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-slate-500">لا توجد نتائج مطابقة.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="border-t border-slate-100 px-4 py-3">{{ $users->links() }}</div>
        @endif
    </div>
@endsection
