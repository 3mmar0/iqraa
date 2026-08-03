<?php

namespace Tests\Feature\Admin;

use App\Models\AttemptAnswer;
use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseQuizQuestionsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Course $course;

    private Quiz $quiz;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::query()->create([
            'slug' => 'super_admin',
            'name_ar' => 'مدير النظام',
            'dashboard_key' => 'admin',
        ]);

        $this->admin = User::factory()->create(['status' => 'active']);
        $this->admin->roles()->attach($role->id);

        $this->course = Course::query()->create([
            'title' => 'مقرر الاختبار',
            'status' => 'published',
        ]);

        $this->quiz = Quiz::query()->create([
            'course_id' => $this->course->id,
            'title' => 'اختبار الوحدة',
            'duration_minutes' => 30,
            'status' => 'draft',
            'show_correct_answers' => true,
        ]);
    }

    public function test_admin_can_store_single_question_and_return_to_course_tab(): void
    {
        $response = $this->actingAs($this->admin)->post(
            route('admin.quizzes.questions.store', $this->quiz),
            [
                'type' => 'single',
                'body' => 'ما هي عاصمة مصر؟',
                'points' => 2,
                'options' => [
                    ['body' => 'القاهرة', 'is_correct' => '1'],
                    ['body' => 'الإسكندرية', 'is_correct' => '0'],
                ],
                'return_to' => 'course',
                'return_course_id' => $this->course->id,
                'return_tab' => 'quizzes',
            ]
        );

        $response->assertRedirect(route('admin.courses.show', [
            'course' => $this->course->id,
            'tab' => 'quizzes',
        ]));

        $this->assertDatabaseHas('questions', [
            'quiz_id' => $this->quiz->id,
            'body' => 'ما هي عاصمة مصر؟',
            'type' => 'single',
            'points' => 2,
        ]);

        $question = Question::query()->where('quiz_id', $this->quiz->id)->first();
        $this->assertNotNull($question);
        $this->assertSame(2, $question->options()->count());
        $this->assertSame(1, $question->options()->where('is_correct', true)->count());
    }

    public function test_single_question_requires_exactly_one_correct_option(): void
    {
        $response = $this->actingAs($this->admin)->from(route('admin.courses.show', [
            'course' => $this->course->id,
            'tab' => 'quizzes',
        ]))->post(route('admin.quizzes.questions.store', $this->quiz), [
            'type' => 'single',
            'body' => 'سؤال ناقص',
            'points' => 1,
            'options' => [
                ['body' => 'أ', 'is_correct' => '1'],
                ['body' => 'ب', 'is_correct' => '1'],
            ],
        ]);

        $response->assertSessionHasErrors('options');
        $this->assertDatabaseCount('questions', 0);
    }

    public function test_text_question_stores_without_options(): void
    {
        $this->actingAs($this->admin)->post(route('admin.quizzes.questions.store', $this->quiz), [
            'type' => 'text',
            'body' => 'اشرح المفهوم',
            'points' => 5,
            'return_to' => 'course',
            'return_course_id' => $this->course->id,
            'return_tab' => 'quizzes',
        ])->assertRedirect();

        $question = Question::query()->where('body', 'اشرح المفهوم')->first();
        $this->assertNotNull($question);
        $this->assertSame('text', $question->type);
        $this->assertSame(0, $question->options()->count());
    }

    public function test_publish_blocked_when_quiz_has_no_questions(): void
    {
        $response = $this->actingAs($this->admin)->from(route('admin.courses.show', [
            'course' => $this->course->id,
            'tab' => 'quizzes',
        ]))->post(route('admin.quizzes.publish', $this->quiz));

        $response->assertSessionHasErrors('quiz');
        $this->assertSame('draft', $this->quiz->fresh()->status);
    }

    public function test_publish_succeeds_with_at_least_one_question(): void
    {
        Question::query()->create([
            'quiz_id' => $this->quiz->id,
            'type' => 'text',
            'body' => 'سؤال',
            'position' => 1,
            'points' => 1,
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.quizzes.publish', $this->quiz))
            ->assertRedirect();

        $this->assertSame('published', $this->quiz->fresh()->status);
    }

    public function test_cannot_delete_question_with_attempt_answers(): void
    {
        $question = Question::query()->create([
            'quiz_id' => $this->quiz->id,
            'type' => 'text',
            'body' => 'محمي',
            'position' => 1,
            'points' => 1,
        ]);

        $student = User::factory()->create();
        $attempt = QuizAttempt::query()->create([
            'user_id' => $student->id,
            'quiz_id' => $this->quiz->id,
            'status' => 'submitted',
            'started_at' => now(),
            'submitted_at' => now(),
        ]);

        AttemptAnswer::query()->create([
            'quiz_attempt_id' => $attempt->id,
            'question_id' => $question->id,
            'selected' => ['answer' => 'x'],
            'is_correct' => false,
        ]);

        $this->actingAs($this->admin)->delete(route('admin.quizzes.questions.destroy', [$this->quiz, $question]), [
            'return_to' => 'course',
            'return_course_id' => $this->course->id,
            'return_tab' => 'quizzes',
        ])->assertRedirect(route('admin.courses.show', [
            'course' => $this->course->id,
            'tab' => 'quizzes',
        ]));

        $this->assertDatabaseHas('questions', ['id' => $question->id]);
    }

    public function test_reorder_updates_positions(): void
    {
        $q1 = Question::query()->create([
            'quiz_id' => $this->quiz->id,
            'type' => 'text',
            'body' => 'الأول',
            'position' => 1,
            'points' => 1,
        ]);
        $q2 = Question::query()->create([
            'quiz_id' => $this->quiz->id,
            'type' => 'text',
            'body' => 'الثاني',
            'position' => 2,
            'points' => 1,
        ]);

        $this->actingAs($this->admin)->post(route('admin.quizzes.questions.reorder', $this->quiz), [
            'question_ids' => [$q2->id, $q1->id],
            'return_to' => 'course',
            'return_course_id' => $this->course->id,
            'return_tab' => 'quizzes',
        ])->assertRedirect();

        $this->assertSame(1, $q2->fresh()->position);
        $this->assertSame(2, $q1->fresh()->position);
    }

    public function test_course_quizzes_tab_includes_question_manager_markers(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.courses.show', [
            'course' => $this->course->id,
            'tab' => 'quizzes',
        ]));

        $response->assertOk();
        $response->assertSee('الأسئلة', false);
        $response->assertSee('إدارة الأسئلة', false);
        $response->assertSee('تفاصيل إضافية', false);
    }
}
