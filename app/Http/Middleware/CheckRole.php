<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        $userRole = $user->role->kode ?? null;

        // Super Admin has access to everything
        if ($userRole === 'super_admin') {
            return $next($request);
        }

        if (!in_array($userRole, $roles)) {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang untuk halaman ini.');
        }

        return $next($request);
    }
}
