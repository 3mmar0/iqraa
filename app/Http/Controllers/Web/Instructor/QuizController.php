<?php

namespace App\Http\Controllers\Web\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Teaching\Services\CourseAuthoringService;

class QuizController extends Controller
{
    public function __construct(private CourseAuthoringService $authoring)
    {
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'question_body' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($course, $validated) {
            $quiz = $this->authoring->createQuiz($course, [
                'title' => $validated['title'],
                'duration_minutes' => $validated['duration_minutes'] ?? null,
            ]);

            Question::query()->create([
                'quiz_id' => $quiz->id,
                'type' => 'short_text',
                'body' => $validated['question_body'] ?? 'سؤال تجريبي',
                'position' => 1,
                'points' => 1,
            ]);
        });

        return back()->with('status', 'تم إنشاء الاختبار مع سؤال أولي.');
    }
}