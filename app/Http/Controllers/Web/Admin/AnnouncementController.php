<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Course;
use App\Models\User;
use App\Notifications\AnnouncementPublishedNotification;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function __construct(private readonly AuditLogger $audit)
    {
    }

    public function index(Request $request): View
    {
        $query = Announcement::query()->with(['author', 'course'])->latest();

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($status = $request->query('status')) {
            match ($status) {
                'draft' => $query->whereNull('published_at'),
                'scheduled' => $query->whereNotNull('published_at')->where('published_at', '>', now()),
                'published' => $query->whereNotNull('published_at')->where('published_at', '<=', now()),
                default => null,
            };
        }

        $announcements = $query->paginate(20)->withQueryString();

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create(): View
    {
        $courses = Course::query()->orderBy('title')->get(['id', 'title']);

        return view('admin.announcements.create', compact('courses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateAnnouncement($request);

        $announcement = Announcement::query()->create([
            ...$validated,
            'author_id' => $request->user()->id,
            'published_at' => null,
        ]);

        $this->audit->log($request->user(), 'announcement.created', Announcement::class, $announcement->id);

        return redirect()->route('admin.announcements.index')->with('status', 'تم إنشاء الإعلان كمسودة.');
    }

    public function edit(Announcement $announcement): View
    {
        $courses = Course::query()->orderBy('title')->get(['id', 'title']);

        return view('admin.announcements.edit', compact('announcement', 'courses'));
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $validated = $this->validateAnnouncement($request);

        $announcement->update($validated);

        $this->audit->log($request->user(), 'announcement.updated', Announcement::class, $announcement->id);

        return redirect()->route('admin.announcements.index')->with('status', 'تم تحديث الإعلان.');
    }

    public function destroy(Request $request, Announcement $announcement): RedirectResponse
    {
        $id = $announcement->id;
        $announcement->delete();

        $this->audit->log($request->user(), 'announcement.deleted', Announcement::class, $id);

        return redirect()->route('admin.announcements.index')->with('status', 'تم حذف الإعلان.');
    }

    public function draft(Request $request, Announcement $announcement): RedirectResponse
    {
        $announcement->update(['published_at' => null]);

        $this->audit->log($request->user(), 'announcement.drafted', Announcement::class, $announcement->id);

        return back()->with('status', 'تم تحويل الإعلان إلى مسودة.');
    }

    public function schedule(Request $request, Announcement $announcement): RedirectResponse
    {
        $validated = $request->validate([
            'published_at' => ['required', 'date', 'after:now'],
        ], [
            'published_at.required' => 'موعد النشر مطلوب.',
            'published_at.after' => 'موعد النشر يجب أن يكون في المستقبل.',
        ]);

        $announcement->update(['published_at' => $validated['published_at']]);

        $this->audit->log($request->user(), 'announcement.scheduled', Announcement::class, $announcement->id);

        return back()->with('status', 'تم جدولة الإعلان.');
    }

    public function publish(Request $request, Announcement $announcement): RedirectResponse
    {
        $announcement->update(['published_at' => now()]);

        $this->audit->log($request->user(), 'announcement.published', Announcement::class, $announcement->id);

        return back()->with('status', 'تم نشر الإعلان.');
    }

    public function pin(Request $request, Announcement $announcement): RedirectResponse
    {
        // Pin persistence requires schema extension (pinned_at column).
        $this->audit->log($request->user(), 'announcement.pinned_stub', Announcement::class, $announcement->id);

        return back()->with('status', 'تم تثبيت الإعلان (يتطلب عمود pinned في قاعدة البيانات للحفظ الدائم).');
    }

    public function archive(Request $request, Announcement $announcement): RedirectResponse
    {
        // Archive persistence requires schema extension; unpublish as proxy.
        $announcement->update(['published_at' => null]);

        $this->audit->log($request->user(), 'announcement.archived_stub', Announcement::class, $announcement->id);

        return back()->with('status', 'تم أرشفة الإعلان (إخفاء من النشر).');
    }

    public function sendNotification(Request $request, Announcement $announcement): RedirectResponse
    {
        $recipients = User::query()
            ->when($announcement->course_id, function ($q) use ($announcement) {
                $q->whereHas('roles', fn ($r) => $r->where('slug', 'student'));
            })
            ->limit(100)
            ->get();

        Notification::send($recipients, new AnnouncementPublishedNotification($announcement));

        $this->audit->log($request->user(), 'announcement.notification_sent', Announcement::class, $announcement->id, [
            'recipients' => $recipients->count(),
        ]);

        return back()->with('status', "تم إرسال إشعار إلى {$recipients->count()} مستخدم.");
    }

    /** @return array<string, mixed> */
    private function validateAnnouncement(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'course_id' => ['nullable', 'integer', 'exists:courses,id'],
        ], [
            'title.required' => 'العنوان مطلوب.',
            'body.required' => 'المحتوى مطلوب.',
        ]);
    }

    /** Derive display status from published_at (no status column on model). */
    public static function statusLabel(?Announcement $announcement): string
    {
        if (! $announcement?->published_at) {
            return 'مسودة';
        }

        return $announcement->published_at->isFuture() ? 'مجدول' : 'منشور';
    }
}
