@extends('layouts.app')

@section('title', 'الأسئلة الشائعة')

@section('content')
    <x-public-page-hero
        title="الأسئلة الشائعة"
        lead="إجابات مباشرة لأكثر ما يُسأل عنه قبل وبعد التسجيل."
    />

    <section class="academy-section bg-[var(--color-surface)]" x-data="{ open: 0 }">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="divide-y divide-[var(--color-line)] overflow-hidden rounded-xl border border-[var(--color-line)] bg-[var(--color-sand)]/40">
                @foreach ($articles as $i => $article)
                    <div class="bg-[var(--color-surface)] px-4 sm:px-5">
                        <button type="button" class="flex w-full items-center justify-between gap-4 py-5 text-right focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-primary)]" @click="open = open === {{ $i }} ? -1 : {{ $i }}" :aria-expanded="(open === {{ $i }}).toString()">
                            <span class="text-lg font-medium text-[var(--color-text)]">{{ $article->title }}</span>
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[var(--color-primary-light)] text-[var(--color-secondary-hover)]" aria-hidden="true" x-text="open === {{ $i }} ? '−' : '+'"></span>
                        </button>
                        <div x-show="open === {{ $i }}" class="pb-5 text-sm leading-relaxed text-[var(--color-text-secondary)]" style="display: none;">
                            {{ $article->body }}
                        </div>
                    </div>
                @endforeach
            </div>
            <p class="mt-10 text-sm text-[var(--color-text-secondary)]">لم تجد إجابتك؟ <a href="{{ route('public.contact') }}" class="font-bold text-[var(--color-primary)] hover:underline">راسلنا</a>.</p>
        </div>
    </section>
@endsection
