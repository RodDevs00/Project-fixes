<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdminCanBypassVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Check if the user is an admin or if the email is verified
        if ($user && ($user->role === 'admin' || $user->hasVerifiedEmail())) {
            return $next($request);
        }

        return response()->json(['error' => 'Your account must be verified to access this resource.'], 403);
    }
}
