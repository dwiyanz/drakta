<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $transactions = Transaction::all();
        $totalPendapatan = $transactions->sum('total_pembayaran');
        return view('admin.laporan',compact('transactions',  'totalPendapatan'));
    }
}
