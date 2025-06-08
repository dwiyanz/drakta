<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\Transaction;

class AdminController extends Controller
{
    public function index()
    {
        // Menampilkan halaman admin
        return view('admin.index');
    }

    public function store(Request $request)
    {
        // Logika untuk menyimpan produk baru
        // Misalnya validasi input dan simpan produk ke database

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            // Tambahkan validasi lain yang diperlukan
        ]);

        // Simpan produk ke database
        // Model Product harus sudah dibuat sebelumnya
        // Product::create($request->all());

        return redirect()->route('admin.index')->with('success', 'Product added successfully!');
    }

    public function storePesanan(Request $request)
    {
    $orders = Transaction::all();

    $validated = $request->validate([
        'order_id' => 'required|integer',
        'user_id' => 'required|integer',
        'product' => 'required|string',
        'total_price' => 'required|numeric',
    ]);

    // Simpan data pemesanan baru untuk admin
    $order = new Transaction();
    $order->order_id = $validated['order_id'];
    $order->user_id = $validated['user_id'];
    $order->product = $validated['product'];
    $order->total_price = $validated['total_price'];
    $order->status_pembayaran = 'Pending';
    $order->status_pemesanan = 'Pesanan Diterima';
    $order->save();

    return response()->json(['success' => true, 'message' => 'Pesanan berhasil disimpan di admin']);
    }


    // Tambahkan metode lain yang diperlukan untuk admin di sini
}
