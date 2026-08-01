@extends('layouts.admin')

@section('title', 'التصنيفات')
@section('heading', 'التصنيفات')
@section('subheading', 'تنظيم المقررات في تصنيفات')

@section('header-actions')
    <a href="{{ route('admin.categories.create') }}" class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white">تصنيف جديد</a>
@endsection

@section('content')
    <x-admin.filter-bar class="mb-5">
        <form method="GET" class="flex w-full flex-wrap items-end gap-3">
            <div class="min-w-[200px] flex-1">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="بحث بالاسم أو الرابط..." class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            </div>
            <div class="min-w-[120px]">
                <select name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                    <option value="">الكل</option>
                    <option value="active" @selected(request('status') === 'active')>نشط</option>
                    <option value="archived" @selected(request('status') === 'archived')>مؤرشف</option>
                </select>
            </div>
            <button class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm text-white">تصفية</button>
        </form>
    </x-admin.filter-bar>

    <x-admin.data-table>
        <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
            <tr>
                <th class="px-4 py-3 text-right">الاسم</th>
                <th class="px-4 py-3 text-right">الرابط</th>
                <th class="px-4 py-3 text-right">المقررات</th>
                <th class="px-4 py-3 text-right">الترتيب</th>
                <th class="px-4 py-3 text-right">الحالة</th>
                <th class="px-4 py-3 text-right">إجراءات</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($categories as $category)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $category->name }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $category->slug }}</td>
                    <td class="px-4 py-3">{{ $category->courses_count }}</td>
                    <td class="px-4 py-3">{{ $category->position }}</td>
                    <td class="px-4 py-3">{{ $category->status === 'active' ? 'نشط' : 'مؤرشف' }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="rounded-lg border px-3 py-1.5 text-xs">تعديل</a>
                            @if (Route::has('admin.categories.archive') && $category->status === 'active')
                                <form method="POST" action="{{ route('admin.categories.archive', $category) }}">@csrf<button class="rounded-lg border px-3 py-1.5 text-xs">أرشفة</button></form>
                            @endif
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('حذف التصنيف؟');">
                                @csrf @method('DELETE')
                                <button class="rounded-lg border border-rose-200 px-3 py-1.5 text-xs text-rose-700">حذف</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500">لا تصنيفات.</td></tr>
            @endforelse
        </tbody>
    </x-admin.data-table>

    @if ($categories->hasPages())
        <div class="mt-4">{{ $categories->links() }}</div>
    @endif
@endsection
