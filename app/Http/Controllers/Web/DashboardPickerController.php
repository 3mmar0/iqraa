<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardPickerController extends Controller
{
    private const DASHBOARD_ROUTES = [
        'student' => 'student.home',
        'instructor' => 'instructor.home',
        'team' => 'team.home',
        'finance' => 'finance.home',
        'marketing' => 'marketing.home',
        'support' => 'support.home',
        'admin' => 'admin.home',
    ];

    public function redirect(Request $request): RedirectResponse|View
    {
        $keys = $request->user()->dashboardKeys();

        if (count($keys) === 0) {
            return view('auth.no-access');
        }

        if (count($keys) === 1) {
            return redirect()->route(self::DASHBOARD_ROUTES[$keys[0]]);
        }

        return view('auth.dashboard-picker', [
            'dashboards' => $keys,
            'labels' => [
                'student' => 'لوحة الطالب',
                'instructor' => 'لوحة المحاضر',
                'team' => 'لوحة الفريق',
                'finance' => 'لوحة المالية',
                'marketing' => 'لوحة التسويق',
                'support' => 'لوحة الدعم',
                'admin' => 'لوحة الإدارة',
            ],
        ]);
    }

    public function choose(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'dashboard' => ['required', 'string'],
        ]);

        $keys = $request->user()->dashboardKeys();
        if (! in_array($validated['dashboard'], $keys, true)) {
            abort(403, 'لوحة غير مسموحة.');
        }

        $request->session()->put('active_dashboard', $validated['dashboard']);

        return redirect()->route(self::DASHBOARD_ROUTES[$validated['dashboard']]);
    }
}
