<?php

namespace App\Http\Controllers\Web\Student;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use App\Policies\EnrollmentPolicy;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaController extends Controller
{
    public function show(Request $request, MediaAsset $asset): StreamedResponse|Response
    {
        $asset->load('lesson.course');
        $course = $asset->lesson?->course;
        abort_unless($course, 404);

        abort_unless(
            app(EnrollmentPolicy::class)->viewMedia($request->user(), $course),
            403,
            'غير مصرح بتشغيل هذا الملف.'
        );

        $disk = Storage::disk($asset->disk ?: 'local_private');
        abort_unless($disk->exists($asset->path), 404);

        $isVideo = $asset->type === 'video' || str_starts_with((string) $asset->mime, 'video/');

        // Videos are stream-only for enrolled students — never offered as attachment downloads.
        $wantDownload = $request->boolean('download') && ! $isVideo;

        $path = $disk->path($asset->path);
        $mime = $asset->mime ?: ($isVideo ? 'video/mp4' : 'application/octet-stream');
        $size = filesize($path) ?: (int) ($asset->size ?? 0);
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
        $filename = addslashes($asset->original_name ?: basename($asset->path));
        $disposition = $wantDownload
            ? 'attachment; filename="'.$filename.'"'
            : 'inline; filename="'.$filename.'"';

        $headers = [
            'Content-Type' => $mime,
            'Accept-Ranges' => 'bytes',
            'Content-Length' => $length,
            'Content-Disposition' => $disposition,
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ];

        if ($isVideo) {
            $headers['Content-Disposition'] = 'inline';
        }

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
}
