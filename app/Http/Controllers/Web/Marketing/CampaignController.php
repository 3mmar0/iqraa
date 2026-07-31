<?php

namespace App\Http\Controllers\Web\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Services\MarketingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function __construct(private MarketingService $marketing)
    {
    }

    public function index(): View
    {
        $campaigns = Campaign::query()->latest()->get();

        return view('marketing.campaigns.index', compact('campaigns'));
    }

    public function create(): View
    {
        return view('marketing.campaigns.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        $this->marketing->createCampaign($validated['name'], [
            'starts_at' => $validated['starts_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
        ]);

        return redirect()->route('marketing.campaigns.index')->with('status', 'تم إنشاء الحملة.');
    }
}