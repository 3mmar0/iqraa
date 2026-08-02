{{-- لوحة تحكم سريعة للطالب --}}
<section class="rounded-2xl border border-[var(--color-line)] bg-white p-4">
    <h3 class="mb-3 text-sm font-semibold text-slate-900">إجراءات سريعة</h3>
    <div class="flex flex-col gap-2">
        @if ($student->status === 'active')
            <form method="POST" action="{{ route('admin.students.impersonate', $student) }}">
                @csrf
                <input type="hidden" name="context" value="student">
                <button type="submit" class="w-full rounded-xl border border-amber-200 bg-amber-50 px-3 py-2.5 text-sm font-medium text-amber-950 hover:bg-amber-100">دخول كـ هذا الطالب</button>
            </form>
            <form method="POST" action="{{ route('admin.students.suspend', $student) }}" onsubmit="return confirm('تعليق الحساب؟');">
                @csrf
                <button type="submit" class="w-full rounded-xl border border-rose-200 bg-rose-50 px-3 py-2.5 text-sm font-medium text-rose-800 hover:bg-rose-100">تعليق الحساب</button>
            </form>
        @else
            <form method="POST" action="{{ route('admin.students.activate', $student) }}">
                @csrf
                <button type="submit" class="w-full rounded-xl bg-emerald-700 px-3 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800">تفعيل الحساب</button>
            </form>
        @endif

        <a href="{{ route('admin.students.edit', $student) }}" class="block w-full rounded-xl border border-slate-200 px-3 py-2.5 text-center text-sm font-medium text-slate-700 hover:bg-slate-50">تعديل الملف الكامل</a>

        <form method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('حذف الطالب نهائياً؟ لا يمكن التراجع.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full rounded-xl border border-transparent px-3 py-2 text-sm font-medium text-rose-700 hover:bg-rose-50">حذف الطالب</button>
        </form>
    </div>
</section>

<section class="rounded-2xl border border-[var(--color-line)] bg-white p-4">
    <h3 class="mb-3 text-sm font-semibold text-slate-900">إسناد مقرر</h3>
    <form method="POST" action="{{ route('admin.students.assign-course', $student) }}" class="space-y-2">
        @csrf
        <select name="course_id" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
            <option value="">اختر مقرراً...</option>
            @forelse ($controls['courses'] as $course)
                <option value="{{ $course->id }}">{{ $course->title }}</option>
            @empty
                <option value="" disabled>لا مقررات متاحة للإسناد</option>
            @endforelse
        </select>
        <button type="submit" class="w-full rounded-xl bg-[var(--color-primary)] px-3 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)] disabled:cursor-not-allowed disabled:opacity-50" @disabled($controls['courses']->isEmpty())>إسناد</button>
    </form>
</section>

<section class="rounded-2xl border border-[var(--color-line)] bg-white p-4">
    <h3 class="mb-3 text-sm font-semibold text-slate-900">التصنيف والحالة</h3>
    <form method="POST" action="{{ route('admin.students.placement', $student) }}" class="space-y-3">
        @csrf
        <div>
            <label class="mb-1 block text-xs text-slate-500" for="ctrl_status">الحالة</label>
            <select id="ctrl_status" name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                <option value="active" @selected(old('status', $student->status) === 'active')>نشط</option>
                <option value="invited" @selected(old('status', $student->status) === 'invited')>مدعو</option>
                <option value="disabled" @selected(old('status', $student->status) === 'disabled')>معطّل</option>
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs text-slate-500" for="ctrl_university">الجامعة</label>
            <input id="ctrl_university" type="text" name="university" value="{{ old('university', $student->university) }}"
                   class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" placeholder="اختياري">
        </div>
        <div>
            <label class="mb-1 block text-xs text-slate-500" for="ctrl_year">السنة الدراسية</label>
            <select id="ctrl_year" name="academic_year_id" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                <option value="">—</option>
                @foreach ($controls['academicYears'] as $year)
                    <option value="{{ $year->id }}" @selected((string) old('academic_year_id', $student->academic_year_id) === (string) $year->id)>{{ $year->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs text-slate-500" for="ctrl_semester">الفصل</label>
            <select id="ctrl_semester" name="semester_id" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                <option value="">—</option>
                @foreach ($controls['semesters'] as $semester)
                    <option value="{{ $semester->id }}" @selected((string) old('semester_id', $student->semester_id) === (string) $semester->id)>{{ $semester->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs text-slate-500" for="ctrl_group">المجموعة</label>
            <select id="ctrl_group" name="group_id" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                <option value="">—</option>
                @foreach ($controls['groups'] as $group)
                    <option value="{{ $group->id }}" @selected((string) old('group_id', $student->group_id) === (string) $group->id)>{{ $group->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-medium text-slate-800 hover:bg-slate-100">حفظ التصنيف</button>
    </form>
</section>

<section class="rounded-2xl border border-[var(--color-line)] bg-white p-4">
    <h3 class="mb-3 text-sm font-semibold text-slate-900">كلمة المرور</h3>
    <form method="POST" action="{{ route('admin.students.reset-password', $student) }}" class="space-y-2">
        @csrf
        <input type="text" name="password" placeholder="اتركها فارغة لتوليد تلقائي"
               class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" autocomplete="new-password">
        <button type="submit" class="w-full rounded-xl bg-slate-900 px-3 py-2.5 text-sm font-medium text-white hover:bg-slate-800">إعادة التعيين</button>
    </form>
</section>

<section class="rounded-2xl border border-[var(--color-line)] bg-white p-4">
    <div class="mb-3 flex items-center justify-between gap-2">
        <h3 class="text-sm font-semibold text-slate-900">ملاحظة سريعة</h3>
        <a href="{{ route('admin.students.show', ['student' => $student, 'tab' => 'notes']) }}" class="text-xs text-[var(--color-primary)] hover:underline">الملء الكامل</a>
    </div>
    <form method="POST" action="{{ route('admin.students.notes', $student) }}" class="space-y-2">
        @csrf
        <textarea name="admin_notes" rows="3" placeholder="ملاحظة إدارية..."
                  class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">{{ old('admin_notes', $student->admin_notes) }}</textarea>
        <button type="submit" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">حفظ الملاحظة</button>
    </form>
</section>
