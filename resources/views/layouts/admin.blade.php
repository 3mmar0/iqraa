@php
    $dashboardLabel = 'لوحة الإدارة';
    $dashboardHome = 'admin.home';
    $dashboardNav = [
        ['label' => 'نظرة عامة', 'route' => 'admin.home', 'match' => 'admin.home', 'icon' => 'home'],
        ['label' => 'الطلاب', 'route' => 'admin.users.index', 'params' => ['type' => 'students'], 'match' => 'admin.users.*', 'query' => ['type' => 'students'], 'icon' => 'student'],
        ['label' => 'فريق العمل', 'route' => 'admin.users.index', 'params' => ['type' => 'staff'], 'match' => 'admin.users.*', 'query' => ['type' => 'staff'], 'icon' => 'staff'],
        ['label' => 'المقررات', 'route' => 'admin.courses.index', 'match' => 'admin.courses.*', 'icon' => 'book'],
        ['label' => 'الدروس', 'route' => 'admin.lessons.index', 'match' => 'admin.lessons.*', 'icon' => 'lesson'],
        ['label' => 'طلبات الالتحاق', 'route' => 'admin.enrollment-requests.index', 'match' => 'admin.enrollment-requests.*', 'icon' => 'inbox'],
        ['label' => 'المدفوعات', 'route' => 'admin.payments.index', 'match' => 'admin.payments.*', 'icon' => 'payment'],
        ['label' => 'الأدوار والصلاحيات', 'route' => 'admin.roles.index', 'match' => 'admin.roles.*', 'icon' => 'shield'],
        ['label' => 'سجل التدقيق', 'route' => 'admin.audit-logs.index', 'match' => 'admin.audit-logs.*', 'icon' => 'scroll'],
        ['label' => 'التشغيل', 'route' => 'admin.ops.index', 'match' => 'admin.ops.*', 'icon' => 'cpu'],
        ['label' => 'الإشعارات', 'route' => 'admin.comms.index', 'match' => 'admin.comms.*', 'icon' => 'bell'],
        ['label' => 'الأمان', 'route' => 'admin.security.index', 'match' => 'admin.security.*', 'icon' => 'lock'],
    ];
@endphp
@extends('layouts.dashboard')
