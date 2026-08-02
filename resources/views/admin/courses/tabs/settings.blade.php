<div class="space-y-4">
        @if (Route::has('admin.courses.assign-semester'))
        <form method="POST" action="{{ route('admin.courses.assign-semester', $course) }}" class="rounded-xl border p-4"
              x-data="{
                  yearId: '{{ $course->academic_year_id }}',
                  semesterId: '{{ $course->semester_id }}',
                  semesters: {{ \App\Models\Semester::query()->get(['id','name','academic_year_id'])->toJson() }},
                  get filtered() {
                      if (!this.yearId) return [];
                      return this.semesters.filter(s => String(s.academic_year_id) === String(this.yearId));
                  },
                  onYearChange() {
                      if (!this.filtered.some(s => String(s.id) === String(this.semesterId))) this.semesterId = '';
                  }
              }">
            @csrf
            <p class="mb-3 text-sm font-medium">تعيين السنة والفصل الدراسي</p>
            <div class="mb-3 grid gap-3 sm:grid-cols-2">
                <select name="academic_year_id" class="rounded-lg border px-3 py-2 text-sm" x-model="yearId" @change="onYearChange()">
                    <option value="">— سنة —</option>
                    @foreach (\App\Models\AcademicYear::orderByDesc('starts_on')->get() as $year)
                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                    @endforeach
                </select>
                <select name="semester_id" class="rounded-lg border px-3 py-2 text-sm" x-model="semesterId" :disabled="!yearId">
                    <option value="">— فصل —</option>
                    <template x-for="s in filtered" :key="s.id">
                        <option :value="s.id" x-text="s.name"></option>
                    </template>
                </select>
            </div>
            <button class="rounded-lg bg-[var(--color-primary)] px-3 py-1.5 text-xs text-white">حفظ</button>
        </form>
    @endif

    <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" class="rounded-xl border border-rose-200 bg-rose-50 p-4" onsubmit="return confirm('حذف المقرر؟');">
        @csrf
        @method('DELETE')
        <p class="mb-3 text-sm text-rose-800">حذف المقرر نهائياً (حذف ناعم).</p>
        <button class="rounded-xl bg-rose-700 px-4 py-2 text-sm text-white">حذف المقرر</button>
    </form>
</div>
