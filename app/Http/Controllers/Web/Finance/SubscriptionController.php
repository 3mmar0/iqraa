<?php

namespace App\Http\Controllers\Web\Finance;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(): View
    {
        $subscriptions = Subscription::query()->with('user')->latest()->limit(100)->get();

        return view('finance.subscriptions', compact('subscriptions'));
    }
}