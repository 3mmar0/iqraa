@php
    $dashboardLabel = 'لوحة الدعم';
    $dashboardHome = 'support.home';
    $dashboardNav = [
        ['label' => 'الرئيسية', 'route' => 'support.home', 'match' => 'support.home', 'icon' => 'home'],
        ['label' => 'التذاكر', 'route' => 'support.tickets.index', 'match' => 'support.tickets.*', 'icon' => 'ticket'],
        ['label' => 'المحادثة', 'route' => 'support.chat.index', 'match' => 'support.chat.*', 'icon' => 'message'],
        ['label' => 'الطلاب', 'route' => 'support.students.index', 'match' => 'support.students.*', 'icon' => 'student'],
        ['label' => 'الأسئلة الشائعة', 'route' => 'support.faq.index', 'match' => 'support.faq.*', 'icon' => 'book'],
        ['label' => 'التقارير', 'route' => 'support.reports.index', 'match' => 'support.reports.*', 'icon' => 'chart'],
    ];
@endphp
@extends('layouts.dashboard')
