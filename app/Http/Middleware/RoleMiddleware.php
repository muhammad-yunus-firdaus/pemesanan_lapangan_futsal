<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * RoleMiddleware - cek role user sebelum akses halaman
 * Misal admin mau akses halaman user, bakal ditolak
 */
class RoleMiddleware
{
    // Cek apakah role user sesuai dengan yang diminta
    public function handle(Request $request, Closure $next, $role)
    {
        // Belum login? balik ke halaman login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Cek rolenya, bisa satu atau banyak
        if (is_array($role)) {
            // Kalo role ga ada di list yang dibolehkan
            if (!in_array(Auth::user()->role, $role)) {
                abort(403, 'Unauthorized action.');
            }
        } else {
            // Role tunggal, langsung bandingin
            if (Auth::user()->role !== $role) {
                abort(403, 'Unauthorized action.');
            }
        }

        return $next($request);
    }
}
