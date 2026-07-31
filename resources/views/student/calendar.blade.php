@extends('layouts.app')

@section('title', 'التقويم')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold">التقويم</h1>
        <p class="text-sm text-slate-600">المحاضرات والمواعيد القادمة.</p>
    </div>

    <section class="rounded-xl border border-slate-200 bg-white p-4">
        <p class="text-sm text-slate-500">لا توجد أحداث مجدولة حالياً.</p>
    </section>
@endsection