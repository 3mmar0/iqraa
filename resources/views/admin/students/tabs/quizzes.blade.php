<div class="overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-right text-xs font-semibold text-slate-500">
            <tr>
                <th class="px-3 py-2">الاختبار</th>
                <th class="px-3 py-2">الدرجة</th>
                <th class="px-3 py-2">الحالة</th>
                <th class="px-3 py-2">التاريخ</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($tabData['attempts'] ?? [] as $attempt)
                <tr>
                    <td class="px-3 py-2 font-medium">{{ $attempt->quiz?->title ?? '—' }}</td>
                    <td class="px-3 py-2">{{ $attempt->score ?? '—' }}</td>
                    <td class="px-3 py-2">{{ $attempt->status }}</td>
                    <td class="px-3 py-2 text-slate-500">{{ $attempt->created_at?->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-3 py-8 text-center text-slate-500">لا محاولات اختبار.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
