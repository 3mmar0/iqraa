@extends('layouts.app')

@section('title', 'الأسئلة الشائعة')

@section('content')
    <section class="border-b border-[var(--color-line)] bg-[var(--color-sand)]">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-20">
            <h1 class="site-brand text-4xl font-bold tracking-tight text-[var(--color-ink)] sm:text-5xl md:text-6xl">الأسئلة الشائعة</h1>
            <p class="mt-4 max-w-2xl text-base text-[var(--color-text-secondary)] sm:text-lg">إجابات مباشرة لأكثر ما يُسأل عنه قبل وبعد التسجيل.</p>
        </div>
    </section>

    <section class="bg-white" x-data="{ open: 0 }">
        <div class="mx-auto max-w-3xl px-4 py-16 sm:px-6 sm:py-20">
            <div class="divide-y divide-[var(--color-line)] overflow-hidden rounded-2xl border border-[var(--color-line)] bg-[var(--color-sand)]/40">
                @foreach ($articles as $i => $article)
                    <div class="bg-white/90 px-4 sm:px-5">
                        <button type="button" class="flex w-full items-center justify-between gap-4 py-5 text-right focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-primary)]" @click="open = open === {{ $i }} ? -1 : {{ $i }}" :aria-expanded="(open === {{ $i }}).toString()">
                            <span class="text-lg font-medium text-[var(--color-ink)]">{{ $article->title }}</span>
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[var(--color-primary-light)] text-[var(--color-primary)]" aria-hidden="true" x-text="open === {{ $i }} ? '−' : '+'"></span>
                        </button>
                        <div x-show="open === {{ $i }}" class="pb-5 text-sm leading-relaxed text-[var(--color-text-secondary)]" style="display: none;">
                            {{ $article->body }}
                        </div>
                    </div>
                @endforeach
            </div>
            <p class="mt-10 text-sm text-[var(--color-text-secondary)]">لم تجد إجابتك؟ <a href="{{ route('public.contact') }}" class="font-semibold text-[var(--color-secondary)] hover:underline">راسلنا</a>.</p>
        </div>
    </section>
@endsection
