@extends('layouts.instructor')

@section('title', 'الرسائل')

@section('content')
    <h1 class="mb-6 text-2xl font-bold text-[var(--color-ink)]">الرسائل</h1>
    <x-empty-state message="صندوق الرسائل قيد التجهيز." />
@endsection