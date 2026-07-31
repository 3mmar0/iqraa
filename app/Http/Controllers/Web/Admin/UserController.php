<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()->with('roles')->latest()->paginate(30);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', Password::defaults()],
            'status' => ['required', 'string', 'in:invited,active'],
        ]);

        if ($validated['status'] === 'active' && empty($validated['password'])) {
            return back()->withInput()->withErrors(['password' => 'كلمة المرور مطلوبة للحساب النشط.']);
        }

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password'] ?? str()->random(32)),
            'creation_source' => 'admin_created',
            'status' => $validated['status'],
        ]);

        $studentRole = Role::query()->where('slug', 'student')->first();
        if ($studentRole) {
            $user->roles()->syncWithoutDetaching([$studentRole->id]);
        }

        return redirect()->route('admin.users.index')->with('status', 'تم إنشاء المستخدم.');
    }
}