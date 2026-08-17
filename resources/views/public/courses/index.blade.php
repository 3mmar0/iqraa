@extends('layouts.app')
@section('title', 'كتالوج المقررات')

@section('content')
    <x-public-page-hero
        title="كل المقررات"
        lead="استعرض المقررات المنشورة، صفِّ حسب التصنيف أو ابحث بالعنوان، ثم اطلب الالتحاق بعد تسجيل الدخول."
    />

    <section class="academy-section bg-[var(--color-sand)]">
        <div class="mx-auto max-w-[90rem] px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[14rem_minmax(0,1fr)]">
                {{-- Category rail --}}
                <aside class="academy-catalog-rail">
                    <p class="mb-3 text-xs font-bold uppercase tracking-wide text-[var(--color-muted)]">التصنيفات</p>
                    <nav class="space-y-1" aria-label="تصفية حسب التصنيف">
                        <a href="{{ route('public.courses.index', array_filter(['q' => $searchQuery ?: null, 'sort' => $sort !== 'newest' ? $sort : null])) }}"
                           class="academy-catalog-link {{ ! $activeCategoryId ? 'is-active' : '' }}">
                            كل الفئات
                        </a>
                        @foreach ($categories as $category)
                            <a href="{{ route('public.courses.index', array_filter(['category_id' => $category->id, 'q' => $searchQuery ?: null, 'sort' => $sort !== 'newest' ? $sort : null])) }}"
                               class="academy-catalog-link {{ (int) $activeCategoryId === $category->id ? 'is-active' : '' }}">
                                {{ $category->name }}
                                <span class="text-xs text-[var(--color-muted)]">({{ $category->courses_count }})</span>
                            </a>
                        @endforeach
                    </nav>
                </aside>

                <div>
                    {{-- Toolbar --}}
                    <form method="GET" action="{{ route('public.courses.index') }}" class="mb-8 flex flex-wrap items-end gap-3 rounded-xl border border-[var(--color-line)] bg-[var(--color-surface)] p-4">
                        @if ($activeCategoryId)
                            <input type="hidden" name="category_id" value="{{ $activeCategoryId }}">
                        @endif
                        <div class="min-w-[12rem] flex-1">
                            <label for="q" class="mb-1 block text-xs font-semibold text-[var(--color-muted)]">بحث</label>
                            <input id="q" type="search" name="q" value="{{ $searchQuery }}" placeholder="ابحث في المقررات…"
                                   class="w-full rounded-lg border border-[var(--color-line)] bg-[var(--color-sand)] px-3 py-2 text-sm focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20">
                        </div>
                        <div>
                            <label for="sort" class="mb-1 block text-xs font-semibold text-[var(--color-muted)]">ترتيب</label>
                            <select id="sort" name="sort" class="rounded-lg border border-[var(--color-line)] bg-[var(--color-sand)] px-3 py-2 text-sm focus:border-[var(--color-primary)] focus:outline-none">
                                <option value="newest" @selected($sort === 'newest')>الأحدث</option>
                                <option value="title_asc" @selected($sort === 'title_asc')>من أ إلى ي</option>
                                <option value="title_desc" @selected($sort === 'title_desc')>من ي إلى أ</option>
                            </select>
                        </div>
                        <button type="submit" class="academy-btn-primary !py-2 !text-sm">تطبيق</button>
                        @if ($searchQuery || $activeCategoryId || $sort !== 'newest')
                            <a href="{{ route('public.courses.index') }}" class="rounded-lg px-3 py-2 text-sm text-[var(--color-muted)] hover:text-[var(--color-text)]">مسح</a>
                        @endif
                    </form>

                    <p class="mb-6 text-sm text-[var(--color-text-secondary)]">{{ $courses->count() }} {{ $courses->count() === 1 ? 'مقرر' : 'مقررات' }}</p>

                    @if ($courses->isEmpty())
                        <x-empty-state message="لا مقررات مطابقة للبحث حالياً." />
                    @else
                        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                            @foreach ($courses as $index => $course)
                                <x-course-card :course="$course" :index="$index" />
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
