@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
    <section class="mx-auto max-w-2xl text-center">
        <h1 class="mb-3 text-3xl font-bold text-teal-900">مرحباً بك في منصة التعلم</h1>
        <p class="mb-8 text-slate-600">اكتشف الدورات وابدأ رحلتك التعليمية معنا.</p>
        @guest
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('login') }}" class="rounded-lg border border-teal-700 px-5 py-2.5 text-teal-800 hover:bg-teal-50">تسجيل الدخول</a>
                <a href="{{ route('register') }}" class="rounded-lg bg-teal-700 px-5 py-2.5 text-white hover:bg-teal-800">إنشاء حساب</a>
            </div>
        @else
            <a href="{{ route('dashboard.redirect') }}" class="inline-block rounded-lg bg-teal-700 px-5 py-2.5 text-white hover:bg-teal-800">الذهاب إلى لوحتي</a>
        @endguest
    </section>
@endsection