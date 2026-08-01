<?php

use App\Http\Controllers\Web\Admin\AuditLogController;
use App\Http\Controllers\Web\Admin\CommsController;
use App\Http\Controllers\Web\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Web\Admin\EnrollmentRequestController as AdminEnrollmentRequestController;
use App\Http\Controllers\Web\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Web\Admin\ImpersonationController;
use App\Http\Controllers\Web\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Web\Admin\OpsController;
use App\Http\Controllers\Web\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Web\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Web\Admin\SecurityController;
use App\Http\Controllers\Web\Admin\UserController as AdminUserController;
use App\Http\Controllers\Web\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Web\Auth\NewPasswordController;
use App\Http\Controllers\Web\Auth\PasswordResetLinkController;
use App\Http\Controllers\Web\Auth\RegisteredUserController;
use App\Http\Controllers\Web\DashboardPickerController;
use App\Http\Controllers\Web\Finance\ExpenseController;
use App\Http\Controllers\Web\Finance\ForecastController;
use App\Http\Controllers\Web\Finance\HomeController as FinanceHomeController;
use App\Http\Controllers\Web\Finance\PayrollController;
use App\Http\Controllers\Web\Finance\ProfitController;
use App\Http\Controllers\Web\Finance\RefundController;
use App\Http\Controllers\Web\Finance\ReportController as FinanceReportController;
use App\Http\Controllers\Web\Finance\RevenueController;
use App\Http\Controllers\Web\Finance\SubscriptionController;
use App\Http\Controllers\Web\Finance\TransactionController;
use App\Http\Controllers\Web\Instructor\AnnouncementController as InstructorAnnouncementController;
use App\Http\Controllers\Web\Instructor\AssignmentController;
use App\Http\Controllers\Web\Instructor\CalendarController as InstructorCalendarController;
use App\Http\Controllers\Web\Instructor\CourseController as InstructorCourseController;
use App\Http\Controllers\Web\Instructor\DashboardController as InstructorDashboardController;
use App\Http\Controllers\Web\Instructor\HomeController as InstructorHomeController;
use App\Http\Controllers\Web\Instructor\LessonController as InstructorLessonController;
use App\Http\Controllers\Web\Instructor\LiveSessionController;
use App\Http\Controllers\Web\Instructor\MediaUploadController;
use App\Http\Controllers\Web\Instructor\MessageController;
use App\Http\Controllers\Web\Instructor\QuizController as InstructorQuizController;
use App\Http\Controllers\Web\Instructor\ReportController as InstructorReportController;
use App\Http\Controllers\Web\Instructor\SettingsController as InstructorSettingsController;
use App\Http\Controllers\Web\Instructor\StudentRosterController;
use App\Http\Controllers\Web\Marketing\AmbassadorController;
use App\Http\Controllers\Web\Marketing\AnalyticsController as MarketingAnalyticsController;
use App\Http\Controllers\Web\Marketing\CampaignController;
use App\Http\Controllers\Web\Marketing\ConversionController;
use App\Http\Controllers\Web\Marketing\CouponController;
use App\Http\Controllers\Web\Marketing\HomeController as MarketingHomeController;
use App\Http\Controllers\Web\Marketing\LeadController;
use App\Http\Controllers\Web\Marketing\ReferralController;
use App\Http\Controllers\Web\Public\CourseCatalogController;
use App\Http\Controllers\Web\Public\HomeController as PublicHomeController;
use App\Http\Controllers\Web\Staff\CourseRequestController as StaffCourseRequestController;
use App\Http\Controllers\Web\Student\AchievementController;
use App\Http\Controllers\Web\Student\CalendarController as StudentCalendarController;
use App\Http\Controllers\Web\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Web\Student\CourseRequestController as StudentCourseRequestController;
use App\Http\Controllers\Web\Student\HomeController as StudentHomeController;
use App\Http\Controllers\Web\Student\LessonController as StudentLessonController;
use App\Http\Controllers\Web\Student\MediaController;
use App\Http\Controllers\Web\Student\NotificationController;
use App\Http\Controllers\Web\Student\ProfileController;
use App\Http\Controllers\Web\Student\ProgressController;
use App\Http\Controllers\Web\Student\QuizController as StudentQuizController;
use App\Http\Controllers\Web\Student\SettingsController as StudentSettingsController;
use App\Http\Controllers\Web\Student\SupportController as StudentSupportController;
use App\Http\Controllers\Web\Support\ChatController;
use App\Http\Controllers\Web\Support\FaqController;
use App\Http\Controllers\Web\Support\HomeController as SupportHomeController;
use App\Http\Controllers\Web\Support\ReportController as SupportReportController;
use App\Http\Controllers\Web\Support\StudentLookupController;
use App\Http\Controllers\Web\Support\TicketController;
use App\Http\Controllers\Web\Team\AnnouncementController as TeamAnnouncementController;
use App\Http\Controllers\Web\Team\AttendanceController;
use App\Http\Controllers\Web\Team\FileController as TeamFileController;
use App\Http\Controllers\Web\Team\GoalController;
use App\Http\Controllers\Web\Team\HomeController as TeamHomeController;
use App\Http\Controllers\Web\Team\MeetingController;
use App\Http\Controllers\Web\Team\ReportController as TeamReportController;
use App\Http\Controllers\Web\Team\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicHomeController::class, 'index'])->name('home');
Route::get('/courses', [CourseCatalogController::class, 'index'])->name('public.courses.index');
Route::get('/courses/{course}', [CourseCatalogController::class, 'show'])->name('public.courses.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardPickerController::class, 'redirect'])->name('dashboard.redirect');
    Route::get('/dashboard-picker', [DashboardPickerController::class, 'redirect'])->name('dashboard.picker');
    Route::post('/dashboard-picker', [DashboardPickerController::class, 'choose'])->name('dashboard.choose');
    Route::post('/impersonation/leave', [ImpersonationController::class, 'leave'])->name('impersonation.leave');

    Route::middleware('dashboard:student')->prefix('student')->name('student.')->group(function () {
        Route::get('/', [StudentHomeController::class, 'index'])->name('home');
        Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/{course}', [StudentCourseController::class, 'show'])->name('courses.show');
        Route::get('/lessons/{lesson}', [StudentLessonController::class, 'show'])->name('lessons.show');
        Route::post('/lessons/{lesson}/complete', [StudentLessonController::class, 'complete'])->name('lessons.complete');
        Route::get('/media/{asset}', [MediaController::class, 'show'])->name('media.show');
        Route::get('/quizzes/{quiz}', [StudentQuizController::class, 'show'])->name('quizzes.show');
        Route::post('/quizzes/{quiz}/attempts', [StudentQuizController::class, 'start'])->name('quizzes.start');
        Route::post('/quiz-attempts/{attempt}/submit', [StudentQuizController::class, 'submit'])->name('quizzes.submit');
        Route::get('/quiz-attempts/{attempt}/result', [StudentQuizController::class, 'result'])->name('quizzes.result');
        Route::get('/course-requests', [StudentCourseRequestController::class, 'index'])->name('course-requests.index');
        Route::post('/course-requests', [StudentCourseRequestController::class, 'store'])->name('course-requests.store');
        Route::get('/progress', [ProgressController::class, 'index'])->name('progress');
        Route::get('/achievements', [AchievementController::class, 'index'])->name('achievements');
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
        Route::get('/calendar', [StudentCalendarController::class, 'index'])->name('calendar');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/settings', [StudentSettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [StudentSettingsController::class, 'update'])->name('settings.update');
        Route::get('/support', [StudentSupportController::class, 'index'])->name('support.index');
        Route::post('/support', [StudentSupportController::class, 'store'])->name('support.store');
    });

    Route::middleware('permission:enrollments.approve')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/course-requests', [StaffCourseRequestController::class, 'index'])->name('course-requests.index');
        Route::post('/course-requests/{courseAccessRequest}/approve', [StaffCourseRequestController::class, 'approve'])->name('course-requests.approve');
        Route::post('/course-requests/{courseAccessRequest}/reject', [StaffCourseRequestController::class, 'reject'])->name('course-requests.reject');
    });

    Route::middleware('dashboard:instructor')->prefix('instructor')->name('instructor.')->group(function () {
        Route::get('/', [InstructorHomeController::class, 'index'])->name('home');
        Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/courses', [InstructorCourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [InstructorCourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [InstructorCourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course}', [InstructorCourseController::class, 'show'])->name('courses.show');
        Route::post('/courses/{course}/lessons', [InstructorLessonController::class, 'store'])->name('lessons.store');
        Route::post('/courses/{course}/quizzes', [InstructorQuizController::class, 'store'])->name('quizzes.store');
        Route::post('/lessons/{lesson}/media', [MediaUploadController::class, 'store'])->name('media.store');
        Route::get('/students', [StudentRosterController::class, 'index'])->name('students.index');
        Route::get('/announcements', [InstructorAnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('/announcements', [InstructorAnnouncementController::class, 'store'])->name('announcements.store');
        Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
        Route::get('/live-sessions', [LiveSessionController::class, 'index'])->name('live-sessions.index');
        Route::post('/live-sessions', [LiveSessionController::class, 'store'])->name('live-sessions.store');
        Route::get('/reports', [InstructorReportController::class, 'index'])->name('reports.index');
        Route::get('/calendar', [InstructorCalendarController::class, 'index'])->name('calendar.index');
        Route::get('/settings', [InstructorSettingsController::class, 'index'])->name('settings.index');
        Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
    });

    Route::middleware('dashboard:finance')->prefix('finance')->name('finance.')->group(function () {
        Route::get('/', [FinanceHomeController::class, 'index'])->name('home');
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::get('/refunds', [RefundController::class, 'index'])->name('refunds.index');
        Route::post('/refunds', [RefundController::class, 'store'])->name('refunds.store');
        Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
        Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
        Route::get('/forecast', [ForecastController::class, 'index'])->name('forecast.index');
        Route::get('/profit', [ProfitController::class, 'index'])->name('profit.index');
        Route::get('/revenue', [RevenueController::class, 'index'])->name('revenue.index');
        Route::post('/reports', [FinanceReportController::class, 'store'])->name('reports.store');
    });

    Route::middleware('dashboard:marketing')->prefix('marketing')->name('marketing.')->group(function () {
        Route::get('/', [MarketingHomeController::class, 'index'])->name('home');
        Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
        Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
        Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
        Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
        Route::post('/coupons', [CouponController::class, 'store'])->name('coupons.store');
        Route::get('/referrals', [ReferralController::class, 'index'])->name('referrals.index');
        Route::get('/ambassadors', [AmbassadorController::class, 'index'])->name('ambassadors.index');
        Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
        Route::get('/conversions', [ConversionController::class, 'index'])->name('conversions.index');
        Route::get('/analytics', [MarketingAnalyticsController::class, 'index'])->name('analytics.index');
    });

    Route::middleware('dashboard:team')->prefix('team')->name('team.')->group(function () {
        Route::get('/', [TeamHomeController::class, 'index'])->name('home');
        Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
        Route::get('/announcements', [TeamAnnouncementController::class, 'index'])->name('announcements.index');
        Route::get('/files', [TeamFileController::class, 'index'])->name('files.index');
        Route::get('/meetings', [MeetingController::class, 'index'])->name('meetings.index');
        Route::get('/goals', [GoalController::class, 'index'])->name('goals.index');
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/reports', [TeamReportController::class, 'index'])->name('reports.index');
    });

    Route::middleware('dashboard:support')->prefix('support')->name('support.')->group(function () {
        Route::get('/', [SupportHomeController::class, 'index'])->name('home');
        Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
        Route::post('/tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close');
        Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
        Route::get('/students', [StudentLookupController::class, 'index'])->name('students.index');
        Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
        Route::post('/faq', [FaqController::class, 'store'])->name('faq.store');
        Route::get('/reports', [SupportReportController::class, 'index'])->name('reports.index');
    });

    Route::middleware('dashboard:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminHomeController::class, 'index'])->name('home');
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/impersonate', [ImpersonationController::class, 'start'])->name('users.impersonate');

        Route::get('/courses', [AdminCourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [AdminCourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [AdminCourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course}', [AdminCourseController::class, 'show'])->name('courses.show');
        Route::get('/courses/{course}/edit', [AdminCourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [AdminCourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course}', [AdminCourseController::class, 'destroy'])->name('courses.destroy');

        Route::get('/lessons', [AdminLessonController::class, 'index'])->name('lessons.index');
        Route::get('/lessons/create', [AdminLessonController::class, 'create'])->name('lessons.create');
        Route::post('/lessons', [AdminLessonController::class, 'store'])->name('lessons.store');
        Route::get('/lessons/{lesson}/edit', [AdminLessonController::class, 'edit'])->name('lessons.edit');
        Route::put('/lessons/{lesson}', [AdminLessonController::class, 'update'])->name('lessons.update');
        Route::delete('/lessons/{lesson}', [AdminLessonController::class, 'destroy'])->name('lessons.destroy');

        Route::get('/enrollment-requests', [AdminEnrollmentRequestController::class, 'index'])->name('enrollment-requests.index');
        Route::post('/enrollment-requests/{courseAccessRequest}/approve', [AdminEnrollmentRequestController::class, 'approve'])->name('enrollment-requests.approve');
        Route::post('/enrollment-requests/{courseAccessRequest}/reject', [AdminEnrollmentRequestController::class, 'reject'])->name('enrollment-requests.reject');

        Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');

        Route::get('/roles', [AdminRoleController::class, 'index'])->name('roles.index');
        Route::put('/roles/{role}', [AdminRoleController::class, 'update'])->name('roles.update');
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/ops', [OpsController::class, 'index'])->name('ops.index');
        Route::get('/comms', [CommsController::class, 'index'])->name('comms.index');
        Route::get('/security', [SecurityController::class, 'index'])->name('security.index');
    });
});
