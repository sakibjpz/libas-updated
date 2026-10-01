<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Check if user is logged in and has the required role
        if (!auth()->check() || auth()->user()->role !== $role) {
            abort(403, 'Unauthorized'); // block access if not correct role
        }

        return $next($request);
    }
}
