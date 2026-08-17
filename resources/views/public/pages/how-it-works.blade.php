@extends('layouts.app')

@section('title', 'كيف تعمل المنصة')

@section('content')
    <x-public-page-hero
        title="كيف تعمل المنصة"
        lead="من التسجيل عبر البوت حتى إتمام برنامج الدبلوم — مسار واضح للطلبة في جامعة اقرأ العالمية."
    />

    <section class="academy-section bg-[var(--color-surface)]">
        <div class="mx-auto max-w-[90rem] px-4 sm:px-6 lg:px-8">
            <ol class="grid gap-4 lg:grid-cols-2">
                @foreach ([
                    ['title' => 'اطّلع على الشروط', 'text' => 'راجع شروط وضوابط القبول والتسجيل لبرامج الدبلوم المعتمدة لدى الجامعة.'],
                    ['title' => 'سجّل عبر البوت', 'text' => 'اختر برنامجك (الدراسات الإسلامية أو تجويد القرآن) وعبّئ نموذج التسجيل الإلكتروني عبر تيليجرام.'],
                    ['title' => 'استلم إشعار القبول', 'text' => 'عند القبول يصلك إشعار رسمي يتضمن بياناتك والرقم الأكاديمي والخطة الدراسية.'],
                    ['title' => 'ادرس عن بُعد', 'text' => 'تابع المحاضرات والأنشطة التعليمية عبر المنصة الإلكترونية من أي مكان.'],
                    ['title' => 'استوفِ المتطلبات', 'text' => 'أكمل متطلبات البرنامج خلال مدة الدراسة (ثلاثة أشهر) وفق الخطة المعتمدة.'],
                    ['title' => 'احصل على الشهادة', 'text' => 'تُمنح شهادة التخرج بعد استيفاء متطلبات البرنامج وفق اللوائح المعتمدة.'],
                ] as $i => $step)
                    <li class="flex gap-4 rounded-xl border border-[var(--color-line)] bg-[var(--color-sand)]/70 p-6 transition hover:border-[var(--color-primary)]/40 hover:bg-[var(--color-surface)]">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[var(--color-primary-light)] text-sm font-bold text-[var(--color-secondary-hover)]">{{ $i + 1 }}</span>
                        <div>
                            <h2 class="text-lg font-bold text-[var(--color-text)]">{{ $step['title'] }}</h2>
                            <p class="mt-2 text-sm leading-relaxed text-[var(--color-text-secondary)]">{{ $step['text'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>

            <div class="mt-12 flex flex-wrap gap-3">
                <a href="{{ route('home') }}#registration" class="academy-btn-primary">ابدأ التسجيل</a>
                <a href="{{ route('public.contact') }}" class="academy-btn-secondary">تواصل معنا</a>
            </div>
        </div>
    </section>
@endsection
