<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreRegisteredUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(StoreRegisteredUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $roleSlug = $validated['account_type'];
        $role = Role::query()->where('slug', $roleSlug)->first();

        if (! $role || ! in_array($roleSlug, ['student', 'instructor'], true)) {
            abort(500, 'دور الحساب غير متوفر.');
        }

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'university' => $validated['university'] ?? null,
            'password' => $validated['password'],
            'creation_source' => 'self_registered',
            'status' => 'active',
        ]);

        $user->roles()->attach($role);

        UserSetting::query()->create(['user_id' => $user->id]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard.redirect');
    }
}
