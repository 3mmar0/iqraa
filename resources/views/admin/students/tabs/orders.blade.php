<div class="overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-right text-xs font-semibold text-slate-500">
            <tr>
                <th class="px-3 py-2">رقم الطلب</th>
                <th class="px-3 py-2">الإجمالي</th>
                <th class="px-3 py-2">الحالة</th>
                <th class="px-3 py-2">التاريخ</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($tabData['orders'] ?? [] as $order)
                <tr>
                    <td class="px-3 py-2 font-medium">{{ $order->number ?? '#'.$order->id }}</td>
                    <td class="px-3 py-2">{{ number_format((float) $order->total, 2) }} {{ $order->currency ?? 'SAR' }}</td>
                    <td class="px-3 py-2">{{ $order->status }}</td>
                    <td class="px-3 py-2 text-slate-500">{{ $order->created_at?->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-3 py-8 text-center text-slate-500">لا طلبات.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
