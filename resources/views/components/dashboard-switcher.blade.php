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
        $dark = $dark ?? false;
    @endphp
    @if (count($keys) > 1)
        <div class="flex flex-wrap items-center gap-2 text-xs {{ $dark ? 'text-teal-100/70' : 'text-slate-500' }}">
            <span>لوحاتك:</span>
            @foreach ($keys as $key)
                @if (isset($routes[$key]) && \Illuminate\Support\Facades\Route::has($routes[$key]))
                    <a href="{{ route($routes[$key]) }}"
                       class="rounded-lg px-2 py-1 {{ $dark ? 'bg-white/10 text-teal-50 hover:bg-white/15' : 'border border-slate-200 text-teal-800 hover:border-teal-600 hover:bg-teal-50' }}">
                        {{ $labels[$key] ?? $key }}
                    </a>
                @endif
            @endforeach
        </div>
    @endif
@endauth
