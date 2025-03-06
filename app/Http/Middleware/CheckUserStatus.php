<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Flash;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (Auth::check() && ! $request->user()->status) {
            Auth::logout();
            Flash::error('Your Account is currently disabled, please contact to administrator.');

            return redirect()->back();
        }

        return $response;
    }
}
