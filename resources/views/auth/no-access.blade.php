@extends('layouts.guest')

@section('title', 'لا توجد صلاحيات')

@section('content')
    <h1 class="text-2xl font-bold tracking-tight text-[var(--color-ink)]">لا يمكن فتح لوحة</h1>
    <p class="mt-3 text-sm leading-relaxed text-[var(--color-text-secondary)]">حسابك لا يحتوي على دور صالح حالياً. تواصل مع الدعم.</p>
    <div class="mt-8">
        <a href="{{ url('/') }}" class="guest-submit">
            العودة للرئيسية
        </a>
    </div>
@endsection
