@auth
    @php
        $keys = auth()->user()->dashboardKeys();
        $labels = [
            'student' => 'لوحة الطالب',
            'instructor' => 'لوحة المحاضر',
            'team' => 'لوحة الفريق',
            'finance' => 'لوحة المالية',
            'marketing' => 'لوحة التسويق',
            'support' => 'لوحة الدعم',
            'admin' => 'لوحة الإدارة',
        ];
        $routes = [
            'student' => 'student.home',
            'instructor' => 'instructor.home',
            'team' => 'team.home',
            'finance' => 'finance.home',
            'marketing' => 'marketing.home',
            'support' => 'support.home',
            'admin' => 'admin.home',
        ];
    @endphp
    @if (count($keys) > 1)
        <div class="flex flex-wrap items-center gap-2 text-sm">
            <span class="text-slate-500">لوحاتك:</span>
            @foreach ($keys as $key)
                @if (isset($routes[$key]) && \Illuminate\Support\Facades\Route::has($routes[$key]))
                    <a href="{{ route($routes[$key]) }}"
                       class="rounded border border-slate-200 px-2 py-1 text-teal-800 hover:border-teal-600 hover:bg-teal-50">
                        {{ $labels[$key] ?? $key }}
                    </a>
                @endif
            @endforeach
        </div>
    @endif
@endauth