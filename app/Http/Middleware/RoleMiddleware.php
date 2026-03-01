<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|array  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/login'); // Redirect jika user belum login
        }

        // Cek apakah role yang diberikan adalah array atau string
        if (is_array($role)) {
            // Jika ada beberapa role, periksa apakah role user ada di dalam array
            if (!in_array(Auth::user()->role, $role)) {
                abort(403, 'Unauthorized action.'); // Tampilkan error 403 jika role tidak sesuai
            }
        } else {
            // Jika hanya ada satu role, periksa apakah role user sesuai
            if (Auth::user()->role !== $role) {
                abort(403, 'Unauthorized action.'); // Tampilkan error 403 jika role tidak sesuai
            }
        }

        // Melanjutkan request ke proses selanjutnya jika valid
        return $next($request);
    }
}
