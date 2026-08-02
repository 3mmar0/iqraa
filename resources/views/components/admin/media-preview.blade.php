@props([
    'asset',
    'url' => null,
    'compact' => false,
])

@php
    $url = $url ?? route('admin.lessons.media.show', [$asset->lesson_id, $asset]);
    $name = $asset->original_name ?? basename($asset->path);
    $type = $asset->type;
    $mime = $asset->mime ?? '';
    $isVideo = $type === 'video' || str_starts_with($mime, 'video/');
    $isImage = $type === 'image' || str_starts_with($mime, 'image/');
    $isPdf = $type === 'pdf' || $mime === 'application/pdf' || str_ends_with(strtolower($name), '.pdf');
@endphp

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl border border-slate-200 bg-slate-50']) }}>
    @if ($isVideo)
        <video
            class="{{ $compact ? 'max-h-48' : 'max-h-80' }} w-full bg-black"
            controls
            preload="metadata"
            src="{{ $url }}"
        ></video>
    @elseif ($isImage)
        <a href="{{ $url }}" target="_blank" rel="noopener" class="block">
            <img src="{{ $url }}" alt="{{ $name }}" class="{{ $compact ? 'max-h-48' : 'max-h-96' }} mx-auto w-full object-contain bg-white">
        </a>
    @elseif ($isPdf)
        <iframe src="{{ $url }}" title="{{ $name }}" class="{{ $compact ? 'h-56' : 'h-96' }} w-full border-0 bg-white"></iframe>
        <div class="border-t border-slate-200 px-3 py-2 text-center">
            <a href="{{ $url }}" target="_blank" rel="noopener" class="text-xs font-medium text-[var(--color-primary)] hover:underline">فتح PDF في تبويب جديد</a>
        </div>
    @else
        <div class="flex items-center justify-between gap-3 px-4 py-6">
            <div class="min-w-0">
                <p class="truncate text-sm font-medium text-slate-800">{{ $name }}</p>
                <p class="text-xs text-slate-500">{{ $type }} · معاينة غير متاحة لهذا النوع</p>
            </div>
            <a href="{{ $url }}" target="_blank" rel="noopener" class="shrink-0 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">فتح / تنزيل</a>
        </div>
    @endif
</div>
