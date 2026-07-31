<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;

class LearningDemoSeeder extends Seeder
{
    public function run(): void
    {
        $instructor = User::query()->where('email', 'instructor@example.com')->first();
        $student = User::query()->where('email', 'student@example.com')->first();

        $course = Course::query()->updateOrCreate(
            ['title' => 'مقدمة في البرمجة'],
            [
                'description' => 'مقرر تجريبي لتعلم أساسيات البرمجة.',
                'instructor_user_id' => $instructor?->id,
                'hours' => 12,
                'status' => 'published',
                'schedule_text' => 'أحد وثلاثاء 6 مساءً',
                'term_label' => 'ترم تجريبي 2026',
            ]
        );

        $lesson1 = Lesson::query()->updateOrCreate(
            ['course_id' => $course->id, 'title' => 'الدرس الأول: البداية'],
            ['description' => 'تعارف على المقرر', 'position' => 1, 'status' => 'published']
        );

        Lesson::query()->updateOrCreate(
            ['course_id' => $course->id, 'title' => 'الدرس الثاني: المتغيرات'],
            ['description' => 'شرح المتغيرات', 'position' => 2, 'status' => 'published']
        );

        $quiz = Quiz::query()->updateOrCreate(
            ['course_id' => $course->id, 'title' => 'اختبار قصير 1'],
            ['duration_minutes' => 15, 'status' => 'published', 'show_correct_answers' => true]
        );

        $question = Question::query()->updateOrCreate(
            ['quiz_id' => $quiz->id, 'body' => 'ما هو ناتج 2+2؟'],
            ['type' => 'single', 'position' => 1, 'points' => 1]
        );

        QuestionOption::query()->where('question_id', $question->id)->delete();
        QuestionOption::query()->create(['question_id' => $question->id, 'body' => '3', 'is_correct' => false]);
        QuestionOption::query()->create(['question_id' => $question->id, 'body' => '4', 'is_correct' => true]);
        QuestionOption::query()->create(['question_id' => $question->id, 'body' => '5', 'is_correct' => false]);

        if ($student) {
            Enrollment::query()->updateOrCreate(
                ['user_id' => $student->id, 'course_id' => $course->id],
                [
                    'status' => 'active',
                    'source' => 'admin_grant',
                    'enrolled_at' => now(),
                ]
            );
        }

        unset($lesson1);
    }
}
