@extends('layouts.app')

@section('title', 'الإشعارات')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold">الإشعارات</h1>
        <p class="text-sm text-slate-600">آخر التنبيهات المتعلقة بحسابك.</p>
    </div>

    <section class="rounded-xl border border-slate-200 bg-white p-4">
        <p class="text-sm text-slate-500">لا توجد إشعارات جديدة.</p>
    </section>
@endsection