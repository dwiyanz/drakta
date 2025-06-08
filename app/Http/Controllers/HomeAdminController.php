<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Transaction;

class HomeAdminController extends Controller
{
    // Pastikan metode 'index' ada di dalam controller
    public function index()
    {
        $jumlahPelanggan = User::where('role', 'pelanggan')->count();
        $statusPending = Transaction::where('status', 'pending')->count();
        $statusProses = Transaction::where('status', 'process')->count();
        $statusSelesai = Transaction::where('status', 'completed')->count();
        
        return view('admin.home', compact('jumlahPelanggan', 'statusSelesai', 'statusProses', 'statusPending')); // Mengarah ke view admin.index (sesuaikan dengan kebutuhan Anda)
    }
}

