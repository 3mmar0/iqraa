<?php

namespace App\Jobs;

use App\Models\AttendanceRecord;
use App\Models\AuditLog;
use App\Models\Course;
use App\Models\FinanceTransaction;
use App\Models\Quiz;
use App\Models\ReportJob;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class GenerateReportJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ReportJob $reportJob,
    ) {}

    public function handle(): void
    {
        $this->reportJob->update(['status' => 'running']);

        try {
            $rows = $this->buildRows($this->reportJob->type);
            $relativePath = 'reports/report-'.$this->reportJob->id.'.csv';
            $csv = $this->toCsv($rows);
            Storage::disk('local')->put($relativePath, $csv);

            $this->reportJob->update([
                'status' => 'done',
                'file_path' => $relativePath,
                'finished_at' => now(),
            ]);
        } catch (\Throwable $e) {
            $this->reportJob->update([
                'status' => 'failed',
                'finished_at' => now(),
            ]);

            throw $e;
        }
    }

    /** @return list<list<string|int|float|null>> */
    private function buildRows(string $type): array
    {
        return match ($type) {
            'students' => $this->studentsRows(),
            'teachers' => $this->teachersRows(),
            'courses' => $this->coursesRows(),
            'quizzes' => $this->quizzesRows(),
            'revenue', 'finance', 'payments' => $this->financeRows(),
            'attendance' => $this->attendanceRows(),
            'activity' => $this->activityRows(),
            default => [['message'], ['نوع التقرير غير مدعوم بالكامل: '.$type]],
        };
    }

    /** @return list<list<string|int|float|null>> */
    private function studentsRows(): array
    {
        $rows = [['id', 'name', 'email', 'phone', 'status', 'university', 'created_at']];
        User::query()
            ->whereHas('roles', fn ($q) => $q->where('slug', 'student'))
            ->orderBy('id')
            ->chunk(200, function ($users) use (&$rows) {
                foreach ($users as $user) {
                    $rows[] = [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->phone,
                        $user->status,
                        $user->university,
                        optional($user->created_at)?->toDateTimeString(),
                    ];
                }
            });

        return $rows;
    }

    /** @return list<list<string|int|float|null>> */
    private function teachersRows(): array
    {
        $rows = [['id', 'name', 'email', 'status', 'courses_count']];
        User::query()
            ->whereHas('roles', fn ($q) => $q->where('slug', 'instructor'))
            ->withCount('instructedCourses')
            ->orderBy('id')
            ->get()
            ->each(function (User $user) use (&$rows) {
                $rows[] = [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->status,
                    $user->instructed_courses_count,
                ];
            });

        return $rows;
    }

    /** @return list<list<string|int|float|null>> */
    private function coursesRows(): array
    {
        $rows = [['id', 'title', 'status', 'price', 'instructor', 'lessons_count', 'enrollments_count']];
        Course::query()
            ->with('instructor')
            ->withCount(['lessons', 'enrollments'])
            ->orderBy('id')
            ->get()
            ->each(function (Course $course) use (&$rows) {
                $rows[] = [
                    $course->id,
                    $course->title,
                    $course->status,
                    $course->price,
                    $course->instructor?->name,
                    $course->lessons_count,
                    $course->enrollments_count,
                ];
            });

        return $rows;
    }

    /** @return list<list<string|int|float|null>> */
    private function quizzesRows(): array
    {
        $rows = [['id', 'title', 'course_id', 'status', 'duration_minutes', 'questions_count']];
        Quiz::query()
            ->withCount('questions')
            ->orderBy('id')
            ->get()
            ->each(function (Quiz $quiz) use (&$rows) {
                $rows[] = [
                    $quiz->id,
                    $quiz->title,
                    $quiz->course_id,
                    $quiz->status,
                    $quiz->duration_minutes,
                    $quiz->questions_count,
                ];
            });

        return $rows;
    }

    /** @return list<list<string|int|float|null>> */
    private function financeRows(): array
    {
        $rows = [['id', 'amount', 'currency', 'type', 'status', 'user_id', 'reference', 'created_at']];
        FinanceTransaction::query()
            ->orderByDesc('id')
            ->limit(5000)
            ->get()
            ->each(function (FinanceTransaction $tx) use (&$rows) {
                $rows[] = [
                    $tx->id,
                    $tx->amount,
                    $tx->currency,
                    $tx->type,
                    $tx->status,
                    $tx->user_id,
                    $tx->reference,
                    optional($tx->created_at)?->toDateTimeString(),
                ];
            });

        return $rows;
    }

    /** @return list<list<string|int|float|null>> */
    private function attendanceRows(): array
    {
        $rows = [['id', 'user_id', 'date', 'status', 'created_at']];
        if (! class_exists(AttendanceRecord::class)) {
            return [['message'], ['لا توجد سجلات حضور']];
        }

        AttendanceRecord::query()
            ->orderByDesc('id')
            ->limit(5000)
            ->get()
            ->each(function ($row) use (&$rows) {
                $rows[] = [
                    $row->id,
                    $row->user_id ?? null,
                    $row->date ?? $row->attended_on ?? null,
                    $row->status ?? null,
                    optional($row->created_at)?->toDateTimeString(),
                ];
            });

        return $rows;
    }

    /** @return list<list<string|int|float|null>> */
    private function activityRows(): array
    {
        $rows = [['id', 'actor_id', 'action', 'target_type', 'target_id', 'created_at']];
        AuditLog::query()
            ->orderByDesc('id')
            ->limit(5000)
            ->get()
            ->each(function (AuditLog $log) use (&$rows) {
                $rows[] = [
                    $log->id,
                    $log->actor_id,
                    $log->action,
                    $log->target_type,
                    $log->target_id,
                    optional($log->created_at)?->toDateTimeString(),
                ];
            });

        return $rows;
    }

    /** @param  list<list<string|int|float|null>>  $rows */
    private function toCsv(array $rows): string
    {
        $handle = fopen('php://temp', 'r+');
        fwrite($handle, "\xEF\xBB\xBF");
        foreach ($rows as $row) {
            fputcsv($handle, array_map(fn ($v) => is_scalar($v) || $v === null ? $v : json_encode($v), $row));
        }
        rewind($handle);
        $csv = stream_get_contents($handle) ?: '';
        fclose($handle);

        return $csv;
    }
}
