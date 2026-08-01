<ul class="divide-y divide-slate-100 text-sm">
    @forelse ($tabData['logs'] ?? [] as $log)
        <li class="py-3">
            <p class="font-medium text-slate-900">{{ $log->action }}</p>
            <p class="text-xs text-slate-500">{{ $log->created_at?->format('Y-m-d H:i') ?? '—' }}</p>
        </li>
    @empty
        <li class="py-8 text-center text-slate-500">لا سجلات نشاط.</li>
    @endforelse
</ul>
