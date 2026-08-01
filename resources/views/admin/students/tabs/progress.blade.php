<div class="overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-right text-xs font-semibold text-slate-500">
            <tr>
                <th class="px-3 py-2">الدرس</th>
                <th class="px-3 py-2">الحالة</th>
                <th class="px-3 py-2">اكتمل</th>
                <th class="px-3 py-2">آخر تحديث</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($tabData['progress'] ?? [] as $row)
                <tr>
                    <td class="px-3 py-2 font-medium">{{ $row->lesson?->title ?? '—' }}</td>
                    <td class="px-3 py-2">{{ $row->status }}</td>
                    <td class="px-3 py-2 text-slate-500">{{ $row->completed_at?->format('Y-m-d') ?? '—' }}</td>
                    <td class="px-3 py-2 text-slate-500">{{ $row->updated_at?->diffForHumans() }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-3 py-8 text-center text-slate-500">لا سجل تقدم.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
