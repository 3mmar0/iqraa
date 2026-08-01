@php
    $isEdit = isset($student);
    $action = $isEdit ? route('admin.students.update', $student) : route('admin.students.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<form method="POST" action="{{ $action }}" class="mx-auto max-w-3xl space-y-6">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5 sm:p-6">
        <h2 class="mb-4 text-base font-semibold text-slate-900">البيانات الأساسية</h2>
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-medium text-slate-700" for="name">الاسم</label>
                <input id="name" name="name" value="{{ old('name', $student->name ?? '') }}" required
                       class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="email">البريد</label>
                <input id="email" type="email" name="email" value="{{ old('email', $student->email ?? '') }}" required
                       class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="phone">الهاتف</label>
                <input id="phone" name="phone" value="{{ old('phone', $student->phone ?? '') }}"
                       class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="university">الجامعة</label>
                <input id="university" name="university" value="{{ old('university', $student->university ?? '') }}"
                       class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="gender">الجنس</label>
                <select id="gender" name="gender" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                    <option value="">—</option>
                    <option value="male" @selected(old('gender', $student->gender ?? '') === 'male')>ذكر</option>
                    <option value="female" @selected(old('gender', $student->gender ?? '') === 'female')>أنثى</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="status">الحالة</label>
                <select id="status" name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                    <option value="active" @selected(old('status', $student->status ?? 'active') === 'active')>نشط</option>
                    <option value="invited" @selected(old('status', $student->status ?? '') === 'invited')>مدعو</option>
                    <option value="disabled" @selected(old('status', $student->status ?? '') === 'disabled')>معطّل</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="academic_year_id">السنة الدراسية</label>
                <select id="academic_year_id" name="academic_year_id" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                    <option value="">—</option>
                    @foreach ($academicYears as $year)
                        <option value="{{ $year->id }}" @selected((string) old('academic_year_id', $student->academic_year_id ?? '') === (string) $year->id)>{{ $year->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="semester_id">الفصل</label>
                <select id="semester_id" name="semester_id" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                    <option value="">—</option>
                    @foreach ($semesters as $semester)
                        <option value="{{ $semester->id }}" @selected((string) old('semester_id', $student->semester_id ?? '') === (string) $semester->id)>{{ $semester->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="group_id">المجموعة</label>
                <select id="group_id" name="group_id" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                    <option value="">—</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}" @selected((string) old('group_id', $student->group_id ?? '') === (string) $group->id)>{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-medium text-slate-700" for="password">كلمة المرور</label>
                <input id="password" type="password" name="password"
                       class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                @if ($isEdit)
                    <input type="password" name="password_confirmation" placeholder="تأكيد كلمة المرور" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                    <p class="mt-1 text-xs text-slate-500">اتركها فارغة إذا لم ترد التغيير.</p>
                @else
                    <p class="mt-1 text-xs text-slate-500">مطلوبة إذا كانت الحالة «نشط».</p>
                @endif
            </div>
        </div>
    </section>

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="rounded-xl bg-teal-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-teal-800">حفظ</button>
        <a href="{{ $isEdit ? route('admin.students.show', $student) : route('admin.students.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-white">إلغاء</a>
    </div>
</form>
