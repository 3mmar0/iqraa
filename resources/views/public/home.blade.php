@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
    {{-- reading-room-hero marker kept for deploy verification --}}
    <section class="academy-hero academy-hero-home reading-room-hero">
        <div class="academy-hero-bg" aria-hidden="true">
            <img
                src="{{ asset('images/home/iqraa-hero.webp') }}"
                alt=""
                width="1920"
                height="1080"
                fetchpriority="high"
                decoding="async"
            >
        </div>
        <div class="academy-hero-overlay" aria-hidden="true"></div>
        <div class="academy-hero-glow" aria-hidden="true"></div>

        <div class="academy-hero-inner academy-rise">
            <p class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/8 px-4 py-1.5 text-sm text-white/85">
                <span aria-hidden="true">🇨🇦</span>
                <span>كندا — العام الجامعي 2026م – 2027م</span>
            </p>
            <x-brand-logo size="hero" class="mt-6" />
            <h1 class="academy-display mt-4 max-w-4xl text-2xl font-bold leading-snug text-white sm:text-3xl md:text-4xl md:leading-tight lg:text-[2.35rem]">
                جامعة اقرأ العالمية للأبحاث العلمية والدراسات القرآنية
            </h1>
            <p class="mt-5 max-w-2xl text-base leading-relaxed text-white/80 sm:text-lg">
                📢 تعلن عمادة القبول والتسجيل عن <strong class="font-bold text-[var(--color-primary)]">فتح باب القبول والتسجيل</strong>
                للعام الجامعي 2026م – 2027م، للراغبين في الالتحاق ببرامج الدبلوم وفق التخصصات والضوابط المعتمدة.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="#programs" class="academy-btn-primary">برامج الدبلوم</a>
                <a href="#registration" class="academy-btn-secondary !border-white/35 !text-white hover:!bg-white/10">سجّل الآن</a>
            </div>
            <p class="mt-6 text-sm text-white/60">📖 اقرأ.. علمٌ يُبنى، وأثرٌ يُمتد.</p>
        </div>
    </section>

    {{-- Diploma programs --}}
    <section class="academy-section border-b border-[var(--color-line)] bg-[var(--color-sand)]" id="programs">
        <div class="mx-auto max-w-[90rem] px-4 sm:px-6 lg:px-8">
            <div class="mb-10 max-w-2xl">
                <h2 class="academy-display text-2xl font-bold text-[var(--color-text)] sm:text-3xl md:text-4xl">📚 برامج الدبلوم المتاحة</h2>
                <p class="mt-3 text-[var(--color-text-secondary)]">برنامجان أكاديميان — اختر التخصص المناسب لك وابدأ التسجيل عبر البوت.</p>
            </div>
            <div class="grid gap-6 lg:grid-cols-2">
                <article class="academy-card group overflow-hidden">
                    <div class="relative aspect-[16/10] overflow-hidden">
                        <img src="{{ asset('images/home/islamic-studies-cover.webp') }}" alt="" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]" width="640" height="400" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-[var(--color-ink)]/80 via-transparent to-transparent"></div>
                    </div>
                    <div class="p-6 sm:p-8">
                        <h3 class="academy-display text-xl font-bold text-[var(--color-text)] sm:text-2xl">دبلوم الدراسات الإسلامية</h3>
                        <p class="mt-3 text-sm leading-relaxed text-[var(--color-text-secondary)]">برنامج أكاديمي يقدّم أساساً رصيناً في العلوم الإسلامية، بخطة دراسية معتمدة ونخبة من المتخصصين.</p>
                        <a href="https://t.me/IqraProgramsBot?start=islamic" target="_blank" rel="noopener noreferrer" class="academy-btn-primary mt-6">📌 التسجيل عبر تيليجرام</a>
                    </div>
                </article>
                <article class="academy-card group overflow-hidden">
                    <div class="relative aspect-[16/10] overflow-hidden">
                        <img src="{{ asset('images/home/tajweed-cover.webp') }}" alt="" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]" width="640" height="400" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-[var(--color-ink)]/80 via-transparent to-transparent"></div>
                    </div>
                    <div class="p-6 sm:p-8">
                        <h3 class="academy-display text-xl font-bold text-[var(--color-text)] sm:text-2xl">دبلوم تجويد القرآن الكريم</h3>
                        <p class="mt-3 text-sm leading-relaxed text-[var(--color-text-secondary)]">برنامج متخصص في أحكام التجويد وتلاوة القرآن الكريم، عن بُعد عبر المنصات التعليمية الإلكترونية.</p>
                        <a href="https://t.me/IqraProgramsBot?start=tajweed" target="_blank" rel="noopener noreferrer" class="academy-btn-primary mt-6">📌 التسجيل عبر تيليجرام</a>
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- Admission requirements + study info --}}
    <section class="academy-section bg-[var(--color-surface)]">
        <div class="mx-auto max-w-[90rem] px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-2">
                <div class="rounded-xl border border-[var(--color-line)] bg-[var(--color-sand)]/60 p-6 sm:p-8">
                    <h2 class="academy-display text-xl font-bold text-[var(--color-text)] sm:text-2xl">📋 شروط القبول والتسجيل</h2>
                    <ol class="mt-5 space-y-3 text-sm leading-relaxed text-[var(--color-text-secondary)]">
                        <li class="flex gap-3"><span class="font-bold text-[var(--color-primary)]">1.</span> أن يكون حاصلًا على شهادة الثانوية العامة أو ما يعادلها.</li>
                        <li class="flex gap-3"><span class="font-bold text-[var(--color-primary)]">2.</span> أن يكون المتقدم حسن السيرة والسلوك.</li>
                        <li class="flex gap-3"><span class="font-bold text-[var(--color-primary)]">3.</span> الالتزام بالضوابط واللوائح الأكاديمية والإدارية المعتمدة لدى الجامعة.</li>
                        <li class="flex gap-3"><span class="font-bold text-[var(--color-primary)]">4.</span> استيفاء جميع المتطلبات والإجراءات المطلوبة للتسجيل والقبول.</li>
                    </ol>
                </div>
                <div class="space-y-6">
                    <div class="rounded-xl border border-[var(--color-line)] bg-[var(--color-secondary-light)]/50 p-6 sm:p-8">
                        <h2 class="academy-display text-xl font-bold text-[var(--color-secondary-hover)]">⏳ مدة الدراسة</h2>
                        <p class="mt-3 text-sm leading-relaxed text-[var(--color-text-secondary)]">
                            تبلغ مدة الدراسة في برامج الدبلوم <strong class="text-[var(--color-text)]">ثلاثة أشهر</strong>، وفق الخطة الدراسية المعتمدة لكل برنامج.
                        </p>
                        <p class="mt-3 text-sm text-[var(--color-text-secondary)]">📄 يُسلَّم الطالب عند القبول إشعار قبول رسمي، بالإضافة إلى الخطة الدراسية المعتمدة للبرنامج.</p>
                    </div>
                    <div class="rounded-xl border border-[var(--color-line)] bg-[var(--color-surface)] p-6 sm:p-8">
                        <h2 class="academy-display text-xl font-bold text-[var(--color-text)]">💻 نمط الدراسة</h2>
                        <p class="mt-3 text-sm leading-relaxed text-[var(--color-text-secondary)]">
                            تُقدَّم الدراسة <strong class="text-[var(--color-text)]">عن بُعد (إلكترونيًا)</strong>، بما يتيح للطلبة من مختلف البلدان الالتحاق بالبرامج والاستفادة من المحاضرات والأنشطة التعليمية دون الحاجة إلى الحضور المباشر.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Benefits --}}
    <section class="academy-section border-y border-[var(--color-line)] bg-[var(--color-sand)]">
        <div class="mx-auto max-w-[90rem] px-4 sm:px-6 lg:px-8">
            <h2 class="academy-display text-2xl font-bold text-[var(--color-text)] sm:text-3xl">مميزات الالتحاق بالبرنامج</h2>
            <ul class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    'الدراسة مجانية دون رسوم دراسية.',
                    'الدراسة عن بُعد عبر الوسائل والمنصات التعليمية الإلكترونية.',
                    'نخبة من الدكاترة والأساتذة المتخصصين في مجالات العلوم الإسلامية والقرآنية.',
                    'خطة دراسية أكاديمية تتناسب مع طبيعة كل برنامج.',
                    'إشعار قبول رسمي للطلبة المقبولين يتضمن بياناتك مع الرقم الأكاديمي.',
                    'شهادة تخرج تُمنح بعد استيفاء متطلبات البرنامج وفق اللوائح المعتمدة.',
                ] as $benefit)
                    <li class="flex gap-3 rounded-xl border border-[var(--color-line)] bg-[var(--color-surface)] p-5">
                        <span class="text-lg" aria-hidden="true">🌿</span>
                        <span class="text-sm leading-relaxed text-[var(--color-text-secondary)]">{{ $benefit }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- Registration --}}
    <section class="academy-section bg-[var(--color-surface)]" id="registration">
        <div class="mx-auto max-w-[90rem] px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <h2 class="academy-display text-2xl font-bold text-[var(--color-text)] sm:text-3xl">📝 آلية التسجيل</h2>
                <p class="mt-4 text-[var(--color-text-secondary)]">
                    على الراغبين في الالتحاق بأحد برامج الدبلوم الاطلاع على شروط وضوابط القبول والتسجيل،
                    ثم تعبئة نموذج التسجيل الإلكتروني من خلال البوت وفق التعليمات المعلنة.
                </p>
            </div>
            <div class="mt-10 grid gap-6 sm:grid-cols-2">
                <a href="https://t.me/IqraProgramsBot?start=islamic" target="_blank" rel="noopener noreferrer"
                   class="group flex flex-col rounded-xl border border-[var(--color-line)] bg-[var(--color-sand)]/70 p-6 transition hover:border-[var(--color-primary)] hover:shadow-lg sm:p-8">
                    <span class="text-2xl" aria-hidden="true">📌</span>
                    <h3 class="academy-display mt-4 text-lg font-bold text-[var(--color-text)] group-hover:text-[var(--color-secondary-hover)]">بوت التسجيل — الدراسات الإسلامية</h3>
                    <p class="mt-2 text-sm text-[var(--color-text-secondary)]">t.me/IqraProgramsBot?start=islamic</p>
                </a>
                <a href="https://t.me/IqraProgramsBot?start=tajweed" target="_blank" rel="noopener noreferrer"
                   class="group flex flex-col rounded-xl border border-[var(--color-line)] bg-[var(--color-sand)]/70 p-6 transition hover:border-[var(--color-primary)] hover:shadow-lg sm:p-8">
                    <span class="text-2xl" aria-hidden="true">📌</span>
                    <h3 class="academy-display mt-4 text-lg font-bold text-[var(--color-text)] group-hover:text-[var(--color-secondary-hover)]">بوت التسجيل — تجويد القرآن الكريم</h3>
                    <p class="mt-2 text-sm text-[var(--color-text-secondary)]">t.me/IqraProgramsBot?start=tajweed</p>
                </a>
            </div>
        </div>
    </section>

    @if ($courses->isNotEmpty())
        <section class="academy-section border-t border-[var(--color-line)] bg-[var(--color-sand)]" id="courses">
            <div class="mx-auto max-w-[90rem] px-4 sm:px-6 lg:px-8">
                <div class="mb-10 flex flex-wrap items-end justify-between gap-4">
                    <div class="max-w-xl">
                        <h2 class="academy-display text-2xl font-bold text-[var(--color-text)] sm:text-3xl">المقررات على المنصة</h2>
                        <p class="mt-3 text-[var(--color-text-secondary)]">{{ $courseCount }} مقرر متاح للطلاب المسجّلين.</p>
                    </div>
                    <a href="{{ route('public.courses.index') }}" class="text-sm font-bold text-[var(--color-primary)] hover:text-[var(--color-secondary-hover)]">عرض الكل ←</a>
                </div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($courses->take(3) as $index => $course)
                        <x-course-card :course="$course" :index="$index" :eager="$index < 3" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Closing CTA --}}
    <section class="academy-hero">
        <div class="relative mx-auto flex max-w-[90rem] flex-col items-center gap-8 px-4 py-16 text-center sm:px-6 sm:py-20 lg:px-8">
            <div>
                <h2 class="academy-display text-3xl font-bold text-white sm:text-4xl">🌍 جامعة اقرأ العالمية</h2>
                <p class="mx-auto mt-4 max-w-2xl text-base leading-relaxed text-white/75">
                    نحو تعليمٍ قرآنيٍّ وعلميٍّ رصين، وبناءِ جيلٍ يحمل العلم ويخدم القرآن.
                </p>
                <p class="mt-4 text-lg font-bold text-[var(--color-primary)]">📖 اقرأ.. علمٌ يُبنى، وأثرٌ يُمتد.</p>
            </div>
            <div class="flex flex-wrap justify-center gap-3">
                <a href="#registration" class="academy-btn-primary">ابدأ التسجيل</a>
                <a href="{{ route('public.contact') }}" class="academy-btn-secondary !text-white !border-white/30 hover:!bg-white/10">تواصل معنا</a>
            </div>
        </div>
    </section>
@endsection
