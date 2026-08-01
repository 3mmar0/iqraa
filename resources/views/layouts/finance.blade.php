@php
    $dashboardLabel = 'لوحة المالية';
    $dashboardHome = 'finance.home';
    $dashboardTheme = 'finance';
    $dashboardNav = [
        ['label' => 'الرئيسية', 'route' => 'finance.home', 'match' => 'finance.home', 'icon' => 'home'],
        ['label' => 'المعاملات', 'route' => 'finance.transactions.index', 'match' => 'finance.transactions.*', 'icon' => 'payment'],
        ['label' => 'الاشتراكات', 'route' => 'finance.subscriptions.index', 'match' => 'finance.subscriptions.*', 'icon' => 'clipboard'],
        ['label' => 'الاستردادات', 'route' => 'finance.refunds.index', 'match' => 'finance.refunds.*', 'icon' => 'inbox'],
        ['label' => 'المصروفات', 'route' => 'finance.expenses.index', 'match' => 'finance.expenses.*', 'icon' => 'scroll'],
        ['label' => 'الرواتب', 'route' => 'finance.payroll.index', 'match' => 'finance.payroll.*', 'icon' => 'users'],
        ['label' => 'الإيرادات', 'route' => 'finance.revenue.index', 'match' => 'finance.revenue.*', 'icon' => 'chart'],
        ['label' => 'الأرباح', 'route' => 'finance.profit.index', 'match' => 'finance.profit.*', 'icon' => 'chart'],
        ['label' => 'التوقعات', 'route' => 'finance.forecast.index', 'match' => 'finance.forecast.*', 'icon' => 'cpu'],
    ];
@endphp
@extends('layouts.dashboard')
