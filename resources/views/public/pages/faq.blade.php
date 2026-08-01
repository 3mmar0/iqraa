@extends('layouts.app')

@section('title', 'الأسئلة الشائعة')

@section('content')
    <section class="border-b border-[var(--color-line)] bg-white">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
            <h1 class="site-brand text-4xl font-bold text-[var(--color-ink)] sm:text-5xl">الأسئلة الشائعة</h1>
            <p class="mt-4 max-w-2xl text-slate-600">إجابات مباشرة لأكثر ما يُسأل عنه قبل وبعد التسجيل.</p>
        </div>
    </section>

    <section class="mx-auto max-w-3xl px-4 py-16 sm:px-6" x-data="{ open: 0 }">
        <div class="divide-y divide-[var(--color-line)] border-y border-[var(--color-line)]">
            @foreach ($articles as $i => $article)
                <div>
                    <button type="button" class="flex w-full items-center justify-between gap-4 py-5 text-right" @click="open = open === {{ $i }} ? -1 : {{ $i }}">
                        <span class="text-lg font-medium text-slate-900">{{ $article->title }}</span>
                        <span class="text-teal-700" x-text="open === {{ $i }} ? '−' : '+'"></span>
                    </button>
                    <div x-show="open === {{ $i }}" class="pb-5 text-sm leading-relaxed text-slate-600" style="display: none;">
                        {{ $article->body }}
                    </div>
                </div>
            @endforeach
        </div>
        <p class="mt-10 text-sm text-slate-600">لم تجد إجابتك؟ <a href="{{ route('public.contact') }}" class="font-medium text-teal-800 hover:underline">راسلنا</a>.</p>
    </section>
@endsection
