<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Coupon;
use App\Models\Lead;
use App\Models\Referral;
use Illuminate\View\View;

class MarketingOverviewController extends Controller
{
    public function index(): View
    {
        $stats = [
            'campaigns' => Campaign::query()->count(),
            'active_campaigns' => Campaign::query()->where('status', 'active')->count(),
            'coupons' => Coupon::query()->count(),
            'active_coupons' => Coupon::query()->where('active', true)->count(),
            'leads' => Lead::query()->count(),
            'referrals' => Referral::query()->count(),
        ];

        return view('admin.marketing.index', compact('stats'));
    }
}
