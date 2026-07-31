<?php

namespace App\Http\Controllers\Web\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('student.profile', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30', 'unique:users,phone,'.$user->id],
            'university' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'current_password' => ['required_with:password'],
        ], [
            'required' => 'هذا الحقل مطلوب.',
            'phone.unique' => 'رقم الهاتف مستخدم.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        ]);

        if (! empty($validated['password'])) {
            if (! Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة.']);
            }
            $user->password = $validated['password'];
        }

        $user->fill([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'university' => $validated['university'] ?? null,
        ])->save();

        return back()->with('status', 'تم تحديث الملف الشخصي.');
    }
}
