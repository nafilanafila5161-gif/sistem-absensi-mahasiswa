<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Pastikan user sudah login
        if (!Auth::check()) {
            return redirect('/');
        }

        // 2. Bandingkan role user di database dengan parameter $role dari route
        if ($request->user()->role !== $role) {
            // Jika tidak cocok, tampilkan error 403 Forbidden
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}