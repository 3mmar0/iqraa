<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\MediaAsset;
use App\Services\AuditLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LessonMediaController extends Controller
{
    public function show(Request $request, Lesson $lesson, MediaAsset $media): StreamedResponse|Response
    {
        if ($media->lesson_id !== $lesson->id) {
            abort(404);
        }

        $disk = Storage::disk($media->disk ?: 'local_private');
        abort_unless($disk->exists($media->path), 404);

        $path = $disk->path($media->path);
        $mime = $media->mime ?: 'application/octet-stream';
        $size = filesize($path) ?: (int) ($media->size ?? 0);
        $start = 0;
        $end = max(0, $size - 1);
        $status = 200;

        if ($range = $request->header('Range')) {
            if (preg_match('/bytes=(\d+)-(\d*)/', $range, $matches)) {
                $start = (int) $matches[1];
                if ($matches[2] !== '') {
                    $end = (int) $matches[2];
                }
                $end = min($end, $size - 1);
                if ($start > $end || $start >= $size) {
                    return response('Requested Range Not Satisfiable', 416, [
                        'Content-Range' => "bytes */{$size}",
                    ]);
                }
                $status = 206;
            }
        }

        $length = $end - $start + 1;
        $filename = addslashes($media->original_name ?: basename($media->path));
        $headers = [
            'Content-Type' => $mime,
            'Accept-Ranges' => 'bytes',
            'Content-Length' => $length,
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
            'Cache-Control' => 'private, max-age=3600',
        ];

        if ($status === 206) {
            $headers['Content-Range'] = "bytes {$start}-{$end}/{$size}";
        }

        return response()->stream(function () use ($path, $start, $length) {
            $stream = fopen($path, 'rb');
            fseek($stream, $start);
            $remaining = $length;
            while ($remaining > 0 && ! feof($stream)) {
                $read = fread($stream, min(8192, $remaining));
                if ($read === false) {
                    break;
                }
                echo $read;
                $remaining -= strlen($read);
            }
            fclose($stream);
        }, $status, $headers);
    }

    public function store(Request $request, Lesson $lesson): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'max:204800'],
            'type' => ['nullable', 'string', Rule::in(['video', 'pdf', 'attachment', 'image', 'file'])],
        ], [
            'file.required' => 'الملف مطلوب.',
            'file.max' => 'الحد الأقصى لحجم الملف 200 ميجابايت.',
        ]);

        $file = $validated['file'];
        $type = $validated['type'] ?? $this->guessType($file->getClientMimeType());
        if ($type === '' || $type === null) {
            $type = $this->guessType($file->getClientMimeType());
        }

        $path = $file->store('lessons/'.$lesson->id, 'local_private');

        if (! $path) {
            if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                return response()->json(['message' => 'تعذر حفظ الملف على الخادم.'], 500);
            }

            return back()->with('error', 'تعذر حفظ الملف على الخادم.');
        }

        $asset = MediaAsset::query()->create([
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

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            $kind = match (true) {
                $type === 'video' || str_starts_with((string) $asset->mime, 'video/') => 'video',
                $type === 'image' || str_starts_with((string) $asset->mime, 'image/') => 'image',
                $type === 'pdf' || $asset->mime === 'application/pdf' => 'pdf',
                default => 'file',
            };

            return response()->json([
                'message' => 'تم رفع الملف.',
                'data' => [
                    'id' => $asset->id,
                    'name' => $asset->original_name,
                    'type' => $asset->type,
                    'mime' => $asset->mime,
                    'size' => $asset->size,
                    'kind' => $kind,
                    'preview_url' => route('admin.lessons.media.show', [$lesson, $asset]),
                    'destroy_url' => route('admin.lessons.media.destroy', [$lesson, $asset]),
                ],
            ], 201);
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
