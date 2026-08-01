<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Group;
use App\Models\Semester;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GroupController extends Controller
{
    public function __construct(private readonly AuditLogger $audit)
    {
    }

    public function index(Request $request): View
    {
        $query = Group::query()
            ->with(['academicYear', 'semester'])
            ->withCount('users')
            ->latest();

        if ($yearId = $request->query('academic_year_id')) {
            $query->where('academic_year_id', $yearId);
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $groups = $query->paginate(20)->withQueryString();
        $years = AcademicYear::query()->orderByDesc('starts_on')->get();

        return view('admin.groups.index', compact('groups', 'years'));
    }

    public function create(): View
    {
        $years = AcademicYear::query()->orderByDesc('starts_on')->get();
        $semesters = Semester::query()->with('academicYear')->orderBy('name')->get();

        return view('admin.groups.create', compact('years', 'semesters'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateGroup($request);

        $group = Group::query()->create($validated);

        $this->audit->log($request->user(), 'group.created', Group::class, $group->id);

        return redirect()->route('admin.groups.show', $group)->with('status', 'تم إنشاء المجموعة.');
    }

    public function show(Group $group): View
    {
        $group->load(['academicYear', 'semester', 'users']);
        $students = User::query()
            ->whereHas('roles', fn ($q) => $q->where('slug', 'student'))
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.groups.show', compact('group', 'students'));
    }

    public function edit(Group $group): View
    {
        $years = AcademicYear::query()->orderByDesc('starts_on')->get();
        $semesters = Semester::query()->with('academicYear')->orderBy('name')->get();

        return view('admin.groups.edit', compact('group', 'years', 'semesters'));
    }

    public function update(Request $request, Group $group): RedirectResponse
    {
        $validated = $this->validateGroup($request);

        $group->update($validated);

        $this->audit->log($request->user(), 'group.updated', Group::class, $group->id);

        return redirect()->route('admin.groups.show', $group)->with('status', 'تم تحديث المجموعة.');
    }

    public function destroy(Request $request, Group $group): RedirectResponse
    {
        $id = $group->id;
        $group->users()->detach();
        $group->delete();

        $this->audit->log($request->user(), 'group.deleted', Group::class, $id);

        return redirect()->route('admin.groups.index')->with('status', 'تم حذف المجموعة.');
    }

    public function attachMember(Request $request, Group $group): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $group->users()->syncWithoutDetaching([$validated['user_id']]);

        $this->audit->log($request->user(), 'group.member_attached', Group::class, $group->id, [
            'user_id' => $validated['user_id'],
        ]);

        return back()->with('status', 'تم إضافة العضو للمجموعة.');
    }

    public function detachMember(Request $request, Group $group, User $user): RedirectResponse
    {
        $group->users()->detach($user->id);

        $this->audit->log($request->user(), 'group.member_detached', Group::class, $group->id, [
            'user_id' => $user->id,
        ]);

        return back()->with('status', 'تم إزالة العضو من المجموعة.');
    }

    /** @return array<string, mixed> */
    private function validateGroup(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'semester_id' => ['nullable', 'integer', 'exists:semesters,id'],
            'status' => ['required', Rule::in(['active', 'inactive', 'archived'])],
        ], [
            'name.required' => 'اسم المجموعة مطلوب.',
        ]);
    }
}
