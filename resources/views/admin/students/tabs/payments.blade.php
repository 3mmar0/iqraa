<h3 class="mb-3 text-sm font-semibold text-slate-900">المعاملات المالية</h3>
<div class="overflow-x-auto mb-8">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-right text-xs font-semibold text-slate-500">
            <tr>
                <th class="px-3 py-2">المبلغ</th>
                <th class="px-3 py-2">النوع</th>
                <th class="px-3 py-2">الحالة</th>
                <th class="px-3 py-2">التاريخ</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($tabData['transactions'] ?? [] as $tx)
                <tr>
                    <td class="px-3 py-2 font-medium">{{ number_format((float) $tx->amount, 2) }} {{ $tx->currency ?? 'SAR' }}</td>
                    <td class="px-3 py-2">{{ $tx->type }}</td>
                    <td class="px-3 py-2">{{ $tx->status }}</td>
                    <td class="px-3 py-2 text-slate-500">{{ $tx->created_at?->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-3 py-8 text-center text-slate-500">لا معاملات.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<h3 class="mb-3 text-sm font-semibold text-slate-900">الاشتراكات</h3>
<ul class="divide-y divide-slate-100 text-sm">
    @forelse ($tabData['subscriptions'] ?? [] as $sub)
        <li class="flex items-center justify-between py-3">
            <span>{{ $sub->plan_code }}</span>
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs">{{ $sub->status }}</span>
        </li>
    @empty
        <li class="py-8 text-center text-slate-500">لا اشتراكات.</li>
    @endforelse
</ul>
