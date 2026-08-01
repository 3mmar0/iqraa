@php
    $files = $course->lessons->flatMap(fn ($l) => $l->mediaAssets->where('type', '!=', 'video'));
@endphp
<p class="mb-3 text-sm text-slate-500">ملفات مرفقة بدروس المقرر (PDF ومستندات).</p>
<ul class="divide-y divide-slate-100 text-sm">
    @forelse ($files as $asset)
        <li class="flex items-center justify-between py-2">
            <span>{{ $asset->original_name ?? basename($asset->path) }}</span>
            <span class="text-xs text-slate-500">{{ $asset->type }}</span>
        </li>
    @empty
        <li class="py-6 text-slate-500">لا ملفات مرفقة.</li>
    @endforelse
</ul>
