@extends('layouts.admin')

@section('title', $teacher->name)
@section('heading', $teacher->name)
@section('subheading', 'ملف المعلم والتحليلات')

@section('header-actions')
    <div class="flex flex-wrap gap-2">
        @if ($teacher->id !== auth()->id() && $teacher->status === 'active' && ! $teacher->hasRole('super_admin'))
            <form method="POST" action="{{ route('admin.users.impersonate', $teacher) }}">
                @csrf
                <button type="submit" class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-2.5 text-sm text-amber-900">دخول كـ</button>
            </form>
        @endif
        <a href="{{ route('admin.teachers.edit', $teacher) }}" class="rounded-xl border bg-white px-4 py-2.5 text-sm">تعديل</a>
    </div>
@endsection

@section('content')
    <div class="mb-6 grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border bg-white p-5">
            <p class="text-sm text-slate-500">المقررات</p>
            <p class="mt-1 text-2xl font-bold">{{ $analytics['courses_count'] }}</p>
        </div>
        <div class="rounded-2xl border bg-white p-5">
            <p class="text-sm text-slate-500">الطلاب (تقريبي)</p>
            <p class="mt-1 text-2xl font-bold">{{ $analytics['students_count'] }}</p>
        </div>
        <div class="rounded-2xl border bg-white p-5">
            <p class="text-sm text-slate-500">مقررات منشورة</p>
            <p class="mt-1 text-2xl font-bold">{{ $analytics['published_courses'] }}</p>
        </div>
    </div>

    <section class="mb-6 rounded-2xl border bg-white p-5">
        <h2 class="mb-3 font-semibold">تعيين مقررات</h2>
        <form method="POST" action="{{ route('admin.teachers.assignCourses', $teacher) }}" class="flex flex-wrap gap-2">
            @csrf
            @foreach ($courses as $course)
                <label class="flex items-center gap-2 rounded-lg border px-3 py-2 text-sm">
                    <input type="checkbox" name="course_ids[]" value="{{ $course->id }}" @checked($course->instructor_user_id === $teacher->id)>
                    {{ $course->title }}
                </label>
            @endforeach
            <button class="rounded-xl bg-[var(--color-primary)] px-4 py-2 text-sm text-white">حفظ التعيين</button>
        </form>
    </section>

    <section class="rounded-2xl border bg-white p-5">
        <h2 class="mb-3 font-semibold">المقررات الحالية</h2>
        <ul class="divide-y text-sm">
            @forelse ($teacher->instructedCourses as $course)
                <li class="flex justify-between py-2">
                    <span>{{ $course->title }}</span>
                    <span class="text-slate-500">{{ $course->enrollments_count ?? 0 }} طالب · {{ $course->status }}</span>
                </li>
            @empty
                <li class="py-4 text-slate-500">لا مقررات معينة.</li>
            @endforelse
        </ul>
    </section>

    <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}" class="mt-6" onsubmit="return confirm('حذف المعلم؟');">
        @csrf
        @method('DELETE')
        <button class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-2 text-sm text-rose-800">حذف المعلم</button>
    </form>
@endsection
