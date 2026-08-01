@extends('layouts.student')

@section('title', 'الإنجازات')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold">الإنجازات</h1>
        <p class="text-sm text-slate-600">شارات وتقديرات تقدّمك.</p>
    </div>

    <section class="rounded-xl border border-slate-200 bg-white p-4">
        <p class="text-sm text-slate-500">لم تحصل على إنجازات بعد.</p>
    </section>
@endsection