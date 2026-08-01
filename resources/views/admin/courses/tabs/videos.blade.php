@php
    $videos = $course->lessons->flatMap(fn ($l) => $l->mediaAssets->where('type', 'video'));
@endphp
<p class="mb-3 text-sm text-slate-500">فيديوهات الدروس في هذا المقرر.</p>
<ul class="divide-y divide-slate-100 text-sm">
    @forelse ($videos as $asset)
        <li class="flex items-center justify-between py-2">
            <span>{{ $asset->original_name ?? basename($asset->path) }}</span>
            <span class="text-xs text-slate-500">{{ number_format(($asset->size ?? 0) / 1048576, 1) }} MB</span>
        </li>
    @empty
        <li class="py-6 text-slate-500">لا فيديوهات بعد.</li>
    @endforelse
</ul>
