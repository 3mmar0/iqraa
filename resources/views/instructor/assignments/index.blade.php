@extends('layouts.instructor')

@section('title', 'الواجبات')

@section('content')
    <h1 class="mb-6 text-2xl font-bold text-[var(--color-ink)]">الواجبات</h1>
    <x-empty-state message="لا واجبات بعد." />
@endsection