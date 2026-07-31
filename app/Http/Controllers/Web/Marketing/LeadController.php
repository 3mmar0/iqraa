<?php

namespace App\Http\Controllers\Web\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(): View
    {
        $leads = Lead::query()->latest()->limit(100)->get();

        return view('marketing.leads.index', compact('leads'));
    }
}