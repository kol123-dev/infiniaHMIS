<?php

namespace App\Http\Middleware;

use App\Models\Module;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CheckModule
 */
class CheckModule
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $route = request()->route()->getName();
        $activeRoutes = Module::whereRoute($route)->whereIsActive(1)->first();
        if (! $activeRoutes) {
            abort(404);
        }

        return $next($request);
    }
}
