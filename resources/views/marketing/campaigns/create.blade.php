@extends('layouts.app')
@section('title', 'حملة جديدة')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">حملة جديدة</h1>
    <form method="POST" action="{{ route('marketing.campaigns.store') }}" class="max-w-lg space-y-3 rounded-xl border border-slate-200 bg-white p-4">
        @csrf
        <input type="text" name="name" required placeholder="اسم الحملة" class="w-full rounded border border-slate-300 px-3 py-2">
        <input type="datetime-local" name="starts_at" class="w-full rounded border border-slate-300 px-3 py-2">
        <input type="datetime-local" name="ends_at" class="w-full rounded border border-slate-300 px-3 py-2">
        <button type="submit" class="rounded bg-teal-700 px-4 py-2 text-white">حفظ</button>
    </form>
@endsection