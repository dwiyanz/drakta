<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\DetailTransaction;
use App\Models\TransactionOrder; // Tambahkan model baru untuk tabel transaction_order
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
                'custom_images' => 'nullable|array',
                'custom_images.*' => 'nullable|file|mimes:jpeg,png,jpg|max:2048'
            ]);

            // Handle multiple file uploads
            $customImagePaths = [];
            if ($request->hasFile('custom_images')) {
                foreach ($request->file('custom_images') as $productId => $file) {
                    if ($file && $file->isValid()) {
                        $customImagePaths[$productId] = $file->store('images/dokumen_tambahan', 'public');
                        Log::info('File uploaded for product:', [
                            'product_id' => $productId,
                            'file_path' => $customImagePaths[$productId]
                        ]);
                    }
                }
            }

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
                'status' => 'pending', // Langsung pending untuk pembayaran saldo
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
                'credit_card',
                'bca_va',
                'bni_va',
                'bri_va',
                'mandiri_clickpay',
                'gopay',
                'shopeepay'
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
            // Modified validation to handle both single and multiple files
            $validated = $request->validate([
                'custom_image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
                'custom_images' => 'nullable|array',
                'custom_images.*' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
                'cart' => 'required|array',
                'cart.*.user_id' => 'required|string',
                'cart.*.product_id' => 'required|string',
                'cart.*.order_id' => 'required|string',
                'cart.*.kuantitas' => 'required|integer|min:1',
                'cart.*.total_pembayaran' => 'required|numeric|min:0'
            ]);

            // Handle file uploads - PERBAIKAN UTAMA DI SINI
            $uploadedFiles = [];
            
            try {
                // Handle single custom_image
                if ($request->hasFile('custom_image')) {
                    $file = $request->file('custom_image');
                    if ($file->isValid()) {
                        $uploadedFiles['single'] = $file->store('images/dokumen_tambahan', 'public');
                        Log::info('Single custom image uploaded:', ['path' => $uploadedFiles['single']]);
                    }
                }

                // Handle multiple custom_images - PERBAIKAN DI SINI
                if ($request->hasFile('custom_images')) {
                    foreach ($request->file('custom_images') as $index => $file) {
                        if ($file && $file->isValid()) {
                            $uploadedFiles[$index] = $file->store('images/dokumen_tambahan', 'public');
                            Log::info('Multiple custom image uploaded:', [
                                'index' => $index,
                                'path' => $uploadedFiles[$index]
                            ]);
                        }
                    }
                }

                Log::info('All uploaded files:', ['files' => $uploadedFiles]);
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

            // Calculate total payment
            $totalPembayaran = collect($request->cart)->sum(function ($item) {
                return $item['total_pembayaran'] * $item['kuantitas'];
            });

            // Prepare items for Midtrans
            $itemDetails = [];
            foreach ($request->cart as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    throw new \Exception('Product not found: ' . $item['product_id']);
                }

                $itemDetails[] = [
                    'id' => $product->id,
                    'price' => (int)$item['total_pembayaran'],
                    'quantity' => (int)$item['kuantitas'],
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
                    'credit_card',
                    'bca_va',
                    'bni_va',
                    'bri_va',
                    'mandiri_clickpay',
                    'gopay',
                    'shopeepay'
                ],
                'callbacks' => [
                    'finish' => url('/product'),
                    'error' => url('/cart'),
                    'pending' => url('/product')
                ]
            ];

            // Verify total matches sum of items
            $itemsTotal = collect($itemDetails)->sum(function ($item) {
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

                // Create transaction details - PERBAIKAN UTAMA DI SINI
                foreach ($request->cart as $index => $item) {
                    $detailTransaction = new DetailTransaction();
                    $detailTransaction->id_transaksi = $transaction->id;
                    $detailTransaction->id_pesanan = $item['order_id'];
                    
                    // PERBAIKAN: Gunakan beberapa strategi untuk mencocokkan file
                    $imagePath = null;
                    
                    // Prioritas 1: Cek berdasarkan product_id
                    if (isset($uploadedFiles[$item['product_id']])) {
                        $imagePath = $uploadedFiles[$item['product_id']];
                        Log::info('Using product ID file:', ['product_id' => $item['product_id'], 'path' => $imagePath]);
                    }
                    // Prioritas 2: Cek berdasarkan index
                    elseif (isset($uploadedFiles[$index])) {
                        $imagePath = $uploadedFiles[$index];
                        Log::info('Using indexed file:', ['index' => $index, 'path' => $imagePath]);
                    }
                    // Prioritas 3: Jika hanya ada single file, gunakan untuk semua item
                    elseif (isset($uploadedFiles['single'])) {
                        $imagePath = $uploadedFiles['single'];
                        Log::info('Using single file for all items:', ['path' => $imagePath]);
                    }
                    // Prioritas 4: Jika ada file dengan index 0, gunakan untuk item pertama
                    elseif ($index === 0 && isset($uploadedFiles[0])) {
                        $imagePath = $uploadedFiles[0];
                        Log::info('Using first uploaded file:', ['path' => $imagePath]);
                    }
                    
                    $detailTransaction->dokumen_tambahan = $imagePath;
                    $detailTransaction->save();
                    
                    Log::info('Detail transaction saved:', [
                        'id_transaksi' => $transaction->id,
                        'id_pesanan' => $item['order_id'],
                        'product_id' => $item['product_id'],
                        'index' => $index,
                        'dokumen_tambahan' => $imagePath
                    ]);
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

public function payWithSaldo(Request $request)
{
    Log::info('Pay with saldo called:', $request->all());
    
    // PERBAIKAN: Debug file uploads di awal
    if ($request->hasFile('custom_images')) {
        Log::info('Files received via hasFile check:', [
            'has_custom_images' => true,
            'custom_images_keys' => array_keys($request->file('custom_images')),
            'file_details' => collect($request->file('custom_images'))->map(function($files, $productId) {
                if (is_array($files)) {
                    return collect($files)->map(function($file) {
                        return [
                            'name' => $file->getClientOriginalName(),
                            'size' => $file->getSize(),
                            'mime' => $file->getMimeType(),
                            'valid' => $file->isValid()
                        ];
                    })->toArray();
                } else {
                    return [
                        'name' => $files->getClientOriginalName(),
                        'size' => $files->getSize(),
                        'mime' => $files->getMimeType(),
                        'valid' => $files->isValid()
                    ];
                }
            })->toArray()
        ]);
    } else {
        Log::info('No files detected via hasFile check');
    }

    // PERBAIKAN: Debug all files in request
    Log::info('All files in request:', [
        'all_files' => $request->allFiles(),
        'has_any_files' => count($request->allFiles()) > 0
    ]);

    try {
        $validated = $request->validate([
            'payment_method' => 'required|in:saldo',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
            'items.*.cart_id' => 'required|integer',
            'total_amount' => 'required|numeric|min:0',
            // PERBAIKAN: Validasi file yang lebih fleksibel
            'custom_images' => 'nullable|array',
            'custom_images.*' => 'nullable', // Biarkan validasi manual di bawah
        ]);

        // PERBAIKAN: Validasi file manual yang lebih robust
        $allFiles = $request->allFiles();
        Log::info('Processing file uploads...', ['total_files' => count($allFiles)]);

        if (!empty($allFiles['custom_images'])) {
            foreach ($allFiles['custom_images'] as $productId => $files) {
                Log::info("Validating files for product {$productId}:", [
                    'files_count' => is_array($files) ? count($files) : 1,
                    'is_array' => is_array($files)
                ]);

                $filesToValidate = is_array($files) ? $files : [$files];
                
                foreach ($filesToValidate as $index => $file) {
                    if (!$file->isValid()) {
                        return response()->json([
                            'success' => false,
                            'message' => "File tidak valid untuk produk: {$productId} (index: {$index})"
                        ], 422);
                    }
                    
                    // Validasi manual untuk file
                    $allowedMimes = ['jpeg', 'png', 'jpg'];
                    $extension = strtolower($file->getClientOriginalExtension());
                    
                    if (!in_array($extension, $allowedMimes)) {
                        return response()->json([
                            'success' => false,
                            'message' => "Format file tidak didukung untuk produk: {$productId}. Hanya JPEG, PNG, JPG yang diperbolehkan."
                        ], 422);
                    }
                    
                    if ($file->getSize() > 2048 * 1024) { // 2MB
                        return response()->json([
                            'success' => false,
                            'message' => "Ukuran file terlalu besar untuk produk: {$productId}. Maksimal 2MB."
                        ], 422);
                    }

                    Log::info("File validation passed:", [
                        'product_id' => $productId,
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'file_extension' => $extension
                    ]);
                }
            }
        } else {
            Log::info('No custom images uploaded');
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 401);
        }

        $totalAmount = (float) $request->total_amount;

        // Cek saldo user
        if ($user->saldo < $totalAmount) {
            return response()->json([
                'success' => false,
                'message' => 'Saldo tidak mencukupi. Saldo Anda: Rp ' . number_format($user->saldo, 0, ',', '.') .
                    ', Total pembayaran: Rp ' . number_format($totalAmount, 0, ',', '.')
            ], 400);
        }

        $mainTransactionId = 'TR' . time() . rand(100, 999);

        DB::beginTransaction();

        try {
            // Cek apakah transaksi dengan ID ini sudah ada
            if (Transaction::where('id', $mainTransactionId)->exists()) {
                Log::warning('Duplicate transaction ID detected, regenerating:', ['transaction_id' => $mainTransactionId]);
                $mainTransactionId = 'TR' . time() . rand(100, 999);
            }

            $totalProcessedAmount = 0.0;
            $customImagePaths = [];
            $createdOrders = [];
            $totalFilesUploaded = 0;

            // PERBAIKAN: Proses gambar custom dengan handling yang lebih konsisten
            if (!empty($allFiles['custom_images'])) {
                Log::info('Processing custom images:', ['custom_images' => array_keys($allFiles['custom_images'])]);
                
                foreach ($allFiles['custom_images'] as $productId => $files) {
                    Log::info('Processing files for product:', [
                        'product_id' => $productId, 
                        'files_type' => gettype($files),
                        'is_array' => is_array($files),
                        'files_count' => is_array($files) ? count($files) : 1
                    ]);
                    
                    $customImagePaths[$productId] = [];
                    
                    // PERBAIKAN: Handle both single file and array of files
                    $filesToProcess = is_array($files) ? $files : [$files];
                    
                    foreach ($filesToProcess as $index => $file) {
                        if ($file && $file->isValid()) {
                            try {
                                // PERBAIKAN: Gunakan nama file yang lebih unik dan aman
                                $fileName = time() . '_' . $productId . '_' . $index . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                                $imagePath = $file->storeAs('images/dokumen_tambahan', $fileName, 'public');
                                
                                $customImagePaths[$productId][] = $imagePath;
                                $totalFilesUploaded++;
                                
                                Log::info("File uploaded successfully:", [
                                    'product_id' => $productId,
                                    'index' => $index,
                                    'original_name' => $file->getClientOriginalName(),
                                    'stored_name' => $fileName,
                                    'stored_path' => $imagePath,
                                    'full_path' => storage_path('app/public/' . $imagePath)
                                ]);
                            } catch (\Exception $e) {
                                Log::error("File upload failed for product {$productId} at index {$index}:", [
                                    'error' => $e->getMessage(),
                                    'file_name' => $file->getClientOriginalName()
                                ]);
                                throw new \Exception("Gagal mengunggah file untuk produk {$productId}: " . $e->getMessage());
                            }
                        } else {
                            Log::warning("Invalid file for product {$productId} at index {$index}");
                        }
                    }
                }
                
                Log::info('All custom image paths:', [
                    'paths' => $customImagePaths,
                    'total_files_uploaded' => $totalFilesUploaded
                ]);
            } else {
                Log::info('No custom images uploaded');
            }

            // Buat orders
            foreach ($request->items as $index => $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    throw new \Exception('Produk tidak ditemukan: ' . $item['product_id']);
                }

                if ($product->stok < $item['quantity']) {
                    throw new \Exception('Stok tidak mencukupi untuk produk: ' . $product->nama_produk);
                }

                $itemTotal = (float) ($item['price'] * $item['quantity']);

                $order = Order::create([
                    'id_user' => $user->id,
                    'id_produk' => $item['product_id'],
                    'kuantitas' => $item['quantity'],
                    'total_pembayaran' => $itemTotal,
                    'status' => 'paid',
                    'catatan' => 'Pembayaran dengan saldo - Item ' . ($index + 1),
                    'tanggal_pemesanan' => now()
                ]);

                $createdOrders[] = $order;

                // Kurangi stok produk
                $product->stok -= $item['quantity'];
                $product->save();

                $totalProcessedAmount += $itemTotal;

                Log::info('Order created successfully:', [
                    'product_id' => $item['product_id'],
                    'order_id' => $order->id,
                    'quantity' => $item['quantity'],
                    'item_total' => $itemTotal,
                    'remaining_stock' => $product->stok
                ]);
            }

            // Validasi total
            $tolerance = 0.01;
            if (abs($totalProcessedAmount - $totalAmount) > $tolerance) {
                throw new \Exception('Total pembayaran tidak sesuai. Expected: ' . $totalAmount . ', Processed: ' . $totalProcessedAmount);
            }

            // Buat transaksi utama
            $mainOrderId = $createdOrders[0]->id;

            $transaction = Transaction::create([
                'id' => $mainTransactionId,
                'id_user' => $user->id,
                'id_pesanan' => $mainOrderId,
                'total_pembayaran' => $totalAmount,
                'snap_token' => null,
                'status' => 'success',
                'payment_method' => 'saldo',
                'tanggal_transaksi' => now()
            ]);

            // PERBAIKAN: Buat detail transaksi dengan dokumen tambahan yang benar
            foreach ($request->items as $index => $item) {
                $order = $createdOrders[$index];
                $itemTotal = (float) ($item['price'] * $item['quantity']);
                
                // PERBAIKAN: Ambil path gambar yang sesuai untuk produk ini
                $productId = $item['product_id'];
                $dokumentenTambahan = null;
                
                if (isset($customImagePaths[$productId]) && !empty($customImagePaths[$productId])) {
                    // Jika ada multiple file, simpan sebagai JSON
                    if (count($customImagePaths[$productId]) > 1) {
                        $dokumentenTambahan = json_encode($customImagePaths[$productId]);
                        Log::info("Multiple files for product {$productId}, storing as JSON:", [
                            'files' => $customImagePaths[$productId]
                        ]);
                    } else {
                        $dokumentenTambahan = $customImagePaths[$productId][0];
                        Log::info("Single file for product {$productId}:", [
                            'file' => $dokumentenTambahan
                        ]);
                    }
                }

                $detailTransaction = DetailTransaction::create([
                    'id_transaksi' => $transaction->id,
                    'id_pesanan' => $order->id,
                    'id_produk' => $item['product_id'],
                    'kuantitas' => $item['quantity'],
                    'harga_satuan' => $item['price'],
                    'total' => $itemTotal,
                    'dokumen_tambahan' => $dokumentenTambahan
                ]);

                Log::info('Detail transaction created:', [
                    'detail_id' => $detailTransaction->id,
                    'transaction_id' => $transaction->id,
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'dokumen_tambahan' => $dokumentenTambahan,
                    'dokumen_tambahan_length' => $dokumentenTambahan ? strlen($dokumentenTambahan) : 0
                ]);
            }

            // Kurangi saldo user
            $user->saldo = $user->saldo - $totalAmount;
            $user->save();

            DB::commit();

            Log::info('Saldo payment successful:', [
                'transaction_id' => $mainTransactionId,
                'user_id' => $user->id,
                'remaining_saldo' => $user->saldo,
                'amount_paid' => $totalAmount,
                'items_count' => count($request->items),
                'orders_created' => count($createdOrders),
                'uploaded_files' => $customImagePaths,
                'total_files_uploaded' => $totalFilesUploaded
            ]);

            return response()->json([
                'success' => true,
                'transaction_id' => $mainTransactionId,
                'payment_method' => 'saldo',
                'remaining_saldo' => $user->saldo,
                'orders_created' => array_map(function ($order) {
                    return $order->id;
                }, $createdOrders),
                'transactions_created' => [$transaction->id],
                'uploaded_files' => $customImagePaths,
                'total_files_uploaded' => $totalFilesUploaded,
                'message' => 'Pembayaran dengan saldo berhasil. Sisa saldo Anda: Rp ' . number_format($user->saldo, 0, ',', '.'),
                'redirect_url' => '/product'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Saldo payment error:', ['error' => $e->getMessage()]);
            throw $e;
        }

    } catch (\Exception $e) {
        Log::error('Pay with saldo error:', ['error' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
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
