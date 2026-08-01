@extends('layouts.student')

@section('title', 'طلبات المقررات')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">طلبات الالتحاق</h1>

    <section class="mb-8 rounded-xl border border-slate-200 bg-white p-4">
        <h2 class="mb-3 font-semibold">طلب مقرر جديد</h2>
        <form method="POST" action="{{ route('student.course-requests.store') }}" class="space-y-3">
            @csrf
            <select name="course_id" required class="w-full rounded-lg border border-slate-300 px-3 py-2">
                <option value="">اختر مقرراً</option>
                @foreach ($catalog as $course)
                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                @endforeach
            </select>
            <textarea name="message" rows="3" placeholder="ملاحظة اختيارية" class="w-full rounded-lg border border-slate-300 px-3 py-2"></textarea>
            <button class="rounded-lg bg-teal-700 px-4 py-2 text-white">إرسال الطلب</button>
        </form>
    </section>

    <ul class="space-y-2">
        @forelse ($requests as $item)
            <li class="rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm">
                {{ $item->course->title }} —
                <span class="font-medium">
                    @if ($item->status === 'pending') معلّق
                    @elseif ($item->status === 'approved') موافق عليه
                    @else مرفوض
                    @endif
                </span>
            </li>
        @empty
            <li class="text-slate-500">لا توجد طلبات.</li>
        @endforelse
    </ul>
@endsection
