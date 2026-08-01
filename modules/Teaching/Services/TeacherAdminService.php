<?php

namespace Modules\Teaching\Services;

use App\Models\Course;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TeacherAdminService
{
    public function __construct(private readonly AuditLogger $audit)
    {
    }

    public function query(array $filters = []): Builder
    {
        $query = User::query()
            ->with(['roles'])
            ->withCount('instructedCourses')
            ->whereHas('roles', fn (Builder $q) => $q->where('slug', 'instructor'))
            ->latest();

        if ($search = trim((string) ($filters['q'] ?? ''))) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query;
    }

    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->query($filters)->paginate($perPage)->withQueryString();
    }

    /** @return Collection<int, User> */
    public function listInstructors(array $filters = []): Collection
    {
        return $this->query($filters)->get();
    }

    public function assignCourse(User $instructor, int $courseId, ?User $actor = null): Course
    {
        $course = Course::query()->findOrFail($courseId);
        $course->update(['instructor_user_id' => $instructor->id]);

        $this->audit->log($actor, 'teacher.course_assigned', User::class, $instructor->id, [
            'course_id' => $courseId,
        ]);

        return $course->fresh('instructor');
    }

    /** @param  list<int>  $courseIds */
    public function assignCourses(User $instructor, array $courseIds, ?User $actor = null): int
    {
        $count = Course::query()
            ->whereIn('id', $courseIds)
            ->update(['instructor_user_id' => $instructor->id]);

        $this->audit->log($actor, 'teacher.courses_assigned', User::class, $instructor->id, [
            'course_ids' => $courseIds,
            'count' => $count,
        ]);

        return $count;
    }
}
