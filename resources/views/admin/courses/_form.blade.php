@php
    $c = $course;
    $panel = $coursePanel ?? 'admin';
    $lockInstructor = $lockInstructor ?? ($panel === 'instructor');
    $selectedYear = (string) old('academic_year_id', $c?->academic_year_id ?? '');
    $selectedSemester = (string) old('semester_id', $c?->semester_id ?? '');
    $semestersPayload = $semesters->map(fn ($s) => [
        'id' => (string) $s->id,
        'name' => $s->name,
        'academic_year_id' => (string) ($s->academic_year_id ?? ''),
    ])->values();
@endphp

<div>
    <label class="admin-label" for="title">العنوان</label>
    <input id="title" name="title" value="{{ old('title', $c?->title) }}" required class="admin-input">
    @error('title')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>
<div>
    <label class="admin-label" for="description">الوصف</label>
    <textarea id="description" name="description" rows="4" class="admin-input">{{ old('description', $c?->description) }}</textarea>
</div>
<div
    class="grid gap-4 sm:grid-cols-2"
    x-data="{
        yearId: @js($selectedYear),
        semesterId: @js($selectedSemester),
        semesters: @js($semestersPayload),
        get filteredSemesters() {
            if (! this.yearId) return [];
            return this.semesters.filter(s => s.academic_year_id === this.yearId);
        },
        onYearChange() {
            const stillValid = this.filteredSemesters.some(s => s.id === this.semesterId);
            if (! stillValid) this.semesterId = '';
        }
    }"
>
    @if ($lockInstructor)
        <div>
            <label class="admin-label" for="instructor_display">المحاضر</label>
            <input id="instructor_display" type="text" value="{{ auth()->user()->name }}" disabled class="admin-input">
            <p class="mt-1 text-xs text-slate-500">سيُسجَّل المقرر باسمك تلقائياً.</p>
        </div>
    @else
        <div>
            <label class="admin-label" for="instructor_user_id">المحاضر</label>
            <select id="instructor_user_id" name="instructor_user_id" required class="admin-input">
                <option value="">اختر محاضراً</option>
                @foreach ($instructors as $instructor)
                    <option value="{{ $instructor->id }}" @selected((string) old('instructor_user_id', $c?->instructor_user_id) === (string) $instructor->id)>{{ $instructor->name }}</option>
                @endforeach
            </select>
        </div>
    @endif
    <div>
        <label class="admin-label" for="category_id">التصنيف</label>
        <select id="category_id" name="category_id" class="admin-input">
            <option value="">بدون تصنيف</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('category_id', $c?->category_id) === (string) $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="admin-label" for="academic_year_id">السنة الدراسية</label>
        <select
            id="academic_year_id"
            name="academic_year_id"
            class="admin-input"
            x-model="yearId"
            @change="onYearChange()"
        >
            <option value="">—</option>
            @foreach ($academicYears as $year)
                <option value="{{ $year->id }}">{{ $year->name }}</option>
            @endforeach
        </select>
        @error('academic_year_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="admin-label" for="semester_id">الفصل الدراسي</label>
        <select
            id="semester_id"
            name="semester_id"
            class="admin-input"
            x-model="semesterId"
            :disabled="! yearId || filteredSemesters.length === 0"
        >
            <option value="">—</option>
            <template x-for="semester in filteredSemesters" :key="semester.id">
                <option :value="semester.id" x-text="semester.name" :selected="semester.id === semesterId"></option>
            </template>
        </select>
        <p class="mt-1 text-xs text-slate-500" x-show="! yearId">اختر السنة الدراسية أولاً لعرض الفصول.</p>
        <p class="mt-1 text-xs text-amber-700" x-show="yearId && filteredSemesters.length === 0" x-cloak>لا توجد فصول مرتبطة بهذه السنة.</p>
        @error('semester_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="admin-label" for="price">السعر (ر.س)</label>
        <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $c?->price) }}" class="admin-input">
    </div>
    <div>
        <label class="admin-label" for="status">الحالة</label>
        <select id="status" name="status" class="admin-input">
            <option value="draft" @selected(old('status', $c?->status ?? 'draft') === 'draft')>مسودة</option>
            <option value="published" @selected(old('status', $c?->status) === 'published')>منشور</option>
            <option value="hidden" @selected(old('status', $c?->status) === 'hidden')>مخفي</option>
            <option value="archived" @selected(old('status', $c?->status) === 'archived')>مؤرشف</option>
        </select>
    </div>
    <div>
        <label class="admin-label" for="hours">الساعات</label>
        <input id="hours" type="number" step="0.5" name="hours" value="{{ old('hours', $c?->hours) }}" class="admin-input">
    </div>
</div>
