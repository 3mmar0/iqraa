@extends('layouts.instructor')

@section('title', 'التقارير')

@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">التقارير والتحليلات</h1>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-slate-200 bg-white p-4"><p class="text-sm text-slate-500">المقررات</p><p class="text-2xl font-semibold">{{ $stats['courses'] }}</p></div>
        <div class="rounded-xl border border-slate-200 bg-white p-4"><p class="text-sm text-slate-500">الدروس</p><p class="text-2xl font-semibold">{{ $stats['lessons'] }}</p></div>
        <div class="rounded-xl border border-slate-200 bg-white p-4"><p class="text-sm text-slate-500">الاختبارات</p><p class="text-2xl font-semibold">{{ $stats['quizzes'] }}</p></div>
        <div class="rounded-xl border border-slate-200 bg-white p-4"><p class="text-sm text-slate-500">الطلاب</p><p class="text-2xl font-semibold">{{ $stats['students'] }}</p></div>
    </div>
@endsection