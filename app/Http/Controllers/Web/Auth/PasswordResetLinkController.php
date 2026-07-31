<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']], [
            'required' => 'هذا الحقل مطلوب.',
            'email' => 'صيغة البريد غير صحيحة.',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'تم إرسال رابط الاستعادة إن وُجد الحساب.')
            : back()->withErrors(['email' => 'تعذر إرسال الرابط. حاول مرة أخرى.']);
    }
}
