<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->query('type', 'students');
        if (! in_array($type, ['students', 'staff'], true)) {
            $type = 'students';
        }

        $query = User::query()->with('roles')->latest();

        if ($type === 'students') {
            $query->whereHas('roles', fn ($q) => $q->where('slug', 'student'))
                ->whereDoesntHave('roles', fn ($q) => $q->where('slug', '!=', 'student'));
        } else {
            $query->whereHas('roles', fn ($q) => $q->where('slug', '!=', 'student'));
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($role = $request->query('role')) {
            $query->whereHas('roles', fn ($q) => $q->where('slug', $role));
        }

        $users = $query->paginate(20)->withQueryString();

        $roles = $type === 'students'
            ? Role::query()->where('slug', 'student')->orderBy('name_ar')->get()
            : Role::query()->where('slug', '!=', 'student')->orderBy('name_ar')->get();

        $pageTitle = $type === 'students' ? 'الطلاب' : 'فريق العمل';

        return view('admin.users.index', compact('users', 'roles', 'type', 'pageTitle'));
    }

    public function create(Request $request): View
    {
        $type = $request->query('type', 'students');
        $roles = Role::query()->orderBy('name_ar')->get();

        return view('admin.users.create', compact('roles', 'type'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50', 'unique:users,phone'],
            'university' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', Password::defaults()],
            'status' => ['required', 'string', 'in:invited,active,disabled'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['integer', 'exists:roles,id'],
            'type' => ['nullable', 'string', 'in:students,staff'],
        ], [
            'required' => 'هذا الحقل مطلوب.',
            'email.unique' => 'البريد مستخدم مسبقاً.',
            'phone.unique' => 'رقم الهاتف مستخدم مسبقاً.',
        ]);

        if ($validated['status'] === 'active' && empty($validated['password'])) {
            return back()->withInput()->withErrors(['password' => 'كلمة المرور مطلوبة للحساب النشط.']);
        }

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'university' => $validated['university'] ?? null,
            'password' => Hash::make($validated['password'] ?? str()->random(32)),
            'creation_source' => 'admin_created',
            'status' => $validated['status'],
            'email_verified_at' => now(),
        ]);

        $roleIds = $validated['roles'] ?? [];
        if ($roleIds === []) {
            $student = Role::query()->where('slug', 'student')->first();
            if ($student) {
                $roleIds = [$student->id];
            }
        }
        $user->roles()->sync($roleIds);

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'user.created', 'user', $user->id, [
                'email' => $user->email,
            ]);
        }

        $type = $validated['type'] ?? 'students';

        return redirect()
            ->route('admin.users.index', ['type' => $type])
            ->with('status', 'تم إنشاء المستخدم بنجاح.');
    }

    public function edit(User $user): View
    {
        $user->load('roles');
        $roles = Role::query()->orderBy('name_ar')->get();
        $type = $user->roles->contains(fn ($r) => $r->slug !== 'student') ? 'staff' : 'students';

        return view('admin.users.edit', compact('user', 'roles', 'type'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50', Rule::unique('users', 'phone')->ignore($user->id)],
            'university' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'status' => ['required', 'string', 'in:invited,active,disabled'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['integer', 'exists:roles,id'],
        ], [
            'required' => 'هذا الحقل مطلوب.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'university' => $validated['university'] ?? null,
            'status' => $validated['status'],
        ]);

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();
        $user->roles()->sync($validated['roles'] ?? []);

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'user.updated', 'user', $user->id);
        }

        $type = $user->fresh()->roles->contains(fn ($r) => $r->slug !== 'student') ? 'staff' : 'students';

        return redirect()
            ->route('admin.users.index', ['type' => $type])
            ->with('status', 'تم تحديث المستخدم.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'لا يمكنك حذف حسابك الحالي.');
        }

        if ($user->hasRole('super_admin') && User::query()->whereHas('roles', fn ($q) => $q->where('slug', 'super_admin'))->count() <= 1) {
            return back()->with('error', 'لا يمكن حذف آخر مدير نظام.');
        }

        $type = $user->roles->contains(fn ($r) => $r->slug !== 'student') ? 'staff' : 'students';
        $id = $user->id;
        $user->roles()->detach();
        $user->delete();

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'user.deleted', 'user', $id);
        }

        return redirect()
            ->route('admin.users.index', ['type' => $type])
            ->with('status', 'تم حذف المستخدم.');
    }
}
