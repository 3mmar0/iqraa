<div class="space-y-4">
    @if (Route::has('admin.courses.assign-semester'))
        <form method="POST" action="{{ route('admin.courses.assign-semester', $course) }}" class="rounded-xl border p-4">
            @csrf
            <p class="mb-3 text-sm font-medium">تعيين السنة والفصل الدراسي</p>
            <div class="mb-3 grid gap-3 sm:grid-cols-2">
                <select name="academic_year_id" class="rounded-lg border px-3 py-2 text-sm">
                    <option value="">— سنة —</option>
                    @foreach (\App\Models\AcademicYear::orderByDesc('starts_on')->get() as $year)
                        <option value="{{ $year->id }}" @selected($course->academic_year_id === $year->id)>{{ $year->name }}</option>
                    @endforeach
                </select>
                <select name="semester_id" class="rounded-lg border px-3 py-2 text-sm">
                    <option value="">— فصل —</option>
                    @foreach (\App\Models\Semester::orderByDesc('starts_on')->get() as $semester)
                        <option value="{{ $semester->id }}" @selected($course->semester_id === $semester->id)>{{ $semester->name }}</option>
                    @endforeach
                </select>
            </div>
            <button class="rounded-lg bg-teal-700 px-3 py-1.5 text-xs text-white">حفظ</button>
        </form>
    @endif

    <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" class="rounded-xl border border-rose-200 bg-rose-50 p-4" onsubmit="return confirm('حذف المقرر؟');">
        @csrf
        @method('DELETE')
        <p class="mb-3 text-sm text-rose-800">حذف المقرر نهائياً (حذف ناعم).</p>
        <button class="rounded-xl bg-rose-700 px-4 py-2 text-sm text-white">حذف المقرر</button>
    </form>
</div>
