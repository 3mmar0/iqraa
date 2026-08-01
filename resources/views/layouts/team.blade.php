@php
    $dashboardLabel = 'لوحة الفريق';
    $dashboardHome = 'team.home';
    $dashboardNav = [
        ['label' => 'الرئيسية', 'route' => 'team.home', 'match' => 'team.home', 'icon' => 'home'],
        ['label' => 'المهام', 'route' => 'team.tasks.index', 'match' => 'team.tasks.*', 'icon' => 'clipboard'],
        ['label' => 'الإعلانات', 'route' => 'team.announcements.index', 'match' => 'team.announcements.*', 'icon' => 'megaphone'],
        ['label' => 'الملفات', 'route' => 'team.files.index', 'match' => 'team.files.*', 'icon' => 'book'],
        ['label' => 'الاجتماعات', 'route' => 'team.meetings.index', 'match' => 'team.meetings.*', 'icon' => 'users'],
        ['label' => 'الأهداف', 'route' => 'team.goals.index', 'match' => 'team.goals.*', 'icon' => 'chart'],
        ['label' => 'الحضور', 'route' => 'team.attendance.index', 'match' => 'team.attendance.*', 'icon' => 'scroll'],
        ['label' => 'التقارير', 'route' => 'team.reports.index', 'match' => 'team.reports.*', 'icon' => 'lesson'],
    ];
@endphp
@extends('layouts.dashboard')
