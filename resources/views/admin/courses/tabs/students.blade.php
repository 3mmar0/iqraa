<div class="space-y-6">
    <section class="rounded-2xl border border-slate-200 bg-gradient-to-l from-[var(--color-teal-50)]/50 to-white p-5">
        <h3 class="mb-3 text-sm font-semibold text-slate-900">إلحاق طالب</h3>
        <form method="POST" action="{{ route('admin.courses.enroll-student', $course) }}" class="flex flex-wrap items-end gap-3">
            @csrf
            <div class="min-w-[16rem] flex-1">
                <label class="admin-label" for="enroll_user_id">الطالب</label>
                <select id="enroll_user_id" name="user_id" required class="admin-input">
                    <option value="">اختر طالباً...</option>
                    @foreach ($availableStudents ?? [] as $student)
                        <option value="{{ $student->id }}">{{ $student->name }} — {{ $student->email }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="admin-btn admin-btn-primary" @disabled(($availableStudents ?? collect())->isEmpty())>إلحاق</button>
        </form>
        @if (($availableStudents ?? collect())->isEmpty())
            <p class="mt-2 text-xs text-slate-500">لا يوجد طلاب نشطون غير ملتحقين متاحون حالياً.</p>
        @endif
    </section>

    <section>
        <h3 class="mb-4 font-semibold text-slate-900">الملتحقون ({{ $course->enrollments->count() }})</h3>
        <ul class="divide-y divide-slate-100 overflow-hidden rounded-2xl border border-slate-200 bg-white">
            @forelse ($course->enrollments as $enrollment)
                <li class="flex flex-wrap items-center justify-between gap-3 px-4 py-3.5 transition hover:bg-slate-50/80">
                    <div class="flex items-center gap-3">
                        <div class="admin-entity-thumb !h-10 !w-10 text-[0.7rem]">{{ mb_substr($enrollment->user?->name ?? '?', 0, 1) }}</div>
                        <div>
                            <p class="font-semibold text-slate-900">{{ $enrollment->user?->name ?? '—' }}</p>
                            <p class="text-xs text-slate-500">
                                {{ $enrollment->user?->email }}
                                · {{ $enrollment->status === 'active' ? 'نشط' : $enrollment->status }}
                                @if ($enrollment->enrolled_at)
                                    · منذ {{ $enrollment->enrolled_at->diffForHumans() }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        @if ($enrollment->user)
                            <a href="{{ route('admin.students.show', $enrollment->user) }}" class="admin-btn admin-btn-ghost admin-btn-sm">الملف</a>
                        @endif
                        @if ($enrollment->status === 'active' && $enrollment->user)
                            <form method="POST" action="{{ route('admin.courses.unenroll-student', $course) }}" onsubmit="return confirm('إزالة الطالب من المقرر؟');">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $enrollment->user_id }}">
                                <button class="admin-btn admin-btn-danger admin-btn-sm">إزالة</button>
                            </form>
                        @endif
                    </div>
                </li>
            @empty
                <li class="p-6">
                    <x-admin.empty-state title="لا ملتحقين بعد" description="ألحق طلاباً بهذا المقرر من النموذج أعلاه." />
                </li>
            @endforelse
        </ul>
    </section>
</div>
