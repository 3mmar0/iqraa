<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\MediaAsset;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LessonMediaController extends Controller
{
    public function store(Request $request, Lesson $lesson): RedirectResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'max:51200'],
            'type' => ['nullable', 'string', Rule::in(['video', 'pdf', 'attachment', 'image', 'file'])],
        ], [
            'file.required' => 'الملف مطلوب.',
            'file.max' => 'الحد الأقصى لحجم الملف 50 ميجابايت.',
        ]);

        $file = $validated['file'];
        $type = $validated['type'] ?? $this->guessType($file->getClientMimeType());
        $path = $file->store('lessons/'.$lesson->id, 'local_private');

        MediaAsset::query()->create([
            'lesson_id' => $lesson->id,
            'type' => $type,
            'disk' => 'local_private',
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'lesson.media.uploaded', Lesson::class, $lesson->id, [
                'type' => $type,
                'name' => $file->getClientOriginalName(),
            ]);
        }

        return back()->with('status', 'تم رفع الملف.');
    }

    public function destroy(Request $request, Lesson $lesson, MediaAsset $media): RedirectResponse
    {
        if ($media->lesson_id !== $lesson->id) {
            abort(404);
        }

        if ($media->path && Storage::disk($media->disk ?: 'local_private')->exists($media->path)) {
            Storage::disk($media->disk ?: 'local_private')->delete($media->path);
        }

        $media->delete();

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'lesson.media.deleted', Lesson::class, $lesson->id, [
                'media_id' => $media->id,
            ]);
        }

        return back()->with('status', 'تم حذف الملف.');
    }

    private function guessType(?string $mime): string
    {
        if (! $mime) {
            return 'file';
        }

        if (str_starts_with($mime, 'video/')) {
            return 'video';
        }

        if (str_starts_with($mime, 'image/')) {
            return 'image';
        }

        if ($mime === 'application/pdf') {
            return 'pdf';
        }

        return 'attachment';
    }
}
