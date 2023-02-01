<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (!Auth::user()->hasRole('admin')) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
            }else{
                return $next($request);
            }
        }
        return response()->json(['status' => 'error', 'message' => 'Not Login'], 401);

    }
}
