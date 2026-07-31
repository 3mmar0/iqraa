@extends('layouts.app')

@section('title', 'الملف الشخصي')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold">الملف الشخصي</h1>
        <p class="text-sm text-slate-600">بياناتك الأساسية في المنصة.</p>
    </div>

    <section class="rounded-xl border border-slate-200 bg-white p-4 space-y-2">
        <p><span class="font-semibold">الاسم:</span> {{ auth()->user()->name }}</p>
        <p><span class="font-semibold">البريد:</span> {{ auth()->user()->email }}</p>
        @if (auth()->user()->university)
            <p><span class="font-semibold">الجامعة:</span> {{ auth()->user()->university }}</p>
        @endif
    </section>
@endsection