@extends('layouts.admin')

@section('title', 'الإعدادات')
@section('heading', 'إعدادات المنصة')
@section('subheading', 'ضبط الخيارات العامة والتشغيل والأمان')

@section('content')
    @if (session('status'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
            {{ session('status') }}
        </div>
    @endif

    <x-admin.tab-nav :tabs="$tabs" class="mb-6" />

    <div class="rounded-2xl border border-[var(--color-line)] bg-white p-6">
        @include('admin.settings.tabs.'.$tab)
    </div>
@endsection
