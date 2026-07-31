<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDashboardAccess
{
    public function handle(Request $request, Closure $next, string $dashboardKey): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($dashboardKey, $user->dashboardKeys(), true)) {
            abort(403, 'غير مصرح لك بالوصول إلى هذه اللوحة.');
        }

        return $next($request);
    }
}
