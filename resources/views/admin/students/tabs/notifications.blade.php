<ul class="divide-y divide-slate-100 text-sm">
    @forelse ($tabData['notifications'] ?? [] as $notification)
        <li class="py-3">
            <p class="font-medium text-slate-900">{{ data_get(json_decode($notification->data ?? '{}', true), 'title', 'إشعار') }}</p>
            <p class="text-xs text-slate-500">{{ \Illuminate\Support\Carbon::parse($notification->created_at)->diffForHumans() }}</p>
        </li>
    @empty
        <li class="py-8 text-center text-slate-500">لا إشعارات.</li>
    @endforelse
</ul>
