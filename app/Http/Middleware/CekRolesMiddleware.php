<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekRolesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Periksa apakah pengguna telah login
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        // Periksa peran pengguna
        if ($user->role_id == $role) {
            return $next($request);
        }

        // Redirect sesuai dengan peran pengguna
        if ($user->role_id == 1) {
            return redirect('/admin');
        } elseif ($user->role_id == 2) {
            return redirect('/gurudb');
        } elseif ($user->role_id == 3) {
            return redirect('/siswadb');
        }
    }
}
