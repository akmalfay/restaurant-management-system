<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRole
{
    /**
     * Handle an incoming request.
     * Usage: middleware('role:admin|staff')
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        $user = Auth::user();
        if (! $user) {
            abort(403);
        }

        $allowed = array_map('trim', explode('|', $roles));

        // For staff we check user_type === 'staff' (role detail for staff not considered here)
        if (! in_array($user->user_type, $allowed)) {
            abort(403);
        }

        return $next($request);
    }
}
