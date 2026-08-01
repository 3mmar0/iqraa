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
        <div class="flex flex-wrap items-center gap-2 text-xs {{ $dark ? 'text-white/70' : 'text-[var(--color-muted)]' }}">
            <span>لوحاتك:</span>
            @foreach ($keys as $key)
                @if (isset($routes[$key]) && \Illuminate\Support\Facades\Route::has($routes[$key]))
                    <a href="{{ route($routes[$key]) }}"
                       class="rounded-lg px-2 py-1 {{ $dark ? 'bg-white/10 text-white hover:bg-white/15' : 'border border-[var(--color-line)] text-[var(--color-primary)] hover:border-[var(--color-primary)] hover:bg-[var(--color-primary-light)]' }}">
                        {{ $labels[$key] ?? $key }}
                    </a>
                @endif
            @endforeach
        </div>
    @endif
@endauth
