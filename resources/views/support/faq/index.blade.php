@extends('layouts.app')
@section('title', 'الأسئلة الشائعة')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">الأسئلة الشائعة</h1>
    @if (\Illuminate\Support\Facades\Route::has('support.faq.store'))
        <form method="POST" action="{{ route('support.faq.store') }}" class="mb-8 max-w-xl space-y-3 rounded-xl border border-slate-200 bg-white p-4">
            @csrf
            <input type="text" name="title" required placeholder="العنوان" class="w-full rounded border border-slate-300 px-3 py-2">
            <textarea name="body" rows="3" required placeholder="المحتوى" class="w-full rounded border border-slate-300 px-3 py-2"></textarea>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="published" value="1" checked> منشور</label>
            <button type="submit" class="rounded bg-teal-700 px-4 py-2 text-white">إضافة</button>
        </form>
    @endif
    @if ($articles->isEmpty())
        <x-empty-state message="لا مقالات." />
    @else
        <ul class="space-y-3">
            @foreach ($articles as $article)
                <li class="rounded-xl border border-slate-200 bg-white p-4">
                    <h2 class="font-semibold">{{ $article->title }}</h2>
                    <p class="mt-1 text-sm text-slate-700">{{ $article->body }}</p>
                </li>
            @endforeach
        </ul>
    @endif
@endsection