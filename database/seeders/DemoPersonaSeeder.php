<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoPersonaSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        $approverRole = Role::query()->updateOrCreate(
            ['slug' => 'enrollment_approver'],
            ['name_ar' => 'معتمد التحاق', 'dashboard_key' => 'support']
        );
        $approvePermission = Permission::query()->where('slug', 'enrollments.approve')->first();
        if ($approvePermission) {
            $approverRole->permissions()->syncWithoutDetaching([$approvePermission->id]);
        }

        $personas = [
            ['email' => 'student@example.com', 'name' => 'طالب تجريبي', 'roles' => ['student'], 'source' => 'self_registered'],
            ['email' => 'student2@example.com', 'name' => 'طالب إداري', 'roles' => ['student'], 'source' => 'admin_created'],
            ['email' => 'instructor@example.com', 'name' => 'محاضر تجريبي', 'roles' => ['instructor'], 'source' => 'admin_created'],
            ['email' => 'support@example.com', 'name' => 'دعم بدون موافقة', 'roles' => ['support'], 'source' => 'admin_created'],
            ['email' => 'approver@example.com', 'name' => 'معتمد التحاق', 'roles' => ['enrollment_approver'], 'source' => 'admin_created'],
            ['email' => 'admin@example.com', 'name' => 'مدير النظام', 'roles' => ['super_admin'], 'source' => 'admin_created'],
            ['email' => 'multi@example.com', 'name' => 'متعدد الأدوار', 'roles' => ['student', 'instructor'], 'source' => 'admin_created'],
            ['email' => 'finance@example.com', 'name' => 'مالية', 'roles' => ['finance'], 'source' => 'admin_created'],
            ['email' => 'marketing@example.com', 'name' => 'تسويق', 'roles' => ['marketing'], 'source' => 'admin_created'],
            ['email' => 'team@example.com', 'name' => 'فريق', 'roles' => ['team'], 'source' => 'admin_created'],
        ];

        foreach ($personas as $persona) {
            $user = User::query()->updateOrCreate(
                ['email' => $persona['email']],
                [
                    'name' => $persona['name'],
                    'password' => $password,
                    'email_verified_at' => now(),
                    'creation_source' => $persona['source'],
                    'status' => 'active',
                    'university' => 'جامعة تجريبية',
                ]
            );

            $roleIds = Role::query()->whereIn('slug', $persona['roles'])->pluck('id');
            $user->roles()->sync($roleIds);
            UserSetting::query()->firstOrCreate(['user_id' => $user->id]);
        }
    }
}
