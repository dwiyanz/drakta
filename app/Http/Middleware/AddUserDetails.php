<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AddUserDetails
{
    public function handle($request, Closure $next)
    {
        if (app()->runningInConsole()) {
            // Jangan share apa-apa kalau running di queue
            return $next($request);
        }
    
        if (Auth::check() && Auth::user()) {
            view()->share('user_nama_lengkap', Auth::user()->name ?? 'Tidak Ada Nama');
        } else {
            view()->share('user_nama_lengkap', 'Tidak Ada Nama');
        }
    
        return $next($request);
    }    
}

