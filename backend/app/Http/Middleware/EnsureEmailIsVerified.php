<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and if their email is verified
        if (Auth::check() && !Auth::user()->hasVerifiedEmail()) {
            return response()->json(['error' => 'Your email is not verified.'], 403);
        }

        return $next($request);
    }
}
