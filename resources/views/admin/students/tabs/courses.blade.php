<div class="mb-4 flex flex-wrap items-end justify-between gap-3">
    <h3 class="text-sm font-semibold text-slate-900">المقررات المسجّلة</h3>
    <form method="POST" action="{{ route('admin.students.assign-course', $student) }}" class="flex flex-wrap items-end gap-2">
        @csrf
        <select name="course_id" required class="rounded-xl border border-slate-200 px-3 py-2 text-sm">
            <option value="">اختر مقرراً...</option>
            @foreach ($tabData['courses'] ?? [] as $course)
                <option value="{{ $course->id }}">{{ $course->title }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-4 py-2 text-sm font-medium text-white hover:bg-[var(--color-primary-hover)]">إسناد مقرر</button>
    </form>
</div>

<div class="overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-right text-xs font-semibold text-slate-500">
            <tr>
                <th class="px-3 py-2">المقرر</th>
                <th class="px-3 py-2">الحالة</th>
                <th class="px-3 py-2">تاريخ الالتحاق</th>
                <th class="px-3 py-2">إجراء</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($tabData['enrollments'] ?? [] as $enrollment)
                <tr>
                    <td class="px-3 py-2 font-medium">{{ $enrollment->course?->title ?? '—' }}</td>
                    <td class="px-3 py-2">
                        @php
                            $enrollLabel = ['active' => 'نشط', 'revoked' => 'ملغى', 'completed' => 'مكتمل'][$enrollment->status] ?? $enrollment->status;
                        @endphp
                        {{ $enrollLabel }}
                    </td>
                    <td class="px-3 py-2 text-slate-500">{{ $enrollment->enrolled_at?->format('Y-m-d') ?? '—' }}</td>
                    <td class="px-3 py-2">
                        @if ($enrollment->status === 'active')
                            <form method="POST" action="{{ route('admin.students.remove-course', $student) }}" class="inline" onsubmit="return confirm('إزالة المقرر؟');">
                                @csrf
                                <input type="hidden" name="course_id" value="{{ $enrollment->course_id }}">
                                <button type="submit" class="text-xs text-rose-700 hover:underline">إزالة</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-3 py-8 text-center text-slate-500">لا مقررات مسجّلة.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
