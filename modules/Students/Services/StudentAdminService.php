<?php

namespace Modules\Students\Services;

use App\Models\Enrollment;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentAdminService
{
    public function __construct(private readonly AuditLogger $audit)
    {
    }

    public function query(array $filters = []): Builder
    {
        $query = User::query()
            ->with(['roles', 'subscriptions', 'group'])
            ->whereHas('roles', fn (Builder $q) => $q->where('slug', 'student'))
            ->latest();

        if ($search = trim((string) ($filters['q'] ?? ''))) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('id', $search);
            });
        }

        foreach (['status', 'university', 'gender'] as $field) {
            if (! empty($filters[$field])) {
                $query->where($field, $filters[$field]);
            }
        }

        if (! empty($filters['academic_year_id'])) {
            $query->where('academic_year_id', $filters['academic_year_id']);
        }

        if (! empty($filters['semester_id'])) {
            $query->where('semester_id', $filters['semester_id']);
        }

        if (! empty($filters['group_id'])) {
            $query->where('group_id', $filters['group_id']);
        }

        if (($filters['paid'] ?? null) === '1') {
            $query->whereHas('subscriptions', fn (Builder $q) => $q->where('status', 'active'));
        }

        if (($filters['paid'] ?? null) === '0') {
            $query->whereDoesntHave('subscriptions', fn (Builder $q) => $q->where('status', 'active'));
        }

        if (($filters['verified'] ?? null) === '1') {
            $query->whereNotNull('email_verified_at');
        }

        if (($filters['verified'] ?? null) === '0') {
            $query->whereNull('email_verified_at');
        }

        return $query;
    }

    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->query($filters)->paginate($perPage)->withQueryString();
    }

    public function create(array $data, ?User $actor = null): User
    {
        $studentRole = Role::query()->where('slug', 'student')->firstOrFail();

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'university' => $data['university'] ?? null,
            'gender' => $data['gender'] ?? null,
            'academic_year_id' => $data['academic_year_id'] ?? null,
            'semester_id' => $data['semester_id'] ?? null,
            'group_id' => $data['group_id'] ?? null,
            'password' => Hash::make($data['password'] ?? Str::random(32)),
            'creation_source' => 'admin_created',
            'status' => $data['status'] ?? 'active',
            'email_verified_at' => now(),
        ]);

        $user->roles()->sync([$studentRole->id]);

        $this->audit->log($actor, 'student.created', User::class, $user->id, [
            'email' => $user->email,
        ]);

        return $user;
    }

    public function update(User $user, array $data, ?User $actor = null): User
    {
        $user->fill(collect($data)->only([
            'name', 'email', 'phone', 'university', 'gender', 'status',
            'academic_year_id', 'semester_id', 'group_id',
        ])->all());

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        $this->audit->log($actor, 'student.updated', User::class, $user->id);

        return $user->fresh();
    }

    public function setStatus(User $user, string $status, ?User $actor = null): User
    {
        $user->update(['status' => $status]);
        $this->audit->log($actor, 'student.status.'.$status, User::class, $user->id);

        return $user;
    }

    public function resetPassword(User $user, ?string $password = null, ?User $actor = null): string
    {
        $plain = $password ?? Str::password(12);
        $user->update(['password' => Hash::make($plain)]);
        $this->audit->log($actor, 'student.password_reset', User::class, $user->id);

        return $plain;
    }

    public function assignCourse(User $user, int $courseId, ?User $actor = null): Enrollment
    {
        $enrollment = Enrollment::query()->updateOrCreate(
            ['user_id' => $user->id, 'course_id' => $courseId],
            ['status' => 'active', 'source' => 'admin_grant', 'enrolled_at' => now(), 'revoked_at' => null]
        );

        $this->audit->log($actor, 'student.course_assigned', User::class, $user->id, [
            'course_id' => $courseId,
        ]);

        return $enrollment;
    }

    public function removeCourse(User $user, int $courseId, ?User $actor = null): void
    {
        Enrollment::query()
            ->where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->update(['status' => 'revoked', 'revoked_at' => now()]);

        $this->audit->log($actor, 'student.course_removed', User::class, $user->id, [
            'course_id' => $courseId,
        ]);
    }

    public function updatePlacement(User $user, array $data, ?User $actor = null): User
    {
        $user->fill(collect($data)->only([
            'academic_year_id', 'semester_id', 'group_id', 'university', 'status',
        ])->all());
        $user->save();

        $this->audit->log($actor, 'student.placement_updated', User::class, $user->id, $data);

        return $user->fresh();
    }

    public function updateNotes(User $user, ?string $notes, ?User $actor = null): User
    {
        $user->update(['admin_notes' => $notes]);
        $this->audit->log($actor, 'student.notes_updated', User::class, $user->id);

        return $user;
    }

    /** @param  list<int>  $ids */
    public function bulkStatus(array $ids, string $status, ?User $actor = null): int
    {
        $count = User::query()->whereIn('id', $ids)->update(['status' => $status]);
        $this->audit->log($actor, 'student.bulk_status.'.$status, null, null, ['ids' => $ids, 'count' => $count]);

        return $count;
    }

    /** @param  list<int>  $ids */
    public function bulkDelete(array $ids, ?User $actor = null): int
    {
        $count = User::query()->whereIn('id', $ids)->delete();
        $this->audit->log($actor, 'student.bulk_delete', null, null, ['ids' => $ids, 'count' => $count]);

        return $count;
    }

    public function exportRows(array $filters = []): Collection
    {
        return $this->query($filters)->limit(5000)->get();
    }
}
