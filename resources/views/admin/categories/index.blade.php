@extends('layouts.admin')

@section('title', 'التصنيفات')
@section('heading', 'التصنيفات')
@section('subheading', 'تنظيم المقررات في تصنيفات')

@section('header-actions')
    <a href="{{ route('admin.categories.create') }}" class="admin-btn admin-btn-primary">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        تصنيف جديد
    </a>
@endsection

@section('content')
    <div class="admin-content-enter space-y-5">
        <x-admin.filter-bar>
            <form method="GET" class="grid w-full gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <div class="sm:col-span-2">
                    <label class="admin-label" for="q">بحث</label>
                    <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="بحث بالاسم أو الرابط..." class="admin-input">
                </div>
                <div>
                    <label class="admin-label" for="status">الحالة</label>
                    <select id="status" name="status" class="admin-input">
                        <option value="">الكل</option>
                        <option value="active" @selected(request('status') === 'active')>نشط</option>
                        <option value="archived" @selected(request('status') === 'archived')>مؤرشف</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button class="admin-btn admin-btn-dark">تصفية</button>
                    <a href="{{ route('admin.categories.index') }}" class="admin-btn admin-btn-ghost">مسح</a>
                </div>
            </form>
        </x-admin.filter-bar>

        @if ($categories->isEmpty())
            <x-admin.empty-state title="لا تصنيفات بعد" description="أضف تصنيفاً لتنظيم المقررات وسهولة التصفية.">
                <x-slot:actions>
                    <a href="{{ route('admin.categories.create') }}" class="admin-btn admin-btn-primary">تصنيف جديد</a>
                </x-slot:actions>
            </x-admin.empty-state>
        @else
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($categories as $category)
                    <article class="admin-panel group flex flex-col p-5 transition hover:-translate-y-0.5 hover:border-[var(--color-primary)]/35">
                        <div class="mb-4 flex items-start justify-between gap-3">
                            <div class="admin-entity-thumb !h-11 !w-11 text-sm">{{ mb_substr($category->name, 0, 1) }}</div>
                            <x-admin.status-badge :status="$category->status" :label="$category->status === 'active' ? 'نشط' : 'مؤرشف'" />
                        </div>
                        <h3 class="text-base font-semibold text-slate-900">{{ $category->name }}</h3>
                        <p class="mt-1 truncate text-xs text-slate-500" dir="ltr">{{ $category->slug }}</p>
                        <div class="mt-4 flex items-center gap-4 text-xs text-slate-500">
                            <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-2 py-1 font-semibold text-slate-700">
                                {{ $category->courses_count }} مقررات
                            </span>
                            <span>ترتيب {{ $category->position }}</span>
                        </div>
                        <div class="mt-5 flex flex-wrap gap-1.5 border-t border-slate-100 pt-4">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="admin-btn admin-btn-ghost admin-btn-sm">تعديل</a>
                            @if (Route::has('admin.categories.archive') && $category->status === 'active')
                                <form method="POST" action="{{ route('admin.categories.archive', $category) }}">
                                    @csrf
                                    <button class="admin-btn admin-btn-ghost admin-btn-sm">أرشفة</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('حذف التصنيف؟');">
                                @csrf @method('DELETE')
                                <button class="admin-btn admin-btn-danger admin-btn-sm">حذف</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($categories->hasPages())
                <div class="mt-1">{{ $categories->links() }}</div>
            @endif
        @endif
    </div>
@endsection
