@php
    $dashboardLabel = 'لوحة الإدارة';
    $dashboardHome = 'admin.home';
    $dashboardTheme = 'admin';
    $dashboardNav = [
        [
            'section' => null,
            'items' => [
                ['label' => 'لوحة التحكم', 'route' => 'admin.home', 'match' => 'admin.home', 'icon' => 'home'],
            ],
        ],
        [
            'section' => 'المستخدمون',
            'items' => [
                ['label' => 'الطلاب', 'route' => 'admin.students.index', 'match' => 'admin.students.*', 'icon' => 'student'],
                ['label' => 'المعلمون', 'route' => 'admin.teachers.index', 'match' => 'admin.teachers.*', 'icon' => 'staff'],
                ['label' => 'طلبات الالتحاق', 'route' => 'admin.enrollment-requests.index', 'match' => 'admin.enrollment-requests.*', 'icon' => 'inbox'],
            ],
        ],
        [
            'section' => 'المحتوى التعليمي',
            'items' => [
                ['label' => 'المقررات', 'route' => 'admin.courses.index', 'match' => 'admin.courses.*', 'icon' => 'book'],
                ['label' => 'التصنيفات', 'route' => 'admin.categories.index', 'match' => 'admin.categories.*', 'icon' => 'category'],
                ['label' => 'الدروس', 'route' => 'admin.lessons.index', 'match' => 'admin.lessons.*', 'icon' => 'lesson'],
                ['label' => 'الاختبارات', 'route' => 'admin.quizzes.index', 'match' => 'admin.quizzes.*', 'icon' => 'quiz'],
                ['label' => 'الواجبات', 'route' => 'admin.assignments.index', 'match' => 'admin.assignments.*', 'icon' => 'assignment'],
            ],
        ],
        [
            'section' => 'الهيكل الأكاديمي',
            'items' => [
                ['label' => 'السنوات الدراسية', 'route' => 'admin.academic-years.index', 'match' => 'admin.academic-years.*', 'icon' => 'calendar'],
                ['label' => 'الفصول الدراسية', 'route' => 'admin.semesters.index', 'match' => 'admin.semesters.*', 'icon' => 'semester'],
                ['label' => 'المجموعات', 'route' => 'admin.groups.index', 'match' => 'admin.groups.*', 'icon' => 'group'],
            ],
        ],
        [
            'section' => 'المبيعات والمدفوعات',
            'items' => [
                ['label' => 'الطلبات', 'route' => 'admin.orders.index', 'match' => 'admin.orders.*', 'icon' => 'order'],
                ['label' => 'المدفوعات', 'route' => 'admin.payments.index', 'match' => 'admin.payments.*', 'icon' => 'payment'],
                ['label' => 'كوبونات الخصم', 'route' => 'admin.coupons.index', 'match' => 'admin.coupons.*', 'icon' => 'coupon'],
                ['label' => 'المالية', 'route' => 'admin.finance.index', 'match' => 'admin.finance.*', 'icon' => 'finance'],
            ],
        ],
        [
            'section' => 'التواصل والنمو',
            'items' => [
                ['label' => 'الإعلانات', 'route' => 'admin.announcements.index', 'match' => 'admin.announcements.*', 'icon' => 'megaphone'],
                ['label' => 'تيليجرام', 'route' => 'admin.telegram.index', 'match' => 'admin.telegram.*', 'icon' => 'telegram'],
                ['label' => 'التسويق', 'route' => 'admin.marketing.index', 'match' => 'admin.marketing.*', 'icon' => 'marketing'],
                ['label' => 'الدعم الفني', 'route' => 'admin.support.index', 'match' => 'admin.support.*', 'icon' => 'ticket'],
                ['label' => 'فريق العمل', 'route' => 'admin.team.index', 'match' => 'admin.team.*', 'icon' => 'team'],
            ],
        ],
        [
            'section' => 'التقارير',
            'items' => [
                ['label' => 'التقارير', 'route' => 'admin.reports.index', 'match' => 'admin.reports.*', 'icon' => 'chart'],
            ],
        ],
        [
            'section' => 'النظام',
            'items' => [
                ['label' => 'الإعدادات', 'route' => 'admin.settings.index', 'match' => 'admin.settings.*', 'icon' => 'settings'],
                ['label' => 'الأدوار والصلاحيات', 'route' => 'admin.roles.index', 'match' => 'admin.roles.*', 'icon' => 'shield'],
                ['label' => 'سجلات النظام', 'route' => 'admin.system-logs.index', 'match' => 'admin.system-logs.*', 'icon' => 'scroll'],
                ['label' => 'التشغيل', 'route' => 'admin.ops.index', 'match' => 'admin.ops.*', 'icon' => 'cpu'],
                ['label' => 'الأمان', 'route' => 'admin.security.index', 'match' => 'admin.security.*', 'icon' => 'lock'],
                ['label' => 'الإشعارات', 'route' => 'admin.comms.index', 'match' => 'admin.comms.*', 'icon' => 'bell'],
            ],
        ],
    ];
@endphp
@extends('layouts.dashboard')
