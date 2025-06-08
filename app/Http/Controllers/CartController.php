<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Order; // Pastikan model Order di-import jika digunakan
use App\Models\Product; // Pastikan model Product di-import
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage; // Import Storage facade

class CartController extends Controller
{
    /**
     * Menampilkan isi keranjang belanja untuk pengguna yang sedang login.
     * Menggunakan metode getCartByUser untuk mengambil data.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Panggil metode getCartByUser untuk mendapatkan data keranjang
        // getCartByUser mengembalikan JsonResponse, kita perlu ekstrak datanya
        $response = $this->getCartByUser();
        $cartItems = json_decode($response->getContent(), true); // Decode JSON response

        // Periksa status code respon dari getCartByUser
        if ($response->getStatusCode() !== 200) {
            // Jika ada error saat mengambil keranjang, kembalikan view dengan pesan error
            // Anda bisa menyesuaikan pesan error dan tampilannya di view cart.blade.php
            return view('cart.index', [
                'cart' => [], // Keranjang kosong
                'error_message' => $cartItems['message'] ?? 'Gagal memuat keranjang.'
            ]);
        }

        // Kirim data keranjang ke view cart.blade.php
        // Data cartItems sudah dalam bentuk array setelah json_decode
        return view('cart.index', ['cart' => $cartItems]);
    }

    /**
     * Menyimpan atau memperbarui produk di keranjang.
     * Dipanggil via AJAX dari frontend.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validasi data yang dikirim dari frontend
        $validated = $request->validate([
            'id_produk' => 'required|string|exists:products,id',
            'kuantitas' => 'required|integer|min:1',
            // Subtotal dihitung di backend untuk mencegah manipulasi harga dari frontend
            // 'subtotal' => 'required|numeric|min:0', // Hapus validasi subtotal dari request
        ]);

        // Ambil ID user dari sesi
        $userId = Auth::id();

        if (!$userId) {
            // Jika user belum login, kembalikan respon error
            return response()->json(['error' => 'User belum login.'], 401);
        }

        try {
             // Cari produk untuk mendapatkan harga sebenarnya
            $product = Product::findOrFail($validated['id_produk']);

            // Hitung subtotal di backend
            $subtotal = $validated['kuantitas'] * $product->harga;

            // Periksa apakah produk sudah ada di keranjang pengguna yang sedang login
            $existingCart = Cart::where('id_user', $userId)
                                 ->where('id_produk', $validated['id_produk'])
                                 ->first();

            if ($existingCart) {
                // Jika produk sudah ada, tambahkan kuantitas dan perbarui subtotal
                $existingCart->kuantitas += $validated['kuantitas'];
                $existingCart->subtotal = $existingCart->kuantitas * $product->harga; // Hitung ulang subtotal total item ini
                $existingCart->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Kuantitas produk berhasil diperbarui di keranjang.',
                    'cart_item' => $existingCart->load('product'), // Muat ulang relasi product
                    'action' => 'updated' // Indikasi bahwa item diperbarui
                ]);
            } else {
                // Jika belum ada, buat entri baru di tabel carts
                $cart = Cart::create([
                    'id_user' => $userId,
                    'id_produk' => $validated['id_produk'],
                    'kuantitas' => $validated['kuantitas'],
                    'subtotal' => $subtotal, // Gunakan subtotal yang dihitung di backend
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil ditambahkan ke keranjang.',
                    'cart_item' => $cart->load('product'), // Muat relasi product
                     'action' => 'added' // Indikasi bahwa item baru ditambahkan
                ]);
            }
        } catch (ModelNotFoundException $e) {
             return response()->json(['error' => 'Produk tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error('Error adding to cart:', ['error' => $e->getMessage(), 'user_id' => $userId, 'request_data' => $request->all()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menambahkan produk ke keranjang.'], 500);
        }
    }


    /**
     * Mengambil data keranjang untuk pengguna yang sedang login.
     * Mengembalikan respon JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCartByUser()
    {
        try {
            $userId = Auth::id();
            if (!Auth::id()) {
                // Jika user belum login, kembalikan respon error
                return response()->json(['message' => 'User belum login.'], 401);
            }

            // Ambil item keranjang beserta relasi produk
            $cartItems = Cart::with(['product' => function($query) {
            $query->select('id', 'nama_produk', 'harga', 'gambar');
            }])
            ->where('id_user', $userId)
            ->orderBy('created_at', 'desc') // <-- Tambahkan ini
            ->get();

            // Transformasi data untuk memastikan path gambar benar dan data sesuai kebutuhan frontend
            $transformedItems = $cartItems->map(function ($item) {
                // Pastikan produk terkait ada
                if (!$item->product) {
                    return null; // Abaikan item keranjang jika produk tidak ditemukan
                }

                // Bentuk path gambar
                $imagePath = $item->product->gambar;
                // Pastikan path gambar dimulai dengan 'images/' jika belum
                if ($imagePath && strpos($imagePath, 'images/') !== 0) {
                     $imagePath = 'images/' . $imagePath;
                }

                // Cek apakah file gambar ada di storage publik
                // Gunakan Storage::disk('public')->exists() untuk cek di storage
                $imageUrl = $imagePath && Storage::disk('public')->exists($imagePath)
                            ? asset('storage/' . $imagePath)
                            : 'https://placehold.co/80x80/e2e8f0/94a3b8?text=Tidak+Ada'; // Placeholder jika gambar tidak ada

                return [
                    'id' => $item->id, // ID item di tabel carts
                    'id_user' => $item->id_user,
                    'id_produk' => $item->id_produk, // ID produk
                    'kuantitas' => $item->kuantitas,
                    'subtotal' => $item->subtotal, // Subtotal per item
                    'product' => [
                        'id' => $item->product->id, // ID produk
                        'nama_produk' => $item->product->nama_produk,
                        'harga' => $item->product->harga,
                        'gambar_url' => $imageUrl // URL gambar yang siap digunakan
                    ]
                ];
            })->filter(); // Hapus item yang null (produk tidak ditemukan)

            return response()->json($transformedItems);

        } catch (\Exception $e) {
            \Log::error('Error pada getCartByUser:', ['error' => $e->getMessage(), 'user_id' => Auth::id()]);
            return response()->json([
                'message' => 'Terjadi kesalahan saat memuat keranjang.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Proses checkout keranjang.
     * (Logika ini sudah ada di controller Anda, tetap dipertahankan)
     */
    public function checkout(Request $request)
    {
        try {
            // Ambil data keranjang pengguna
            $cartItems = Cart::where('id_user', Auth::id())->get(); // Ambil dari model Cart

            if ($cartItems->isEmpty()) {
                return response()->json(['message' => 'Keranjang kosong!'], 422);
            }

            $totalAmount = 0;
            $orderData = [];

            DB::beginTransaction();

            // Menghitung total dan menyiapkan data untuk order
            foreach ($cartItems as $item) {
                // Menggunakan relasi product untuk mendapatkan harga
                $product = $item->product; // Asumsi relasi product() sudah didefinisikan di model Cart

                 if (!$product) {
                     // Handle case where product is not found (e.g., product was deleted)
                     DB::rollBack();
                     return response()->json(['message' => 'Produk di keranjang tidak valid.'], 400);
                 }

                $subtotal = $item->kuantitas * $product->harga; // Menghitung subtotal per produk
                $totalAmount += $subtotal;

                // Buat data order baru di tabel orders
                Order::create([
                    'id_produk' => $item->id_produk,
                    'id_user' => Auth::id(),
                    'kuantitas' => $item->kuantitas,
                    'total_pembayaran' => $subtotal, // Gunakan subtotal per item
                    // Tambahkan kolom lain yang relevan untuk tabel orders jika ada
                    'tanggal_pesanan' => now(), // Contoh: tambahkan tanggal pesanan
                    'status' => 'pending', // Contoh: status awal pesanan
                ]);
            }

            // Hapus data dari tabel keranjang setelah checkout
            Cart::where('id_user', Auth::id())->delete();

            DB::commit();

            return response()->json([
                'message' => 'Pesanan berhasil dibuat.',
                'total_pembayaran' => $totalAmount,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error saat checkout:', ['error' => $e->getMessage(), 'user_id' => Auth::id()]);
            return response()->json(['message' => 'Terjadi kesalahan saat checkout, coba lagi.'], 500);
        }
    }

    /**
     * Menghapus produk dari keranjang berdasarkan ID item keranjang.
     * (Logika ini sudah ada di controller Anda, tetap dipertahankan)
     *
     * @param int $cartId ID item di tabel carts
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($cartId)
    {
        try {
            // Cari item keranjang berdasarkan ID dan pastikan milik user yang login
            $cartItem = Cart::where('id', $cartId)->where('id_user', Auth::id())->first();

            if ($cartItem) {
                $cartItem->delete();  // Menghapus item dari keranjang
                return response()->json(['success' => true, 'message' => 'Item berhasil dihapus dari keranjang.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Item keranjang tidak ditemukan atau bukan milik Anda.'], 404);
            }
        } catch (\Exception $e) {
             \Log::error('Error removing from cart:', ['error' => $e->getMessage(), 'cart_item_id' => $cartId, 'user_id' => Auth::id()]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus item.'], 500);
        }
    }

    /**
     * Mengupdate jumlah produk di keranjang berdasarkan ID item keranjang.
     * (Logika ini sudah ada di controller Anda, tetap dipertahankan)
     *
     * @param Request $request
     * @param int $cartId ID item di tabel carts
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $cartId)
    {
        try {
            // Cari item keranjang berdasarkan ID dan pastikan milik user yang login
            $cart = Cart::where('id', $cartId)->where('id_user', Auth::id())->firstOrFail();

            // Validate the request
            $validated = $request->validate([
                'kuantitas' => 'required|integer|min:1'
            ]);

            // Ambil produk terkait untuk mendapatkan harga dan stok
            $product = $cart->product; // Asumsi relasi product() sudah didefinisikan di model Cart

            if (!$product) {
                 return response()->json(['success' => false, 'message' => 'Produk terkait tidak ditemukan.'], 404);
            }

            // Validasi stok: pastikan kuantitas yang diminta tidak melebihi stok produk
            if ($validated['kuantitas'] > $product->stok) {
                 return response()->json(['success' => false, 'message' => 'Kuantitas melebihi stok yang tersedia (' . $product->stok . ').'], 422);
            }


            // Update the cart quantity
            $cart->kuantitas = $validated['kuantitas'];

            // Recalculate subtotal menggunakan harga dari produk
            $cart->subtotal = $cart->kuantitas * $product->harga;

            $cart->save();

            return response()->json([
                'success' => true,
                'message' => 'Kuantitas berhasil diupdate.',
                'data' => $cart->load('product') // Muat ulang relasi product untuk respon
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Item keranjang tidak ditemukan atau bukan milik Anda.'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error updating cart:', ['error' => $e->getMessage(), 'cart_item_id' => $cartId, 'user_id' => Auth::id(), 'request_data' => $request->all()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate kuantitas.'
            ], 500);
        }
    }

    // Metode lain yang mungkin Anda butuhkan:
    // public function clearCart() { ... } // Mengosongkan seluruh keranjang user

}