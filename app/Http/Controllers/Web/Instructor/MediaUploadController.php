<?php

namespace App\Http\Controllers\Web\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\MediaAsset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MediaUploadController extends Controller
{
    public function store(Request $request, Lesson $lesson): RedirectResponse
    {
        $this->authorize('update', $lesson->course);

        $validated = $request->validate([
            'file' => ['required', 'file', 'max:51200'],
            'type' => ['nullable', 'string', 'max:50'],
        ]);

        $file = $validated['file'];
        $path = $file->store('lessons/'.$lesson->id, 'local_private');

        MediaAsset::query()->create([
            'lesson_id' => $lesson->id,
            'type' => $validated['type'] ?? 'file',
            'disk' => 'local_private',
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        return back()->with('status', 'تم رفع الملف.');
    }
}