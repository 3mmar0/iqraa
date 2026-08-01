<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcademicYearController extends Controller
{
    public function __construct(private readonly AuditLogger $audit)
    {
    }

    public function index(): View
    {
        $years = AcademicYear::query()->withCount(['semesters', 'groups'])->latest()->paginate(20);

        return view('admin.academic-years.index', compact('years'));
    }

    public function create(): View
    {
        return view('admin.academic-years.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateYear($request);

        if (! empty($validated['is_current'])) {
            AcademicYear::query()->update(['is_current' => false]);
        }

        $validated['is_current'] = $request->boolean('is_current');

        $year = AcademicYear::query()->create($validated);

        $this->audit->log($request->user(), 'academic_year.created', AcademicYear::class, $year->id);

        return redirect()->route('admin.academic-years.index')->with('status', 'تم إنشاء السنة الدراسية.');
    }

    public function edit(AcademicYear $academicYear): View
    {
        return view('admin.academic-years.edit', ['year' => $academicYear]);
    }

    public function update(Request $request, AcademicYear $academicYear): RedirectResponse
    {
        $validated = $this->validateYear($request);

        if ($request->boolean('is_current')) {
            AcademicYear::query()->where('id', '!=', $academicYear->id)->update(['is_current' => false]);
        }

        $validated['is_current'] = $request->boolean('is_current');

        $academicYear->update($validated);

        $this->audit->log($request->user(), 'academic_year.updated', AcademicYear::class, $academicYear->id);

        return redirect()->route('admin.academic-years.index')->with('status', 'تم تحديث السنة الدراسية.');
    }

    public function destroy(Request $request, AcademicYear $academicYear): RedirectResponse
    {
        if ($academicYear->semesters()->exists() || $academicYear->groups()->exists()) {
            return back()->with('status', 'لا يمكن حذف سنة مرتبطة بفصول أو مجموعات.');
        }

        $id = $academicYear->id;
        $academicYear->delete();

        $this->audit->log($request->user(), 'academic_year.deleted', AcademicYear::class, $id);

        return redirect()->route('admin.academic-years.index')->with('status', 'تم حذف السنة الدراسية.');
    }

    /** @return array<string, mixed> */
    private function validateYear(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'starts_on' => ['nullable', 'date'],
            'ends_on' => ['nullable', 'date', 'after_or_equal:starts_on'],
            'is_current' => ['sometimes', 'boolean'],
        ], [
            'name.required' => 'اسم السنة مطلوب.',
        ]);
    }
}
