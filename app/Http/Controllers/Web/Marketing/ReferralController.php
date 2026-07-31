<?php

namespace App\Http\Controllers\Web\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\View\View;

class ReferralController extends Controller
{
    public function index(): View
    {
        $referrals = Referral::query()->with(['referrer', 'referred'])->latest()->limit(100)->get();

        return view('marketing.referrals.index', compact('referrals'));
    }
}