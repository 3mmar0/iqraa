<div class="space-y-5">
    @if (Route::has('admin.courses.assign-semester'))
        <form method="POST" action="{{ route('admin.courses.assign-semester', $course) }}" class="rounded-2xl border border-slate-200 bg-gradient-to-l from-slate-50/80 to-white p-5"
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
            <p class="mb-3 text-sm font-semibold text-slate-900">تعيين السنة والفصل الدراسي</p>
            <div class="mb-4 grid gap-3 sm:grid-cols-2">
                <select name="academic_year_id" class="admin-input" x-model="yearId" @change="onYearChange()">
                    <option value="">— سنة —</option>
                    @foreach (\App\Models\AcademicYear::orderByDesc('starts_on')->get() as $year)
                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                    @endforeach
                </select>
                <select name="semester_id" class="admin-input" x-model="semesterId" :disabled="!yearId">
                    <option value="">— فصل —</option>
                    <template x-for="s in filtered" :key="s.id">
                        <option :value="s.id" x-text="s.name"></option>
                    </template>
                </select>
            </div>
            <button class="admin-btn admin-btn-primary">حفظ</button>
        </form>
    @endif

    <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" class="rounded-2xl border border-rose-200 bg-gradient-to-l from-rose-50 to-white p-5" onsubmit="return confirm('حذف المقرر؟');">
        @csrf
        @method('DELETE')
        <p class="mb-1 text-sm font-semibold text-rose-900">منطقة الخطر</p>
        <p class="mb-4 text-sm text-rose-800/80">حذف المقرر نهائياً (حذف ناعم).</p>
        <button class="admin-btn admin-btn-danger">حذف المقرر</button>
    </form>
</div>
