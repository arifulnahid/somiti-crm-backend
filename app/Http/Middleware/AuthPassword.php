<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Ensure the user is already authenticated via Sanctum
        if (! $request->user()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // 2. Validate that 'password' is sent in the body
        if (! $request->has('password')) {
            return response()->json(['message' => 'Password confirmation is required.'], 422);
        }

        // 3. Verify the password match
        if (! Hash::check($request->input('password'), $request->user()->password)) {
            return response()->json(['message' => 'The provided password does not match our records.'], 403);
        }

        return $next($request);
    }
}
