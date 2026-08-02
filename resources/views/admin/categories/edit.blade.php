@extends('layouts.admin')

@section('title', 'تعديل التصنيف')
@section('heading', 'تعديل التصنيف')
@section('subheading', $category->name)

@section('content')
    <div class="space-y-5">
        <x-admin.form-shell max-width="max-w-2xl">
            <x-slot:header>
                <p class="text-sm font-semibold text-slate-800">تعديل التصنيف</p>
                <p class="mt-0.5 text-xs text-slate-500">{{ $category->name }}</p>
            </x-slot:header>
            <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-5">
                @csrf
                @method('PUT')
                @include('admin.categories._form', ['category' => $category])
                <div class="flex flex-wrap gap-2 border-t border-slate-100 pt-5">
                    <button class="admin-btn admin-btn-primary">حفظ</button>
                    <a href="{{ route('admin.categories.index') }}" class="admin-btn admin-btn-ghost">رجوع</a>
                </div>
            </form>
        </x-admin.form-shell>

        @if (Route::has('admin.categories.merge'))
            <div class="mx-auto max-w-2xl overflow-hidden rounded-2xl border border-amber-200 bg-gradient-to-l from-amber-50 to-white p-5 sm:p-6">
                <p class="mb-1 text-sm font-semibold text-amber-950">دمج هذا التصنيف في تصنيف آخر</p>
                <p class="mb-4 text-xs text-amber-800/80">ستُنقل المقررات إلى التصنيف الهدف ثم يُحذف هذا التصنيف.</p>
                <form method="POST" action="{{ route('admin.categories.merge', $category) }}" class="space-y-3">
                    @csrf
                    <select name="target_id" required class="admin-input">
                        <option value="">اختر التصنيف الهدف</option>
                        @foreach (\App\Models\Category::where('id', '!=', $category->id)->orderBy('name')->get() as $target)
                            <option value="{{ $target->id }}">{{ $target->name }}</option>
                        @endforeach
                    </select>
                    <button class="admin-btn admin-btn-sm rounded-xl bg-amber-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-amber-800" onclick="return confirm('دمج التصنيفات؟');">دمج</button>
                </form>
            </div>
        @endif
    </div>
@endsection
