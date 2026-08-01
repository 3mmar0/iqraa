<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SemesterController extends Controller
{
    public function __construct(private readonly AuditLogger $audit)
    {
    }

    public function index(Request $request): View
    {
        $query = Semester::query()->with('academicYear')->latest();

        if ($yearId = $request->query('academic_year_id')) {
            $query->where('academic_year_id', $yearId);
        }

        $semesters = $query->paginate(20)->withQueryString();
        $years = AcademicYear::query()->orderByDesc('starts_on')->get();

        return view('admin.semesters.index', compact('semesters', 'years'));
    }

    public function create(Request $request): View
    {
        $years = AcademicYear::query()->orderByDesc('starts_on')->get();
        $selectedYearId = $request->query('academic_year_id');

        return view('admin.semesters.create', compact('years', 'selectedYearId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSemester($request);

        if ($request->boolean('is_current')) {
            Semester::query()
                ->where('academic_year_id', $validated['academic_year_id'])
                ->update(['is_current' => false]);
        }

        $validated['is_current'] = $request->boolean('is_current');

        $semester = Semester::query()->create($validated);

        $this->audit->log($request->user(), 'semester.created', Semester::class, $semester->id);

        return redirect()->route('admin.semesters.index', [
            'academic_year_id' => $semester->academic_year_id,
        ])->with('status', 'تم إنشاء الفصل الدراسي.');
    }

    public function edit(Semester $semester): View
    {
        $years = AcademicYear::query()->orderByDesc('starts_on')->get();

        return view('admin.semesters.edit', compact('semester', 'years'));
    }

    public function update(Request $request, Semester $semester): RedirectResponse
    {
        $validated = $this->validateSemester($request);

        if ($request->boolean('is_current')) {
            Semester::query()
                ->where('academic_year_id', $validated['academic_year_id'])
                ->where('id', '!=', $semester->id)
                ->update(['is_current' => false]);
        }

        $validated['is_current'] = $request->boolean('is_current');

        $semester->update($validated);

        $this->audit->log($request->user(), 'semester.updated', Semester::class, $semester->id);

        return redirect()->route('admin.semesters.index', [
            'academic_year_id' => $semester->academic_year_id,
        ])->with('status', 'تم تحديث الفصل الدراسي.');
    }

    public function destroy(Request $request, Semester $semester): RedirectResponse
    {
        if ($semester->courses()->exists()) {
            return back()->with('status', 'لا يمكن حذف فصل مرتبط بمقررات.');
        }

        $yearId = $semester->academic_year_id;
        $id = $semester->id;
        $semester->delete();

        $this->audit->log($request->user(), 'semester.deleted', Semester::class, $id);

        return redirect()->route('admin.semesters.index', [
            'academic_year_id' => $yearId,
        ])->with('status', 'تم حذف الفصل الدراسي.');
    }

    /** @return array<string, mixed> */
    private function validateSemester(Request $request): array
    {
        return $request->validate([
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
            'name' => ['required', 'string', 'max:255'],
            'term_number' => ['nullable', 'integer', 'min:1', 'max:12'],
            'starts_on' => ['nullable', 'date'],
            'ends_on' => ['nullable', 'date', 'after_or_equal:starts_on'],
            'is_current' => ['sometimes', 'boolean'],
        ], [
            'name.required' => 'اسم الفصل مطلوب.',
            'academic_year_id.required' => 'السنة الدراسية مطلوبة.',
        ]);
    }
}
