<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Modules\Reports\Services\AdminReportService;

class ReportController extends Controller
{
    /** @var array<string, string> */
    public const REPORT_TYPES = [
        'students' => 'الطلاب',
        'revenue' => 'الإيرادات',
        'courses' => 'المقررات',
        'quizzes' => 'الاختبارات',
        'teachers' => 'المعلمون',
        'attendance' => 'الحضور',
        'activity' => 'النشاط',
        'finance' => 'المالية',
    ];

    public function __construct(private readonly AdminReportService $reports)
    {
    }

    public function index(): View
    {
        $reportTypes = self::REPORT_TYPES;
        $jobs = ReportJob::query()
            ->with('requester')
            ->latest()
            ->paginate(20);

        return view('admin.reports.index', compact('reportTypes', 'jobs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'string', Rule::in(array_keys(self::REPORT_TYPES))],
            'format' => ['nullable', 'string', Rule::in(['csv', 'xlsx', 'pdf'])],
        ]);

        $this->reports->enqueue(
            $request->user(),
            $validated['type'],
            $validated['format'] ?? 'csv',
            $request->user(),
        );

        return back()->with('status', 'تمت إضافة التقرير إلى قائمة الانتظار.');
    }
}
