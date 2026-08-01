<div class="grid gap-4 sm:grid-cols-3">
    <x-admin.kpi-card label="عدد الدروس" :value="$course->lessons_count" />
    <x-admin.kpi-card label="عدد الطلاب" :value="$course->enrollments_count" />
    <x-admin.kpi-card label="عدد الاختبارات" :value="$course->quizzes->count()" />
</div>
<x-admin.chart-shell title="تحليلات المقرر" id="course-analytics-chart" class="mt-4">
    <p class="py-8 text-center text-sm text-slate-500">رسوم بيانية تفصيلية ستتوفر في تقارير الإدارة.</p>
</x-admin.chart-shell>
