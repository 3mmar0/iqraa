<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentRequest;
use App\Http\Requests\Admin\UpdateStudentRequest;
use App\Models\AcademicYear;
use App\Models\AuditLog;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\FinanceTransaction;
use App\Models\Group;
use App\Models\LessonProgress;
use App\Models\Order;
use App\Models\QuizAttempt;
use App\Models\Semester;
use App\Models\Subscription;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Students\Services\StudentAdminService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StudentController extends Controller
{
    public function __construct(private readonly StudentAdminService $students)
    {
    }

    public function index(Request $request): View
    {
        $filters = $request->only([
            'q', 'status', 'university', 'gender', 'academic_year_id',
            'semester_id', 'group_id', 'paid', 'verified',
        ]);

        $students = $this->students->paginate($filters);
        $groups = Group::query()->orderBy('name')->get(['id', 'name']);
        $academicYears = AcademicYear::query()->orderByDesc('starts_on')->get(['id', 'name']);

        return view('admin.students.index', compact('students', 'filters', 'groups', 'academicYears'));
    }

    public function create(): View
    {
        return view('admin.students.create', $this->formOptions());
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($validated['status'] === 'active' && empty($validated['password'])) {
            return back()->withInput()->withErrors(['password' => 'كلمة المرور مطلوبة للحساب النشط.']);
        }

        $student = $this->students->create($validated, $request->user());

        return redirect()
            ->route('admin.students.show', $student)
            ->with('status', 'تم إنشاء الطالب بنجاح.');
    }

    public function show(Request $request, User $student): View
    {
        $this->ensureStudent($student);

        $tab = $request->query('tab', 'overview');
        $student->load(['roles', 'subscriptions', 'group', 'academicYear', 'semester']);

        $tabData = $this->tabData($student, $tab);

        return view('admin.students.show', compact('student', 'tab', 'tabData'));
    }

    public function edit(User $student): View
    {
        $this->ensureStudent($student);
        $student->load(['roles', 'group', 'academicYear', 'semester']);

        return view('admin.students.edit', array_merge(
            ['student' => $student],
            $this->formOptions(),
        ));
    }

    public function update(UpdateStudentRequest $request, User $student): RedirectResponse
    {
        $this->ensureStudent($student);

        $this->students->update($student, $request->validated(), $request->user());

        return redirect()
            ->route('admin.students.show', $student)
            ->with('status', 'تم تحديث بيانات الطالب.');
    }

    public function destroy(Request $request, User $student): RedirectResponse
    {
        $this->ensureStudent($student);

        if ($student->id === $request->user()->id) {
            return back()->with('error', 'لا يمكنك حذف حسابك الحالي.');
        }

        $id = $student->id;
        $student->roles()->detach();
        $student->delete();

        app(\App\Services\AuditLogger::class)->log($request->user(), 'student.deleted', User::class, $id);

        return redirect()
            ->route('admin.students.index')
            ->with('status', 'تم حذف الطالب.');
    }

    public function suspend(Request $request, User $student): RedirectResponse
    {
        $this->ensureStudent($student);
        $this->students->setStatus($student, 'disabled', $request->user());

        return back()->with('status', 'تم تعليق حساب الطالب.');
    }

    public function activate(Request $request, User $student): RedirectResponse
    {
        $this->ensureStudent($student);
        $this->students->setStatus($student, 'active', $request->user());

        return back()->with('status', 'تم تفعيل حساب الطالب.');
    }

    public function resetPassword(Request $request, User $student): RedirectResponse
    {
        $this->ensureStudent($student);

        $request->validate([
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $plain = $this->students->resetPassword(
            $student,
            $request->input('password'),
            $request->user(),
        );

        return back()->with('status', 'تم إعادة تعيين كلمة المرور: '.$plain);
    }

    public function assignCourse(Request $request, User $student): RedirectResponse
    {
        $this->ensureStudent($student);

        $validated = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
        ], [
            'course_id.required' => 'اختر مقرراً.',
            'course_id.exists' => 'المقرر غير موجود.',
        ]);

        $this->students->assignCourse($student, (int) $validated['course_id'], $request->user());

        return back()->with('status', 'تم إسناد المقرر للطالب.');
    }

    public function removeCourse(Request $request, User $student): RedirectResponse
    {
        $this->ensureStudent($student);

        $validated = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
        ]);

        $this->students->removeCourse($student, (int) $validated['course_id'], $request->user());

        return back()->with('status', 'تم إزالة المقرر من الطالب.');
    }

    /** @return array<string, mixed> */
    private function formOptions(): array
    {
        return [
            'academicYears' => AcademicYear::query()->orderByDesc('starts_on')->get(),
            'semesters' => Semester::query()->orderByDesc('starts_on')->get(),
            'groups' => Group::query()->orderBy('name')->get(),
            'courses' => Course::query()->where('status', 'published')->orderBy('title')->get(['id', 'title']),
        ];
    }

    /** @return array<string, mixed> */
    private function tabData(User $student, string $tab): array
    {
        return match ($tab) {
            'courses' => [
                'enrollments' => Enrollment::query()
                    ->with('course:id,title,status')
                    ->where('user_id', $student->id)
                    ->latest()
                    ->get(),
                'courses' => Course::query()->where('status', 'published')->orderBy('title')->get(['id', 'title']),
            ],
            'payments' => [
                'transactions' => FinanceTransaction::query()
                    ->where('user_id', $student->id)
                    ->latest()
                    ->limit(50)
                    ->get(),
                'subscriptions' => Subscription::query()
                    ->where('user_id', $student->id)
                    ->latest()
                    ->get(),
            ],
            'quizzes' => [
                'attempts' => QuizAttempt::query()
                    ->with('quiz:id,title')
                    ->where('user_id', $student->id)
                    ->latest()
                    ->limit(50)
                    ->get(),
            ],
            'progress' => [
                'progress' => LessonProgress::query()
                    ->with('lesson:id,title,course_id')
                    ->where('user_id', $student->id)
                    ->latest('updated_at')
                    ->limit(50)
                    ->get(),
            ],
            'attendance' => [
                'placeholder' => true,
            ],
            'notifications' => [
                'notifications' => DB::getSchemaBuilder()->hasTable('notifications')
                    ? DB::table('notifications')->where('notifiable_id', $student->id)->latest()->limit(30)->get()
                    : collect(),
            ],
            'orders' => [
                'orders' => Order::query()
                    ->where('user_id', $student->id)
                    ->latest()
                    ->limit(50)
                    ->get(),
            ],
            'activity' => [
                'logs' => class_exists(AuditLog::class)
                    ? AuditLog::query()
                        ->where('target_type', User::class)
                        ->where('target_id', $student->id)
                        ->latest('created_at')
                        ->limit(50)
                        ->get()
                    : collect(),
            ],
            'notes' => [
                'placeholder' => true,
            ],
            default => [
                'enrollments_count' => Enrollment::query()->where('user_id', $student->id)->where('status', 'active')->count(),
                'orders_count' => Order::query()->where('user_id', $student->id)->count(),
                'active_subscription' => Subscription::query()->where('user_id', $student->id)->where('status', 'active')->first(),
            ],
        };
    }

    private function ensureStudent(User $user): void
    {
        if (! $user->hasRole('student')) {
            throw new NotFoundHttpException();
        }
    }
}
