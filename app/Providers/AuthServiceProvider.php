<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Policies\CoursePolicy;
use App\Policies\EnrollmentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Modules\Rbac\Services\PermissionRegistrar;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Course::class => CoursePolicy::class,
        Enrollment::class => EnrollmentPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function ($user, string $ability) {
            if (method_exists($user, 'hasRole') && $user->hasRole('super_admin')) {
                return true;
            }

            return null;
        });

        app(PermissionRegistrar::class)->registerGates();
    }
}