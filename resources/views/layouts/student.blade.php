@php
    $dashboardLabel = 'لوحة الطالب';
    $dashboardHome = 'student.home';
    $dashboardTheme = 'student';
    $dashboardNav = [
        ['label' => 'الرئيسية', 'route' => 'student.home', 'match' => 'student.home', 'icon' => 'home'],
        ['label' => 'مقرراتي', 'route' => 'student.courses.index', 'match' => 'student.courses.*', 'icon' => 'book'],
        ['label' => 'طلبات الالتحاق', 'route' => 'student.course-requests.index', 'match' => 'student.course-requests.*', 'icon' => 'inbox'],
        ['label' => 'تقدمي', 'route' => 'student.progress', 'match' => 'student.progress', 'icon' => 'chart'],
        ['label' => 'إنجازاتي', 'route' => 'student.achievements', 'match' => 'student.achievements', 'icon' => 'shield'],
        ['label' => 'التقويم', 'route' => 'student.calendar', 'match' => 'student.calendar', 'icon' => 'clipboard'],
        ['label' => 'الإشعارات', 'route' => 'student.notifications', 'match' => 'student.notifications', 'icon' => 'bell'],
        ['label' => 'الدعم', 'route' => 'student.support.index', 'match' => 'student.support.*', 'icon' => 'message'],
        ['label' => 'الملف الشخصي', 'route' => 'student.profile.edit', 'match' => 'student.profile.*', 'icon' => 'users'],
        ['label' => 'الإعدادات', 'route' => 'student.settings.edit', 'match' => 'student.settings.*', 'icon' => 'lock'],
    ];
@endphp
@extends('layouts.dashboard')
