<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        Log::info('User Role:', ['role' => Auth::user()->role]);

        if (!Auth::check() || Auth::user()->role !== $role) {
            Log::info('Unauthorized access attempt.');
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return $next($request);
    }
}
