<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\UploadSession;
use App\Services\AuditLogger;
use App\Services\ResumableCourseVideoUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CourseIntroVideoController extends Controller
{
    public function __construct(private readonly ResumableCourseVideoUploadService $uploads)
    {
    }

    public function init(Request $request, Course $course): JsonResponse
    {
        $validated = $request->validate([
            'original_name' => ['required', 'string', 'max:255'],
            'total_size' => ['required', 'integer', 'min:1', 'max:'.ResumableCourseVideoUploadService::MAX_SIZE],
            'mime' => ['nullable', 'string', 'max:100'],
            'fingerprint' => ['nullable', 'string', 'max:191'],
        ], [
            'original_name.required' => 'اسم الملف مطلوب.',
            'total_size.required' => 'حجم الملف مطلوب.',
        ]);

        $session = $this->uploads->init(
            $course,
            $request->user(),
            $validated['original_name'],
            (int) $validated['total_size'],
            $validated['mime'] ?? null,
            $validated['fingerprint'] ?? null,
        );

        return response()->json([
            'data' => $this->uploads->statusPayload($session),
            'message' => count($session->receivedChunkIndexes()) > 0
                ? 'تم استئناف رفع سابق.'
                : 'تم بدء جلسة الرفع.',
        ]);
    }

    public function status(Request $request, Course $course, UploadSession $upload): JsonResponse
    {
        $this->assertSession($upload, $course, $request);

        return response()->json(['data' => $this->uploads->statusPayload($upload)]);
    }

    public function chunk(Request $request, Course $course, UploadSession $upload): JsonResponse
    {
        $this->assertSession($upload, $course, $request);

        $validated = $request->validate([
            'index' => ['required', 'integer', 'min:0'],
            'chunk' => ['required', 'file', 'max:5120'], // 5MB max per chunk request
        ], [
            'chunk.required' => 'جزء الملف مطلوب.',
        ]);

        $session = $this->uploads->storeChunk(
            $upload,
            (int) $validated['index'],
            $validated['chunk']
        );

        return response()->json([
            'data' => $this->uploads->statusPayload($session),
        ]);
    }

    public function complete(Request $request, Course $course, UploadSession $upload): JsonResponse
    {
        $this->assertSession($upload, $course, $request);

        $course = $this->uploads->assemble($upload, $course);

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'course.intro_video.uploaded', Course::class, $course->id, [
                'name' => $course->intro_video_original_name,
                'size' => $course->intro_video_size,
            ]);
        }

        return response()->json([
            'data' => [
                'course_id' => $course->id,
                'original_name' => $course->intro_video_original_name,
                'size' => $course->intro_video_size,
                'mime' => $course->intro_video_mime,
                'stream_url' => route(\App\Support\CoursePanel::fromRequest($request).'.courses.intro-video.stream', $course),
            ],
            'message' => 'تم رفع الفيديو التوضيحي بنجاح.',
        ]);
    }

    public function destroy(Request $request, Course $course): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $this->uploads->deleteIntroVideo($course);

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'course.intro_video.deleted', Course::class, $course->id);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'تم حذف الفيديو التوضيحي.']);
        }

        $panel = \App\Support\CoursePanel::fromRequest($request);

        return redirect()
            ->route($panel.'.courses.show', ['course' => $course, 'tab' => 'general'])
            ->with('status', 'تم حذف الفيديو التوضيحي.');
    }

    public function stream(Request $request, Course $course): StreamedResponse|Response
    {
        abort_unless($course->intro_video_path, 404);

        $disk = Storage::disk($course->intro_video_disk ?: 'local_private');
        abort_unless($disk->exists($course->intro_video_path), 404);

        $path = $disk->path($course->intro_video_path);
        $mime = $course->intro_video_mime ?: 'video/mp4';
        $size = filesize($path) ?: (int) $course->intro_video_size;
        $start = 0;
        $end = $size - 1;
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
        $headers = [
            'Content-Type' => $mime,
            'Accept-Ranges' => 'bytes',
            'Content-Length' => $length,
            'Content-Disposition' => 'inline; filename="'.addslashes($course->intro_video_original_name ?: 'intro.mp4').'"',
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

    private function assertSession(UploadSession $upload, Course $course, Request $request): void
    {
        abort_unless($upload->course_id === $course->id, 404);
        abort_unless($upload->user_id === $request->user()->id, 403);
        abort_unless($upload->purpose === 'course_intro_video', 404);
    }
}
