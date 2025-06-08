<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'kuantitas' => 'required|integer|min:1',
                'total_pembayaran' => 'required|numeric|min:1',
            ]);

            $product = Product::findOrFail($request->product_id);

            // Cek stok tersedia
            if ($product->stok < $request->kuantitas) {
                return response()->json([
                    'message' => 'Stok produk tidak mencukupi untuk pesanan ini.'
                ], 422);
            }

            DB::beginTransaction();

            $order = new Order();
            $order->id_produk = $request->product_id;
            $order->id_user = Auth::id();
            $order->kuantitas = $request->kuantitas;
            $order->total_pembayaran = $request->total_pembayaran;
            $order->status = 'pending'; // Tambahkan status pending

            $order->save();

            // HAPUS BAGIAN INI - Jangan kurangi stok di sini
            // $product->stok -= $request->kuantitas;
            // $product->save();

            DB::commit();

            return response()->json(['order_id' => $order->id], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error saat membuat pesanan:', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.',
            ], 500);
        }
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'kuantitas' => 'required|integer|min:1',
            'total_pembayaran' => 'required|numeric',
        ]);

        $order = Order::create([
            'id_user' => auth()->id(),
            'id_produk' => $validated['product_id'], // Fix: was id_produk
            'kuantitas' => $validated['kuantitas'],
            'total_pembayaran' => $validated['total_pembayaran'],
            'status' => 'cart' // Tambahkan status untuk cart
        ]);

        if ($order) {
            return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan ke keranjang']);
        } else {
            return response()->json(['success' => false, 'message' => 'Gagal menambahkan ke keranjang'], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            if ($order->id_user !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk menghapus pesanan ini'
                ], 403);
            }

            DB::beginTransaction();
            try {
                // Hanya kembalikan stok jika sudah dikurangi sebelumnya
                // Dengan solusi baru, ini tidak perlu karena stok belum dikurangi
                // Tapi tetap kita biarkan untuk backward compatibility
                if ($order->status === 'paid' || $order->status === 'completed') {
                    $product = Product::findOrFail($order->id_produk);
                    $product->stok += $order->kuantitas;
                    $product->save();
                }

                $order->delete();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dihapus'
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function requestCancellation($id)
    {
        $transaction = Transaction::where('id', $id)
            ->where('id_user', auth()->id())
            ->firstOrFail();

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Pesanan tidak bisa dibatalkan karena sudah diproses.');
        }

        $transaction->update([
            'cancellation_requested' => true,
        ]);

        return back()->with('success', 'Permintaan pembatalan dikirim. Menunggu konfirmasi admin.');
    }

    public function multipledestroy(Request $request)
    {
        try {
            $validated = $request->validate([
                'order_ids' => 'required|array',
                'order_ids.*' => 'exists:orders,id'
            ]);

            $orders = Order::whereIn('id', $validated['order_ids'])
                ->where('id_user', Auth::id())
                ->get();

            if ($orders->isEmpty()) {
                return response()->json(['message' => 'Pesanan tidak ditemukan atau tidak dimiliki oleh Anda'], 403);
            }

            DB::beginTransaction();
            
            foreach ($orders as $order) {
                // Hanya kembalikan stok jika sudah dikurangi sebelumnya
                if ($order->status === 'paid' || $order->status === 'completed') {
                    $product = Product::findOrFail($order->id_produk);
                    $product->stok += $order->kuantitas;
                    $product->save();
                }
                $order->delete();
            }

            DB::commit();

            return response()->json(['message' => 'Pesanan berhasil dihapus'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in multipledestroy:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}