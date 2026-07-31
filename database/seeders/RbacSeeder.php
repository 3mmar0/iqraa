<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'enrollments.approve' => 'الموافقة على طلبات الالتحاق',
            'courses.manage' => 'إدارة المقررات',
            'finance.refund' => 'تنفيذ الاستردادات',
            'support.tickets' => 'إدارة التذاكر',
            'admin.users' => 'إدارة المستخدمين',
            'admin.roles' => 'إدارة الأدوار والصلاحيات',
        ];

        foreach ($permissions as $slug => $nameAr) {
            Permission::query()->updateOrCreate(
                ['slug' => $slug],
                ['name_ar' => $nameAr]
            );
        }

        $roles = [
            ['slug' => 'student', 'name_ar' => 'طالب', 'dashboard_key' => 'student'],
            ['slug' => 'instructor', 'name_ar' => 'محاضر', 'dashboard_key' => 'instructor'],
            ['slug' => 'team', 'name_ar' => 'فريق', 'dashboard_key' => 'team'],
            ['slug' => 'finance', 'name_ar' => 'مالية', 'dashboard_key' => 'finance'],
            ['slug' => 'marketing', 'name_ar' => 'تسويق', 'dashboard_key' => 'marketing'],
            ['slug' => 'support', 'name_ar' => 'دعم', 'dashboard_key' => 'support'],
            ['slug' => 'super_admin', 'name_ar' => 'مدير النظام', 'dashboard_key' => 'admin'],
        ];

        foreach ($roles as $role) {
            Role::query()->updateOrCreate(['slug' => $role['slug']], $role);
        }

        $approver = Role::query()->where('slug', 'support')->first();
        $approvePermission = Permission::query()->where('slug', 'enrollments.approve')->first();
        if ($approver && $approvePermission) {
            // Default: support does NOT get approve — DemoPersonaSeeder grants to approver user via a dedicated role or direct attach.
            // Super admin bypasses via Gate::before.
        }

        $admin = Role::query()->where('slug', 'super_admin')->first();
        if ($admin) {
            $admin->permissions()->sync(Permission::query()->pluck('id'));
        }
    }
}
