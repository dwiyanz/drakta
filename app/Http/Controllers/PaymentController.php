<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Transaction;
use Auth;

class PaymentController extends Controller
{
    public function createTransaction(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'total_pembayaran' => 'required|numeric',
            'payment_method' => 'required|string'
        ]);

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Buat data transaksi
        $orderId = uniqid();
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $request->total_pembayaran,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->nama_lengkap,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->telepon,
            ],
        ];

        try {
            // Ambil Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'message' => 'Snap token berhasil dibuat!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function success()
    {
        return view('payment.success'); // Tampilkan halaman sukses
    }

    public function updateStatus(Request $request, $id)
{
    try {
        $request->validate([
            'status' => 'required|string',
            'payment_data' => 'required|array',
        ]);

        $transaction = Transaction::where('transaction_id', $id)->firstOrFail();
        $transaction->status = $request->status;
        $transaction->save();

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Update status gagal: ' . $e->getMessage()
        ], 500);
    }
}

}

