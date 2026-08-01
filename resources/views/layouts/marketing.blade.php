@php
    $dashboardLabel = 'لوحة التسويق';
    $dashboardHome = 'marketing.home';
    $dashboardNav = [
        ['label' => 'الرئيسية', 'route' => 'marketing.home', 'match' => 'marketing.home', 'icon' => 'home'],
        ['label' => 'الحملات', 'route' => 'marketing.campaigns.index', 'match' => 'marketing.campaigns.*', 'icon' => 'megaphone'],
        ['label' => 'القسائم', 'route' => 'marketing.coupons.index', 'match' => 'marketing.coupons.*', 'icon' => 'ticket'],
        ['label' => 'الإحالات', 'route' => 'marketing.referrals.index', 'match' => 'marketing.referrals.*', 'icon' => 'users'],
        ['label' => 'السفراء', 'route' => 'marketing.ambassadors.index', 'match' => 'marketing.ambassadors.*', 'icon' => 'staff'],
        ['label' => 'العملاء المحتملون', 'route' => 'marketing.leads.index', 'match' => 'marketing.leads.*', 'icon' => 'inbox'],
        ['label' => 'التحويلات', 'route' => 'marketing.conversions.index', 'match' => 'marketing.conversions.*', 'icon' => 'chart'],
        ['label' => 'التحليلات', 'route' => 'marketing.analytics.index', 'match' => 'marketing.analytics.*', 'icon' => 'chart'],
    ];
@endphp
@extends('layouts.dashboard')
