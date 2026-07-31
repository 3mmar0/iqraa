<?php

namespace App\Http\Controllers\Web\Student;

use App\Http\Controllers\Controller;
use App\Models\UserSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(Request $request): View
    {
        $settings = UserSetting::query()->firstOrCreate(
            ['user_id' => $request->user()->id],
            ['dark_mode' => false, 'notification_preferences' => ['email' => true, 'in_app' => true]]
        );

        return view('student.settings', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $settings = UserSetting::query()->firstOrCreate(['user_id' => $request->user()->id]);
        $settings->update([
            'dark_mode' => $request->boolean('dark_mode'),
            'notification_preferences' => [
                'email' => $request->boolean('notify_email'),
                'in_app' => $request->boolean('notify_in_app'),
            ],
        ]);

        return back()->with('status', 'تم حفظ الإعدادات.');
    }
}
