<?php

namespace App\Http\Controllers\Web\Marketing;

use App\Http\Controllers\Controller;
use App\Models\AmbassadorProfile;
use Illuminate\View\View;

class AmbassadorController extends Controller
{
    public function index(): View
    {
        $ambassadors = AmbassadorProfile::query()->with('user')->latest()->get();

        return view('marketing.ambassadors.index', compact('ambassadors'));
    }
}