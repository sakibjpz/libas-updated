<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // not logged in -> send to login
        if (!$user) {
            return redirect()->route('login');
        }

        // if admin -> allow
        if ($user->isAdmin()) {
            return $next($request);
        }

        // otherwise deny
        abort(403, 'Unauthorized');
    }
}
