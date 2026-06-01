<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah user sudah login
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Cek apakah role user sesuai
        if (auth()->user()->role !== $role) {
            // Redirect berdasarkan role user yang sebenarnya
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak');
            } else {
                return redirect()->route('petugas.dashboard')->with('error', 'Akses ditolak');
            }
        }

        // Cek apakah akun aktif (khusus petugas)
        if ($role === 'petugas' && !auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan oleh admin');
        }

        return $next($request);
    }
}