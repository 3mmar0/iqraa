<?php

namespace Modules\Catalog\Services;

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\User;
use App\Services\AuditLogger;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LessonAdminService
{
    public function __construct(private readonly AuditLogger $audit)
    {
    }

    public function lock(Lesson $lesson, ?User $actor = null): Lesson
    {
        $lesson->update(['is_locked' => true]);
        $this->audit->log($actor, 'lesson.locked', Lesson::class, $lesson->id);

        return $lesson;
    }

    public function unlock(Lesson $lesson, ?User $actor = null): Lesson
    {
        $lesson->update(['is_locked' => false]);
        $this->audit->log($actor, 'lesson.unlocked', Lesson::class, $lesson->id);

        return $lesson;
    }

    public function duplicate(Lesson $lesson, ?User $actor = null): Lesson
    {
        return DB::transaction(function () use ($lesson, $actor) {
            $copy = $lesson->replicate(['published_at']);
            $copy->title = $lesson->title.' (نسخة)';
            $copy->position = ((int) $lesson->course->lessons()->max('position')) + 1;
            $copy->status = 'draft';
            $copy->is_locked = false;
            $copy->published_at = null;
            $copy->quiz_id = null;
            $copy->save();

            $this->audit->log($actor, 'lesson.duplicated', Lesson::class, $copy->id, [
                'source_id' => $lesson->id,
            ]);

            return $copy;
        });
    }

    /** @param  list<int>  $orderedIds */
    public function reorder(int $courseId, array $orderedIds, ?User $actor = null): void
    {
        DB::transaction(function () use ($courseId, $orderedIds, $actor) {
            foreach ($orderedIds as $position => $lessonId) {
                Lesson::query()
                    ->where('course_id', $courseId)
                    ->where('id', $lessonId)
                    ->update(['position' => $position + 1]);
            }

            $this->audit->log($actor, 'lesson.reordered', Lesson::class, null, [
                'course_id' => $courseId,
                'order' => $orderedIds,
            ]);
        });
    }

    public function schedulePublish(Lesson $lesson, Carbon $publishedAt, ?User $actor = null): Lesson
    {
        $lesson->update([
            'published_at' => $publishedAt,
            'status' => $publishedAt->isPast() ? 'published' : 'scheduled',
        ]);

        $this->audit->log($actor, 'lesson.schedule_publish', Lesson::class, $lesson->id, [
            'published_at' => $publishedAt->toIso8601String(),
        ]);

        return $lesson;
    }

    public function attachQuiz(Lesson $lesson, Quiz $quiz, ?User $actor = null): Lesson
    {
        $lesson->update(['quiz_id' => $quiz->id]);
        $this->audit->log($actor, 'lesson.quiz_attached', Lesson::class, $lesson->id, [
            'quiz_id' => $quiz->id,
        ]);

        return $lesson->fresh('quiz');
    }
}
