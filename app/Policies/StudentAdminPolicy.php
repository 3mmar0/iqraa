<?php

namespace App\Policies;

use App\Models\User;

class StudentAdminPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->hasPermission('admin.students');
    }

    public function view(User $user, User $student): bool
    {
        return $this->viewAny($user) && $student->hasRole('student');
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, User $student): bool
    {
        return $this->view($user, $student);
    }

    public function delete(User $user, User $student): bool
    {
        return $this->view($user, $student);
    }
}
