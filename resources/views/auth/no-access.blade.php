@extends('layouts.guest')

@section('title', 'لا توجد صلاحيات')

@section('content')
    <header class="guest-head">
        <h1 class="guest-title">لا يمكن فتح لوحة</h1>
        <p class="guest-lead">حسابك لا يحتوي على دور صالح حالياً. تواصل مع الدعم.</p>
    </header>
    <a href="{{ url('/') }}" class="guest-submit">العودة للرئيسية</a>
@endsection
