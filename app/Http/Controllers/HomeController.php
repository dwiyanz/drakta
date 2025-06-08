<?php

namespace App\Http\Controllers;
use App\Models\Beranda;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Redirect ke halaman beranda (home.blade.php).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $beranda = Beranda::first();
        
        return view('pelanggan.home', compact('beranda')); // Langsung menampilkan view 'home.blade.php'
    }
}
