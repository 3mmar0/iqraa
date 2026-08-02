<?php

namespace App\Services;

use App\Models\Course;
use App\Models\UploadSession;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ResumableCourseVideoUploadService
{
    public const CHUNK_SIZE = 2 * 1024 * 1024; // 2MB

    public const MAX_SIZE = 2 * 1024 * 1024 * 1024; // 2GB

    public function init(
        Course $course,
        User $user,
        string $originalName,
        int $totalSize,
        ?string $mime,
        ?string $fingerprint = null,
    ): UploadSession {
        if ($totalSize <= 0 || $totalSize > self::MAX_SIZE) {
            throw new RuntimeException('حجم الفيديو غير صالح.');
        }

        if ($fingerprint) {
            $existing = UploadSession::query()
                ->where('course_id', $course->id)
                ->where('user_id', $user->id)
                ->where('client_fingerprint', $fingerprint)
                ->where('purpose', 'course_intro_video')
                ->where('status', 'pending')
                ->where('total_size', $totalSize)
                ->where('original_name', $originalName)
                ->latest()
                ->first();

            if ($existing) {
                $existing->received_chunks = $this->reconcileReceivedChunks($existing);

                return $existing;
            }
        }

        $chunkSize = self::CHUNK_SIZE;
        $totalChunks = (int) ceil($totalSize / $chunkSize);
        $tempKey = 'uploads/course-intro/'.$course->id.'/'.Str::uuid()->toString();

        Storage::disk('local_private')->makeDirectory($tempKey);

        return UploadSession::query()->create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'purpose' => 'course_intro_video',
            'original_name' => $originalName,
            'mime' => $mime,
            'total_size' => $totalSize,
            'chunk_size' => $chunkSize,
            'total_chunks' => $totalChunks,
            'received_chunks' => [],
            'status' => 'pending',
            'temp_key' => $tempKey,
            'client_fingerprint' => $fingerprint,
        ]);
    }

    public function storeChunk(UploadSession $session, int $index, UploadedFile $chunk): UploadSession
    {
        if ($session->status !== 'pending') {
            throw new RuntimeException('جلسة الرفع غير قابلة لاستقبال أجزاء.');
        }

        if ($index < 0 || $index >= $session->total_chunks) {
            throw new RuntimeException('رقم الجزء غير صالح.');
        }

        $disk = Storage::disk('local_private');
        $path = $session->temp_key.'/chunk_'.str_pad((string) $index, 6, '0', STR_PAD_LEFT);
        $disk->put($path, fopen($chunk->getRealPath(), 'r'));

        $session->markChunkReceived($index);

        return $session->fresh();
    }

    public function assemble(UploadSession $session, Course $course): Course
    {
        if (! $session->isComplete()) {
            throw new RuntimeException('لم تكتمل كل أجزاء الملف بعد.');
        }

        $session->update(['status' => 'assembling']);

        $disk = Storage::disk('local_private');
        $extension = pathinfo($session->original_name, PATHINFO_EXTENSION) ?: 'mp4';
        $finalRelative = 'courses/'.$course->id.'/intro_'.Str::uuid()->toString().'.'.$extension;
        $finalAbsolute = $disk->path($finalRelative);

        if (! is_dir(dirname($finalAbsolute))) {
            mkdir(dirname($finalAbsolute), 0755, true);
        }

        $out = fopen($finalAbsolute, 'wb');
        if ($out === false) {
            $session->update(['status' => 'failed']);
            throw new RuntimeException('تعذر إنشاء ملف الفيديو النهائي.');
        }

        try {
            for ($i = 0; $i < $session->total_chunks; $i++) {
                $chunkPath = $session->temp_key.'/chunk_'.str_pad((string) $i, 6, '0', STR_PAD_LEFT);
                if (! $disk->exists($chunkPath)) {
                    throw new RuntimeException('جزء مفقود رقم '.$i);
                }
                $in = fopen($disk->path($chunkPath), 'rb');
                if ($in === false) {
                    throw new RuntimeException('تعذر قراءة الجزء '.$i);
                }
                stream_copy_to_stream($in, $out);
                fclose($in);
            }
        } catch (\Throwable $e) {
            fclose($out);
            @unlink($finalAbsolute);
            $session->update(['status' => 'failed']);
            throw $e;
        }

        fclose($out);

        if ($course->intro_video_path) {
            $oldDisk = $course->intro_video_disk ?: 'local_private';
            Storage::disk($oldDisk)->delete($course->intro_video_path);
        }

        $course->update([
            'intro_video_path' => $finalRelative,
            'intro_video_disk' => 'local_private',
            'intro_video_original_name' => $session->original_name,
            'intro_video_mime' => $session->mime ?: 'video/mp4',
            'intro_video_size' => $session->total_size,
        ]);

        $this->cleanupTemp($session);
        $session->update(['status' => 'completed']);

        return $course->fresh();
    }

    public function deleteIntroVideo(Course $course): void
    {
        if ($course->intro_video_path) {
            Storage::disk($course->intro_video_disk ?: 'local_private')->delete($course->intro_video_path);
        }

        $course->update([
            'intro_video_path' => null,
            'intro_video_disk' => null,
            'intro_video_original_name' => null,
            'intro_video_mime' => null,
            'intro_video_size' => null,
        ]);
    }

    public function cleanupTemp(UploadSession $session): void
    {
        Storage::disk('local_private')->deleteDirectory($session->temp_key);
    }

    /** @return list<int> */
    private function reconcileReceivedChunks(UploadSession $session): array
    {
        $disk = Storage::disk('local_private');
        $valid = [];

        foreach ($session->receivedChunkIndexes() as $index) {
            $path = $session->temp_key.'/chunk_'.str_pad((string) $index, 6, '0', STR_PAD_LEFT);
            if ($disk->exists($path)) {
                $valid[] = $index;
            }
        }

        if ($valid !== $session->receivedChunkIndexes()) {
            $session->received_chunks = $valid;
            $session->save();
        }

        return $valid;
    }

    public function statusPayload(UploadSession $session): array
    {
        return [
            'upload_id' => $session->id,
            'status' => $session->status,
            'chunk_size' => $session->chunk_size,
            'total_chunks' => $session->total_chunks,
            'total_size' => $session->total_size,
            'received_chunks' => $session->receivedChunkIndexes(),
            'uploaded_bytes' => $session->uploadedBytes(),
            'progress' => $session->total_size > 0
                ? round(($session->uploadedBytes() / $session->total_size) * 100, 1)
                : 0,
        ];
    }
}
