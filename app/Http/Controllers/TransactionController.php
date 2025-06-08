<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\DetailTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{

    public function index()
    {
        // Ambil data pesanan dan transaksi yang terkait dengan pelanggan yang sedang login
        $orders = Transaction::where('id_user', Auth::user()->id)
                            ->with('product', 'user')  // Pastikan relasi produk dan user dimuat
                            ->orderBy('created_at', 'desc') // urut dari terbaru
                            ->get();
    
        // Kirim data pesanan ke view
        return view('pelanggan.data-pesanan', compact('orders'));
    }

public function store(Request $request)
{
    Log::info('Store Transaction Called with data:', $request->all());
    try {
        // Validation
        $validated = $request->validate([
            'order_id' => 'required',
            'total_pembayaran' => 'required|numeric',
            'custom_image' => 'nullable|image|max:2048',
            'payment_method' => 'required|in:midtrans,saldo',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 401);
        }

        // Convert amount to integer
        $totalPembayaran = (int) $request->total_pembayaran;
        
        // Generate unique Transaction ID
        $transactionId = 'TR' . time() . rand(100, 999);

        $order = Order::with('product')
            ->where('id', $request->order_id)
            ->first();

        if (!$order) {
            Log::error('Order not found:', ['order_id' => $request->order_id]);
            return response()->json([
                'success' => false,
                'message' => 'Order tidak ditemukan.'
            ], 404);
        }

        // Handle file upload
        $customImagePath = null;
        if ($request->hasFile('custom_image')) {
            $customImagePath = $request->file('custom_image')
                ->store('images/dokumen_tambahan', 'public');
        }

        // Check payment method
        if ($request->payment_method === 'saldo') {
            return $this->processSaldoPayment($request, $user, $order, $transactionId, $totalPembayaran, $customImagePath);
        } else {
            return $this->processMidtransPayment($request, $user, $order, $transactionId, $totalPembayaran, $customImagePath);
        }

    } catch (\Exception $e) {
        Log::error('General Error:', ['error' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

// Update method processSaldoPayment
private function processSaldoPayment($request, $user, $order, $transactionId, $totalPembayaran, $customImagePath)
{
    Log::info('Processing saldo payment:', [
        'user_id' => $user->id,
        'user_saldo' => $user->saldo,
        'total_pembayaran' => $totalPembayaran
    ]);

    if ($user->saldo < $totalPembayaran) {
        return response()->json([
            'success' => false,
            'message' => 'Saldo tidak mencukupi. Saldo Anda: Rp ' . number_format($user->saldo, 0, ',', '.') . 
                        ', Total pembayaran: Rp ' . number_format($totalPembayaran, 0, ',', '.')
        ], 400);
    }

    try {
        DB::beginTransaction();

        // Create transaction record
        $transaction = Transaction::create([
            'id' => $transactionId,
            'id_user' => $user->id,
            'id_pesanan' => $request->order_id,
            'total_pembayaran' => $totalPembayaran,
            'snap_token' => null,
            'status' => 'success', // Langsung success untuk pembayaran saldo
            'payment_method' => 'saldo',
            'tanggal_transaksi' => now()
        ]);

        // Create detail transaction
        DetailTransaction::create([
            'id_transaksi' => $transaction->id,
            'id_pesanan' => $request->order_id,
            'dokumen_tambahan' => $customImagePath
        ]);

        // Deduct balance from user
        $user->saldo = $user->saldo - $totalPembayaran;
        $user->save();

        // PENTING: Kurangi stok produk di sini setelah pembayaran berhasil
        $this->reduceProductStock($request->order_id);

        DB::commit();

        Log::info('Saldo payment successful:', [
            'transaction_id' => $transactionId,
            'user_id' => $user->id,
            'remaining_saldo' => $user->saldo,
            'amount_paid' => $totalPembayaran
        ]);

        return response()->json([
            'success' => true,
            'transaction_id' => $transactionId,
            'payment_method' => 'saldo',
            'remaining_saldo' => $user->saldo,
            'message' => 'Pembayaran dengan saldo berhasil. Sisa saldo Anda: Rp ' . number_format($user->saldo, 0, ',', '.'),
            'redirect_url' => '/product' // Tambahkan redirect URL
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Saldo payment error:', ['error' => $e->getMessage()]);
        throw new \Exception('Gagal memproses pembayaran saldo: ' . $e->getMessage());
    }
}

private function processMidtransPayment($request, $user, $order, $transactionId, $totalPembayaran, $customImagePath)
{
    // Prepare Midtrans request payload
    $payload = [
        'transaction_details' => [
            'order_id' => $transactionId,
            'gross_amount' => $totalPembayaran
        ],
        'credit_card' => [
            'secure' => true
        ],
        'customer_details' => [
            'first_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->telepon,
        ],
        'item_details' => [
            [
                'id' => $order->product->id,
                'price' => $totalPembayaran,
                'quantity' => 1,
                'name' => substr($order->product->nama_produk, 0, 50)
            ]
        ],
        'enabled_payments' => [
            'credit_card', 'bca_va', 'bni_va', 'bri_va', 
            'mandiri_clickpay', 'gopay', 'shopeepay'
        ]
    ];

    Log::info('Midtrans payload:', $payload);

    try {
        // Create HTTP client
        $client = new \GuzzleHttp\Client();

        // Make request to Midtrans Snap API
        $response = $client->post('https://app.sandbox.midtrans.com/snap/v1/transactions', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode(config('midtrans.server_key') . ':')
            ],
            'json' => $payload,
            'verify' => false // Only for development
        ]);

        $result = json_decode($response->getBody()->getContents(), true);
        
        if (!isset($result['token'])) {
            throw new \Exception('Invalid response from Midtrans');
        }

        $snapToken = $result['token'];
        Log::info('Snap Token generated:', ['token' => $snapToken]);

        DB::beginTransaction();
        
        $transaction = Transaction::create([
            'id' => $transactionId,
            'id_user' => $user->id,
            'id_pesanan' => $request->order_id,
            'total_pembayaran' => $totalPembayaran,
            'snap_token' => $snapToken,
            'status' => 'pending',
            'payment_method' => 'midtrans',
            'tanggal_transaksi' => now()
        ]);

        DetailTransaction::create([
            'id_transaksi' => $transaction->id,
            'id_pesanan' => $request->order_id,
            'dokumen_tambahan' => $customImagePath
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'snap_token' => $snapToken,
            'transaction_id' => $transactionId,
            'payment_method' => 'midtrans'
        ]);

    } catch (\GuzzleHttp\Exception\RequestException $e) {
        DB::rollBack();
        $errorResponse = $e->hasResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null;
        Log::error('Midtrans API Error:', [
            'error' => $errorResponse ?? $e->getMessage(),
            'payload' => $payload
        ]);
        throw new \Exception('Gagal membuat Snap Token: ' . ($errorResponse['error_messages'][0] ?? $e->getMessage()));
    }
}
    
    public function updateStatus(Request $request)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|array',
        ]);
    
        // Perbarui status dan mode pembayaran untuk setiap transaksi
        foreach ($request->status as $transactionId => $status) {
            $transaction = Transaction::where('id', $transactionId)->first();
            if ($transaction) {
                // Update status pemesanan
                $transaction->status = $status;
                $transaction->save();
            }
        }
    
        return redirect()->back()->with('success', 'Status dan mode pembayaran berhasil diperbarui.');
    }
    

    public function search(Request $request)
    {
        $query = Transaction::with(['detailTransactions', 'detailTransactions.order']) // Memuat relasi
            ->join('orders', 'transactions.id_pesanan', '=', 'orders.id')
            ->select(
                'transactions.*',
                'orders.kuantitas',
                DB::raw('DATE(transactions.created_at) as tanggal_transaksi')
            );
        

        // Filter berdasarkan rentang tanggal jika ada
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween(DB::raw('DATE(transactions.created_at)'), [$request->start_date, $request->end_date]);
        }

        // Filter berdasarkan status transaksi jika ada
        if ($request->filled('status')) {
            $query->where('transactions.status', $request->status);
        }

        // Filter berdasarkan ID Transaksi jika ada
        if ($request->filled('transaction_id')) {
            $query->where('transactions.id', 'like', "%" . $request->transaction_id . "%");
        }

        // Ambil data transaksi yang sudah difilter
        $transactions = $query->get();

        $totalPendapatan = $transactions->sum('total_pembayaran');


        return view('admin.laporan', compact('transactions', 'totalPendapatan'));
    }

    public function cart(Request $request)
    {
        $userId = Auth::user()->id;
        $user = Auth::user();
        Log::info('Request data:', ['request_data' => $request->all()]);
    
        try {
            // Modified validation to match the incoming data structure
            $validated = $request->validate([
                'custom_image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
                'cart' => 'required|array',
                'cart.*.user_id' => 'required|string',
                'cart.*.product_id' => 'required|string',
                'cart.*.order_id' => 'required|string',
                'cart.*.kuantitas' => 'required|integer|min:1',
                'cart.*.total_pembayaran' => 'required|numeric|min:0'
            ]);
    
            // Handle file upload
            try {
                $customImagePath = null;
                if ($request->hasFile('custom_image')) {
                    $file = $request->file('custom_image');
                    if ($file->isValid()) {
                        $customImagePath = $file->store('images/dokumen_tambahan', 'public');
                    }
                }
            } catch (\Exception $e) {
                Log::error('File upload error: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengunggah file: ' . $e->getMessage()
                ], 422);
            }
    
            // Generate transaction ID
            $lastOrder = Transaction::orderBy('id', 'desc')->first();
            $lastNumber = $lastOrder ? (int)substr($lastOrder->id, 2) : 0;
            $transactionId = 'TR' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    
            // Calculate total payment - updating this part
            $totalPembayaran = collect($request->cart)->sum(function($item) {
                return $item['total_pembayaran'] * $item['kuantitas'];
            });
            
    
            // Prepare items for Midtrans - updating this part
            $itemDetails = [];
            foreach ($request->cart as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    throw new \Exception('Product not found: ' . $item['product_id']);
                }
                
                $itemDetails[] = [
                    'id' => $product->id,
                    'price' => (int)$item['total_pembayaran'],
                    'quantity' => (int)$item['kuantitas'],  // Convert to integer
                    'name' => substr($product->nama_produk, 0, 50)
                ];
            }
    
            // Prepare Midtrans payload
            $payload = [
                'transaction_details' => [
                    'order_id' => $transactionId,
                    'gross_amount' => (int)$totalPembayaran
                ],
                'credit_card' => [
                    'secure' => true
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->telepon,
                ],
                'item_details' => $itemDetails,
                'enabled_payments' => [
                    'credit_card', 'bca_va', 'bni_va', 'bri_va', 
                    'mandiri_clickpay', 'gopay', 'shopeepay'
                ],
                'callbacks' => [
                    'finish' => url('/product'),
                    'error' => url('/cart'),
                    'pending' => url('/product')
                ]
            ];
    
            // Verify total matches sum of items
            $itemsTotal = collect($itemDetails)->sum(function($item) {
                return $item['price'] * $item['quantity'];
            });

            if ($itemsTotal !== (int)$totalPembayaran) {
                throw new \Exception('Total pembayaran tidak sesuai dengan total item');
            }

            Log::info('Midtrans payload:', $payload);
    
            try {
                // Create HTTP client
                $client = new \GuzzleHttp\Client();
    
                // Make request to Midtrans Snap API
                $response = $client->post('https://app.sandbox.midtrans.com/snap/v1/transactions', [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Basic ' . base64_encode(config('midtrans.server_key') . ':')
                    ],
                    'json' => $payload,
                    'verify' => false // Only for development
                ]);
    
                $result = json_decode($response->getBody()->getContents(), true);
                
                if (!isset($result['token'])) {
                    throw new \Exception('Invalid response from Midtrans');
                }
    
                $snapToken = $result['token'];
                Log::info('Snap Token generated:', ['token' => $snapToken]);
    
                // Start database transaction
                DB::beginTransaction();
    
                // Create main transaction
                $transaction = new Transaction();
                $transaction->id = $transactionId;
                $transaction->id_user = $userId;
                $transaction->id_pesanan = $request->cart[0]['order_id'];
                $transaction->total_pembayaran = $totalPembayaran;
                $transaction->snap_token = $snapToken;
                $transaction->status = 'pending';
                $transaction->tanggal_transaksi = now();
                $transaction->save();
    
                // Create transaction details
                foreach ($request->cart as $item) {
                    $detailTransaction = new DetailTransaction();
                    $detailTransaction->id_transaksi = $transaction->id;
                    $detailTransaction->id_pesanan = $item['order_id'];
                    $detailTransaction->dokumen_tambahan = $customImagePath;
                    $detailTransaction->save();
                }
    
                DB::commit();
    
                return response()->json([
                    'success' => true,
                    'snap_token' => $snapToken,
                    'transaction_id' => $transactionId
                ]);
    
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                DB::rollBack();
                $errorResponse = $e->hasResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null;
                Log::error('Midtrans API Error:', [
                    'error' => $errorResponse ?? $e->getMessage(),
                    'payload' => $payload
                ]);
                throw new \Exception('Gagal membuat Snap Token: ' . ($errorResponse['error_messages'][0] ?? $e->getMessage()));
            }
    
        } catch (\Exception $e) {
            Log::error('General Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function printReport(Request $request)
    {
        $query = Transaction::with('detailTransactions.order')
            ->join('orders', 'transactions.id_pesanan', '=', 'orders.id')
            ->select(
                'transactions.*',
                'orders.kuantitas',
                DB::raw('DATE(transactions.created_at) as tanggal_transaksi')
            );
    
        // Filter tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween(DB::raw('DATE(transactions.created_at)'), [$request->start_date, $request->end_date]);
        }
    
        // Filter status
        if ($request->filled('status')) {
            $query->where('transactions.status', $request->status);
        }
    
        // Filter ID transaksi
        if ($request->filled('transaction_id')) {
            $query->where('transactions.id', 'like', "%{$request->transaction_id}%");
        }
    
        $transactions = $query->get();
    
        $totalPendapatan = $transactions->sum('total_pembayaran');
    
        return view('admin.transaction-print', compact('transactions', 'totalPendapatan'));
    }
    
public function approveCancellation($id)
{
    Log::info('=== APPROVE CANCELLATION METHOD CALLED ===');
    Log::info('Received ID parameter:', ['id' => $id]);
    Log::info('Request method:', ['method' => request()->method()]);
    Log::info('Current user:', ['user' => auth()->user()]);

    try {
        // 1. Load transaction dengan relasi user
        $transaction = Transaction::with('user')->findOrFail($id);
        Log::info('=== APPROVE CANCELLATION START ===');
        Log::info('Transaction found:', [
            'transaction_id' => $transaction->id,
            'user_id_from_transaction' => $transaction->id_user,
            'total_pembayaran' => $transaction->total_pembayaran,
            'total_pembayaran_type' => gettype($transaction->total_pembayaran),
            'status' => $transaction->status,
            'cancellation_requested' => $transaction->cancellation_requested
        ]);

        // 2. Validasi kondisi pembatalan
        if (!$transaction->cancellation_requested || $transaction->status !== 'pending') {
            Log::warning('Invalid cancellation conditions:', [
                'cancellation_requested' => $transaction->cancellation_requested,
                'status' => $transaction->status
            ]);
            
            // Return JSON for AJAX requests
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak valid untuk pembatalan.'
                ], 400);
            }
            
            return back()->with('error', 'Transaksi tidak valid untuk pembatalan.');
        }

        // 3. Pastikan user ada dan valid
        $user = $transaction->user;
        if (!$user) {
            Log::error('User not found for transaction:', [
                'transaction_id' => $transaction->id, 
                'user_id_from_transaction' => $transaction->id_user
            ]);
            
            // Coba cari user manual
            $manualUser = \App\Models\User::where('id', $transaction->id_user)->first();
            if ($manualUser) {
                Log::info('Manual user search found user:', [
                    'user_id' => $manualUser->id,
                    'user_name' => $manualUser->name,
                    'user_role' => $manualUser->role
                ]);
                $user = $manualUser;
            } else {
                Log::error('Manual user search also failed');
                
                // Return JSON for AJAX requests
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'User tidak ditemukan untuk transaksi ini.'
                    ], 404);
                }
                
                return back()->with('error', 'User tidak ditemukan untuk transaksi ini.');
            }
        }

        Log::info('User found for refund:', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role,
            'current_saldo' => $user->saldo,
            'current_saldo_type' => gettype($user->saldo),
            'refund_amount' => $transaction->total_pembayaran
        ]);

        // 4. Gunakan database transaction untuk konsistensi
        DB::transaction(function () use ($transaction, $user) {
            // Hitung saldo baru - konversi ke integer karena saldo adalah bigint
            $saldoSebelum = (int) $user->saldo;
            $jumlahRefund = (int) round((float) $transaction->total_pembayaran); // Bulatkan decimal ke integer
            $saldoSesudah = $saldoSebelum + $jumlahRefund;

            Log::info('Saldo calculation:', [
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sebelum_type' => gettype($saldoSebelum),
                'jumlah_refund' => $jumlahRefund,
                'jumlah_refund_type' => gettype($jumlahRefund),
                'saldo_sesudah' => $saldoSesudah,
                'original_total_pembayaran' => $transaction->total_pembayaran
            ]);

            // Update saldo user dengan raw query untuk memastikan
            $updateResult = DB::table('users')
                ->where('id', $user->id)
                ->update(['saldo' => $saldoSesudah, 'updated_at' => now()]);
                
            Log::info('Raw update result:', ['affected_rows' => $updateResult]);
            
            // Refresh model untuk mendapatkan data terbaru
            $user->refresh();
            Log::info('User after refresh:', ['saldo' => $user->saldo]);

            // Update status transaksi dengan raw query juga
            $transactionUpdateResult = DB::table('transactions')
                ->where('id', $transaction->id)
                ->update([
                    'status' => 'return',
                    'cancellation_requested' => 0,
                    'updated_at' => now()
                ]);
                
            Log::info('Transaction update result:', ['affected_rows' => $transactionUpdateResult]);
            
            // Refresh transaction model
            $transaction->refresh();
            Log::info('Transaction after refresh:', [
                'status' => $transaction->status,
                'cancellation_requested' => $transaction->cancellation_requested
            ]);
        });

        // 5. Verifikasi final - ambil data terbaru dari database
        $userFinal = \App\Models\User::find($user->id);
        $transactionFinal = Transaction::find($transaction->id);

        Log::info('=== FINAL VERIFICATION ===', [
            'user_final_id' => $userFinal->id,
            'user_final_name' => $userFinal->name,
            'user_final_saldo' => $userFinal->saldo,
            'transaction_final_status' => $transactionFinal->status,
            'transaction_cancellation_requested' => $transactionFinal->cancellation_requested,
            'refund_amount' => (float) $transaction->total_pembayaran
        ]);

        Log::info('=== APPROVE CANCELLATION SUCCESS ===');

        $successMessage = "Pembatalan disetujui. Dana Rp " . number_format((int) round((float) $transaction->total_pembayaran), 0, ',', '.') . " telah dikembalikan ke saldo " . $userFinal->name . ".";

        // Return JSON for AJAX requests
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'transaction' => [
                    'id' => $transactionFinal->id,
                    'status' => $transactionFinal->status,
                    'cancellation_requested' => $transactionFinal->cancellation_requested,
                ],
                'user' => [
                    'id' => $userFinal->id,
                    'name' => $userFinal->name,
                    'saldo' => $userFinal->saldo,
                ],
                'refund_amount' => (int) round((float) $transaction->total_pembayaran)
            ]);
        }

        return back()->with('success', $successMessage);

    } catch (\Exception $e) {
        Log::error('=== APPROVE CANCELLATION ERROR ===', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        $errorMessage = 'Terjadi kesalahan: ' . $e->getMessage();
        
        // Return JSON for AJAX requests
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 500);
        }
        
        return back()->with('error', $errorMessage);
    }
}

// Tambahkan method ini di TransactionController untuk mengurangi stok setelah pembayaran berhasil

private function reduceProductStock($orderId)
{
    try {
        $order = Order::with('product')->findOrFail($orderId);
        $product = $order->product;
        
        // Cek stok masih mencukupi
        if ($product->stok < $order->kuantitas) {
            throw new \Exception('Stok produk tidak mencukupi. Stok tersisa: ' . $product->stok);
        }
        
        // Kurangi stok
        $product->stok -= $order->kuantitas;
        $product->save();
        
        // Update status order
        $order->status = 'paid';
        $order->save();
        
        Log::info('Stock reduced successfully', [
            'product_id' => $product->id,
            'quantity_reduced' => $order->kuantitas,
            'remaining_stock' => $product->stok
        ]);
        
    } catch (\Exception $e) {
        Log::error('Failed to reduce stock:', [
            'order_id' => $orderId,
            'error' => $e->getMessage()
        ]);
        throw $e;
    }
}


// Tambahkan method untuk handle Midtrans callback/webhook
public function handleMidtransCallback(Request $request)
{
    try {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transaction = Transaction::where('id', $request->order_id)->first();
        
        if (!$transaction) {
            Log::error('Transaction not found for callback:', ['order_id' => $request->order_id]);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        Log::info('Midtrans callback received:', [
            'order_id' => $request->order_id,
            'transaction_status' => $request->transaction_status,
            'payment_type' => $request->payment_type
        ]);

        DB::beginTransaction();

        switch ($request->transaction_status) {
            case 'capture':
            case 'settlement':
                $transaction->status = 'success';
                $transaction->save();
                
                // PENTING: Kurangi stok produk setelah pembayaran berhasil
                $this->reduceProductStock($transaction->id_pesanan);
                break;
                
            case 'pending':
                $transaction->status = 'pending';
                $transaction->save();
                break;
                
            case 'deny':
            case 'expire':
            case 'cancel':
                $transaction->status = 'failed';
                $transaction->save();
                // Stok tidak dikurangi karena pembayaran gagal
                break;
        }

        DB::commit();

        return response()->json(['message' => 'OK']);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Midtrans callback error:', ['error' => $e->getMessage()]);
        return response()->json(['message' => 'Error processing callback'], 500);
    }
}


    
    

    


}


