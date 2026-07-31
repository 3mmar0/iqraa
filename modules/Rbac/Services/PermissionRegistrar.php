<?php

namespace Modules\Rbac\Services;

use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Throwable;

class PermissionRegistrar
{
    public function registerGates(): void
    {
        try {
            foreach (Permission::query()->pluck('slug') as $slug) {
                Gate::define($slug, function ($user) use ($slug) {
                    return method_exists($user, 'hasPermission') && $user->hasPermission($slug);
                });
            }
        } catch (Throwable) {
            // Database may not be migrated yet during early boot/artisan.
        }
    }
}
