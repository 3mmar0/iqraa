@props(['maxWidth' => 'max-w-3xl'])

<div {{ $attributes->merge(['class' => "admin-content-enter mx-auto {$maxWidth}"]) }}>
    <div class="admin-panel overflow-hidden">
        <div class="border-b border-[var(--color-line)] bg-gradient-to-l from-[var(--color-teal-50)]/80 via-white to-white px-5 py-4 sm:px-6">
            @isset($header)
                {{ $header }}
            @else
                <p class="text-sm font-semibold text-slate-800">تفاصيل السجل</p>
                <p class="mt-0.5 text-xs text-slate-500">أدخل البيانات ثم احفظ التغييرات</p>
            @endif
        </div>
        <div class="space-y-5 p-5 sm:p-6">
            {{ $slot }}
        </div>
    </div>
</div>
