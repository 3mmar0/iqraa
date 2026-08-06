@php
    $dashboardLabel = 'لوحة المحاضر';
    $dashboardHome = 'instructor.home';
    $dashboardTheme = 'instructor';
    $dashboardNav = [
        [
            'section' => 'التدريس',
            'items' => [
                ['label' => 'الرئيسية', 'route' => 'instructor.home', 'match' => 'instructor.home', 'icon' => 'home'],
                ['label' => 'لوحة الأداء', 'route' => 'instructor.dashboard', 'match' => 'instructor.dashboard', 'icon' => 'chart'],
                ['label' => 'المقررات', 'route' => 'instructor.courses.index', 'match' => 'instructor.courses.*', 'icon' => 'book'],
                ['label' => 'الطلاب', 'route' => 'instructor.students.index', 'match' => 'instructor.students.*', 'icon' => 'student'],
            ],
        ],
        [
            'section' => 'التقييم والتواصل',
            'items' => [
                ['label' => 'الواجبات', 'route' => 'instructor.assignments.index', 'match' => 'instructor.assignments.*', 'icon' => 'clipboard'],
                ['label' => 'الإعلانات', 'route' => 'instructor.announcements.index', 'match' => 'instructor.announcements.*', 'icon' => 'megaphone'],
                ['label' => 'الرسائل', 'route' => 'instructor.messages.index', 'match' => 'instructor.messages.*', 'icon' => 'message'],
            ],
        ],
        [
            'section' => 'الجدول والتحليل',
            'items' => [
                ['label' => 'الجلسات المباشرة', 'route' => 'instructor.live-sessions.index', 'match' => 'instructor.live-sessions.*', 'icon' => 'bell'],
                ['label' => 'التقويم', 'route' => 'instructor.calendar.index', 'match' => 'instructor.calendar.*', 'icon' => 'clipboard'],
                ['label' => 'التقارير', 'route' => 'instructor.reports.index', 'match' => 'instructor.reports.*', 'icon' => 'scroll'],
                ['label' => 'الإعدادات', 'route' => 'instructor.settings.index', 'match' => 'instructor.settings.*', 'icon' => 'lock'],
            ],
        ],
    ];
@endphp
@extends('layouts.dashboard')
