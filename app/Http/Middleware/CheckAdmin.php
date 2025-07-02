<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Periksa apakah pengguna sudah login dan memiliki peran admin
        if (auth()->check() && auth()->user()->isAdmin()) {
            return $next($request);
        }
        
        // Redirect ke halaman home dengan pesan error jika bukan admin
        return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
    }
}
