@extends('layouts.admin')

@section('title', 'تعديل التصنيف')
@section('heading', 'تعديل التصنيف')
@section('subheading', $category->name)

@section('content')
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="mx-auto max-w-2xl space-y-4 rounded-2xl border border-[var(--color-line)] bg-white p-6">
        @csrf
        @method('PUT')
        @include('admin.categories._form', ['category' => $category])
        <div class="flex gap-2">
            <button class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white">حفظ</button>
            <a href="{{ route('admin.categories.index') }}" class="rounded-xl border px-4 py-2.5 text-sm">رجوع</a>
        </div>
    </form>

    @if (Route::has('admin.categories.merge'))
        <form method="POST" action="{{ route('admin.categories.merge', $category) }}" class="mx-auto mt-6 max-w-2xl rounded-2xl border border-amber-200 bg-amber-50 p-6">
            @csrf
            <p class="mb-3 text-sm font-medium text-amber-900">دمج هذا التصنيف في تصنيف آخر</p>
            <select name="target_id" required class="mb-3 w-full rounded-xl border px-3 py-2.5 text-sm">
                <option value="">اختر التصنيف الهدف</option>
                @foreach (\App\Models\Category::where('id', '!=', $category->id)->orderBy('name')->get() as $target)
                    <option value="{{ $target->id }}">{{ $target->name }}</option>
                @endforeach
            </select>
            <button class="rounded-xl bg-amber-700 px-4 py-2 text-sm text-white" onclick="return confirm('دمج التصنيفات؟');">دمج</button>
        </form>
    @endif
@endsection
