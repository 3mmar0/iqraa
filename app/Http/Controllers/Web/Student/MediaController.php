<?php

namespace App\Http\Controllers\Web\Student;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use App\Policies\EnrollmentPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaController extends Controller
{
    public function show(Request $request, MediaAsset $asset): StreamedResponse
    {
        $asset->load('lesson.course');
        $course = $asset->lesson->course;

        abort_unless(
            app(EnrollmentPolicy::class)->viewMedia($request->user(), $course),
            403,
            'غير مصرح بتشغيل هذا الملف.'
        );

        $disk = Storage::disk($asset->disk ?: 'local_private');
        abort_unless($disk->exists($asset->path), 404);

        return $disk->response($asset->path, $asset->original_name, [
            'Content-Type' => $asset->mime ?: 'application/octet-stream',
        ]);
    }
}
