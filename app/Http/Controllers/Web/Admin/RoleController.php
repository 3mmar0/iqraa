<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::query()->with('permissions')->orderBy('name_ar')->get();
        $permissions = Permission::query()->orderBy('slug')->get();

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        return back()->with('status', 'تم تحديث صلاحيات الدور.');
    }
}