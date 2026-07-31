<?php

namespace App\Http\Controllers\Web\Finance;

use App\Http\Controllers\Controller;
use App\Models\PayrollRecord;
use Illuminate\View\View;

class PayrollController extends Controller
{
    public function index(): View
    {
        $records = PayrollRecord::query()->with('user')->latest()->limit(100)->get();

        return view('finance.payroll', compact('records'));
    }
}