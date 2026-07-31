<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function view(User $user, Course $course): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return (int) $course->instructor_user_id === (int) $user->id;
    }

    public function update(User $user, Course $course): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return (int) $course->instructor_user_id === (int) $user->id;
    }
}