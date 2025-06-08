<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
{
    $user = auth()->user();

    // Log informasi tentang user untuk debugging
    \Log::info('User:', [$user]);

    // Gunakan metode isAdmin untuk memeriksa apakah pengguna admin
    if ($user && $user->isAdmin()) {
        return $next($request);
    }

    // Jika bukan admin, redirect ke halaman home
    return redirect()->route('home')->with('error', 'Anda tidak memiliki akses admin.');
}



}

