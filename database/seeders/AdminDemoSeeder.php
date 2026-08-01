<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Category;
use App\Models\Course;
use App\Models\Group;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminDemoSeeder extends Seeder
{
    public function run(): void
    {
        $year = AcademicYear::query()->updateOrCreate(
            ['name' => '2025/2026'],
            [
                'starts_on' => '2025-09-01',
                'ends_on' => '2026-06-30',
                'is_current' => true,
            ]
        );

        $semester = Semester::query()->updateOrCreate(
            ['academic_year_id' => $year->id, 'name' => 'الفصل الأول'],
            [
                'term_number' => 1,
                'starts_on' => '2025-09-01',
                'ends_on' => '2026-01-15',
                'is_current' => true,
            ]
        );

        $category = Category::query()->updateOrCreate(
            ['slug' => 'medicine'],
            [
                'name' => 'طب',
                'description' => 'مواد طبية',
                'status' => 'active',
                'position' => 1,
            ]
        );

        $group = Group::query()->updateOrCreate(
            ['name' => 'فرقة أ'],
            [
                'academic_year_id' => $year->id,
                'semester_id' => $semester->id,
                'status' => 'active',
            ]
        );

        Course::query()->whereNull('category_id')->limit(5)->update([
            'category_id' => $category->id,
            'academic_year_id' => $year->id,
            'semester_id' => $semester->id,
        ]);

        $student = User::query()
            ->whereHas('roles', fn ($q) => $q->where('slug', 'student'))
            ->first();

        if ($student) {
            $student->update([
                'academic_year_id' => $year->id,
                'semester_id' => $semester->id,
                'group_id' => $group->id,
            ]);
            $group->users()->syncWithoutDetaching([$student->id]);

            $course = Course::query()->first();
            if ($course && ! Order::query()->where('user_id', $student->id)->exists()) {
                $order = Order::query()->create([
                    'number' => 'ORD-'.Str::upper(Str::random(8)),
                    'user_id' => $student->id,
                    'total' => $course->price ?: 500,
                    'currency' => 'EGP',
                    'status' => 'pending',
                ]);
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'course_id' => $course->id,
                    'title' => $course->title,
                    'unit_price' => $course->price ?: 500,
                    'quantity' => 1,
                ]);
            }
        }
    }
}
