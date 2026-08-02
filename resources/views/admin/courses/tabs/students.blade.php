<div class="space-y-6">
    <section class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
        <h3 class="mb-3 text-sm font-semibold text-slate-900">إلحاق طالب</h3>
        <form method="POST" action="{{ route('admin.courses.enroll-student', $course) }}" class="flex flex-wrap items-end gap-3">
            @csrf
            <div class="min-w-[16rem] flex-1">
                <label class="mb-1 block text-xs text-slate-500" for="enroll_user_id">الطالب</label>
                <select id="enroll_user_id" name="user_id" required class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm">
                    <option value="">اختر طالباً...</option>
                    @foreach ($availableStudents ?? [] as $student)
                        <option value="{{ $student->id }}">{{ $student->name }} — {{ $student->email }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white" @disabled(($availableStudents ?? collect())->isEmpty())>إلحاق</button>
        </form>
        @if (($availableStudents ?? collect())->isEmpty())
            <p class="mt-2 text-xs text-slate-500">لا يوجد طلاب نشطون غير ملتحقين متاحون حالياً.</p>
        @endif
    </section>

    <section>
        <h3 class="mb-3 font-semibold">الملتحقون ({{ $course->enrollments->count() }})</h3>
        <ul class="divide-y divide-slate-100 text-sm">
            @forelse ($course->enrollments as $enrollment)
                <li class="flex flex-wrap items-center justify-between gap-3 py-3">
                    <div>
                        <p class="font-medium">{{ $enrollment->user?->name ?? '—' }}</p>
                        <p class="text-xs text-slate-500">
                            {{ $enrollment->user?->email }}
                            · {{ $enrollment->status === 'active' ? 'نشط' : $enrollment->status }}
                            @if ($enrollment->enrolled_at)
                                · منذ {{ $enrollment->enrolled_at->diffForHumans() }}
                            @endif
                        </p>
                    </div>
                    <div class="flex gap-2">
                        @if ($enrollment->user)
                            <a href="{{ route('admin.students.show', $enrollment->user) }}" class="rounded-lg border px-2.5 py-1.5 text-xs">الملف</a>
                        @endif
                        @if ($enrollment->status === 'active' && $enrollment->user)
                            <form method="POST" action="{{ route('admin.courses.unenroll-student', $course) }}" onsubmit="return confirm('إزالة الطالب من المقرر؟');">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $enrollment->user_id }}">
                                <button class="rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-xs text-rose-800">إزالة</button>
                            </form>
                        @endif
                    </div>
                </li>
            @empty
                <li class="py-8 text-center text-slate-500">لا ملتحقين بعد.</li>
            @endforelse
        </ul>
    </section>
</div>
