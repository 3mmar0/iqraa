<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\TelegramGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Modules\Notifications\Services\TelegramAdminService;

class TelegramController extends Controller
{
    public function __construct(private readonly TelegramAdminService $telegram)
    {
    }

    public function index(): View
    {
        $groups = TelegramGroup::query()->with('course')->latest()->paginate(20);
        $courses = Course::query()->orderBy('title')->get(['id', 'title']);

        return view('admin.telegram.index', compact('groups', 'courses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'chat_id' => ['nullable', 'string', 'max:255'],
            'course_id' => ['nullable', 'integer', 'exists:courses,id'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ], [
            'title.required' => 'عنوان المجموعة مطلوب.',
        ]);

        $this->telegram->createGroup($validated, $request->user());

        return back()->with('status', 'تم إنشاء مجموعة تيليجرام.');
    }

    public function attachCourse(Request $request, TelegramGroup $telegramGroup): RedirectResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
        ]);

        $course = Course::query()->findOrFail($validated['course_id']);
        $this->telegram->linkCourse($telegramGroup, $course, $request->user());

        return back()->with('status', 'تم ربط المجموعة بالمقرر.');
    }

    public function generateInvite(Request $request, TelegramGroup $telegramGroup): RedirectResponse
    {
        $validated = $request->validate([
            'ttl_hours' => ['nullable', 'integer', 'min:1', 'max:168'],
        ]);

        $this->telegram->generateInvite(
            $telegramGroup,
            $request->user(),
            (int) ($validated['ttl_hours'] ?? 24),
        );

        return back()->with('status', 'تم إنشاء رابط الدعوة.');
    }

    public function expireLink(Request $request, TelegramGroup $telegramGroup): RedirectResponse
    {
        $this->telegram->expireLink($telegramGroup, $request->user());

        return back()->with('status', 'تم إنهاء صلاحية الرابط.');
    }

    public function sendAnnouncement(Request $request, TelegramGroup $telegramGroup): RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:4000'],
        ], [
            'message.required' => 'نص الإعلان مطلوب.',
        ]);

        $this->telegram->sendAnnouncement(
            $telegramGroup,
            $validated['message'],
            [],
            $request->user(),
        );

        return back()->with('status', 'تم إرسال الإعلان (وضع تجريبي — بدون API فعلي).');
    }
}
