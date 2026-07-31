<?php

namespace App\Http\Controllers\Web\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\SubmitQuizAttemptRequest;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Services\EnrollmentService;
use App\Services\QuizAttemptService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function __construct(
        private EnrollmentService $enrollments,
        private QuizAttemptService $attempts,
    ) {
    }

    public function show(Request $request, Quiz $quiz): View
    {
        abort_unless($this->enrollments->userHasActiveEnrollment($request->user(), $quiz->course_id), 403);
        $quiz->loadCount('questions');

        return view('student.quizzes.show', compact('quiz'));
    }

    public function start(Request $request, Quiz $quiz): RedirectResponse
    {
        abort_unless($this->enrollments->userHasActiveEnrollment($request->user(), $quiz->course_id), 403);
        $attempt = $this->attempts->start($request->user(), $quiz);

        return redirect()->route('student.quizzes.result', $attempt)
            ->with('status', 'بدأت المحاولة — أجب ثم أرسل.');
    }

    public function submit(SubmitQuizAttemptRequest $request, QuizAttempt $attempt): RedirectResponse
    {
        abort_unless($attempt->user_id === $request->user()->id, 403);
        $attempt->load('quiz');
        abort_unless($this->enrollments->userHasActiveEnrollment($request->user(), $attempt->quiz->course_id), 403);

        $this->attempts->submit($attempt, $request->validated('answers', []));

        return redirect()->route('student.quizzes.result', $attempt)->with('status', 'تم إرسال الاختبار.');
    }

    public function result(Request $request, QuizAttempt $attempt): View
    {
        abort_unless($attempt->user_id === $request->user()->id, 403);
        $attempt->load(['quiz.questions.options', 'answers']);

        return view('student.quizzes.result', compact('attempt'));
    }
}
