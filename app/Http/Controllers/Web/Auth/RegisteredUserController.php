<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30', 'unique:users,phone'],
            'university' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'required' => 'هذا الحقل مطلوب.',
            'email.unique' => 'البريد الإلكتروني مستخدم مسبقاً.',
            'phone.unique' => 'رقم الهاتف مستخدم مسبقاً.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        ]);

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'university' => $validated['university'] ?? null,
            'password' => $validated['password'],
            'creation_source' => 'self_registered',
            'status' => 'active',
        ]);

        $studentRole = Role::query()->where('slug', 'student')->first();
        if ($studentRole) {
            $user->roles()->attach($studentRole);
        }

        UserSetting::query()->create(['user_id' => $user->id]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard.redirect');
    }
}
