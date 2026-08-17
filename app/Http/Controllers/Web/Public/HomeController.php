<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\FaqArticle;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $courses = Course::query()
            ->with(['instructor', 'category'])
            ->withCount(['lessons', 'enrollments'])
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        $courseCount = Course::query()->where('status', 'published')->count();

        $categories = Category::query()
            ->where('status', 'active')
            ->withCount(['courses' => fn ($q) => $q->where('status', 'published')])
            ->orderBy('position')
            ->orderBy('name')
            ->limit(4)
            ->get();

        $instructors = User::query()
            ->whereHas('roles', fn ($q) => $q->where('slug', 'instructor'))
            ->where('status', 'active')
            ->withCount([
                'instructedCourses as published_courses_count' => fn ($q) => $q->where('status', 'published'),
            ])
            ->orderBy('name')
            ->limit(3)
            ->get();

        $faqs = FaqArticle::query()
            ->where('published', true)
            ->orderBy('position')
            ->limit(4)
            ->get();

        if ($faqs->isEmpty()) {
            $faqs = collect([
                (object) ['title' => 'كيف أسجّل في مقرر؟', 'body' => 'أنشئ حساباً، اختر مقرراً من الكتالوج، ثم أرسل طلب التحاق ليراجعه الفريق.'],
                (object) ['title' => 'هل المحتوى بالعربية؟', 'body' => 'نعم. المنصة والواجهة بالكامل باللغة العربية واتجاه RTL.'],
                (object) ['title' => 'ماذا بعد الموافقة على الطلب؟', 'body' => 'يظهر المقرر في لوحة الطالب ويمكنك متابعة الدروس والتقدم فوراً.'],
            ]);
        }

        return view('public.home', compact('courses', 'courseCount', 'categories', 'instructors', 'faqs'));
    }
}
