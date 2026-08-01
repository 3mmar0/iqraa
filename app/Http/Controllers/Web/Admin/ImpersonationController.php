<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function start(Request $request, User $user): RedirectResponse
    {
        $admin = $request->user();

        if ($user->id === $admin->id) {
            return back()->with('error', 'لا يمكنك تسجيل الدخول كنفسك.');
        }

        if ($user->status !== 'active') {
            return back()->with('error', 'لا يمكن الدخول كحساب غير نشط.');
        }

        if (session()->has('impersonator_id')) {
            return back()->with('error', 'أنت بالفعل تتصفح بحساب آخر. عد أولاً لحساب المدير.');
        }

        session(['impersonator_id' => $admin->id]);
        Auth::login($user);
        $request->session()->regenerate();

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($admin, 'user.impersonate.start', 'user', $user->id, [
                'as' => $user->email,
            ]);
        }

        return redirect()->route('dashboard.redirect')
            ->with('status', 'تم الدخول كـ '.$user->name);
    }

    public function leave(Request $request): RedirectResponse
    {
        $impersonatorId = session()->pull('impersonator_id');

        if (! $impersonatorId) {
            return redirect()->route('home');
        }

        $admin = User::query()->find($impersonatorId);

        if (! $admin) {
            Auth::logout();

            return redirect()->route('login')->with('error', 'تعذر استعادة حساب المدير.');
        }

        $was = $request->user();
        Auth::login($admin);
        $request->session()->regenerate();

        if (class_exists(AuditLogger::class) && $was) {
            app(AuditLogger::class)->log($admin, 'user.impersonate.stop', 'user', $was->id);
        }

        return redirect()->route('admin.home')->with('status', 'عدت إلى حساب المدير.');
    }
}
