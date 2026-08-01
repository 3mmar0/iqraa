<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Modules\Students\Services\StudentAdminService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentBulkController extends Controller
{
    public function __construct(private readonly StudentAdminService $students)
    {
    }

    public function destroy(Request $request): RedirectResponse
    {
        $ids = $this->validatedIds($request);

        if (in_array($request->user()->id, $ids, true)) {
            return back()->with('error', 'لا يمكنك حذف حسابك الحالي ضمن العملية الجماعية.');
        }

        $this->students->bulkDelete($ids, $request->user());

        return back()->with('status', 'تم حذف الطلاب المحددين.');
    }

    public function activate(Request $request): RedirectResponse
    {
        $ids = $this->validatedIds($request);
        $count = $this->students->bulkStatus($ids, 'active', $request->user());

        return back()->with('status', "تم تفعيل {$count} طالب.");
    }

    public function deactivate(Request $request): RedirectResponse
    {
        $ids = $this->validatedIds($request);
        $count = $this->students->bulkStatus($ids, 'disabled', $request->user());

        return back()->with('status', "تم تعطيل {$count} طالب.");
    }

    public function assignCourse(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:users,id'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
        ], [
            'ids.required' => 'حدد طالباً واحداً على الأقل.',
            'course_id.required' => 'اختر مقرراً.',
        ]);

        $course = Course::query()->findOrFail($validated['course_id']);

        foreach ($validated['ids'] as $id) {
            $user = \App\Models\User::query()->find($id);
            if ($user && $user->hasRole('student')) {
                $this->students->assignCourse($user, $course->id, $request->user());
            }
        }

        return back()->with('status', 'تم إسناد المقرر للطلاب المحددين.');
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->only([
            'q', 'status', 'university', 'gender', 'academic_year_id',
            'semester_id', 'group_id', 'paid', 'verified',
        ]);

        $rows = $this->students->exportRows($filters);

        $filename = 'students-'.now()->format('Y-m-d-His').'.csv';

        return Response::streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['id', 'name', 'email', 'phone', 'university', 'status', 'created_at']);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->id,
                    $row->name,
                    $row->email,
                    $row->phone,
                    $row->university,
                    $row->status,
                    $row->created_at?->toDateTimeString(),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /** @return list<int> */
    private function validatedIds(Request $request): array
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:users,id'],
        ], [
            'ids.required' => 'حدد طالباً واحداً على الأقل.',
        ]);

        return array_map('intval', $validated['ids']);
    }
}
