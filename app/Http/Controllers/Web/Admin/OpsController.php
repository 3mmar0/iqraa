<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class OpsController extends Controller
{
    public function index(): View
    {
        return view('admin.ops.index', [
            'placeholders' => [
                'storage' => 'مراقبة التخزين',
                'queues' => 'طابور المهام',
                'jobs' => 'الوظائف الفاشلة',
                'monitoring' => 'المراقبة والتنبيهات',
            ],
        ]);
    }
}