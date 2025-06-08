<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk atau detail produk tunggal.
     * Digunakan untuk tampilan admin (daftar) dan pelanggan (daftar/detail).
     *
     * @param Request $request
     * @param int|null $id Opsional: ID produk untuk menampilkan detail.
     * @return \Illuminate\View\View
     */
    public function index(Request $request, $id = null)
{
    $searchQuery = $request->input('query');
    $viewData = [
        'searchQuery' => $searchQuery,
        'isAdminView' => false,
        'transaction' => null,
        'product' => null,
        'products' => collect(),
    ];

    // Cek apakah ID produk diberikan di URL
    if ($id) {
        // Jika ID produk diberikan, ambil detail produk tunggal
        $product = Product::findOrFail($id);
        $viewData['product'] = $product;
        $viewData['isAdminView'] = false;
        
        // Untuk halaman detail produk, tetap tampilkan semua produk juga
        // agar JavaScript bisa mengakses data produk untuk modal
        $products = Product::with('orders.transactions')->orderBy('id', 'asc')->get();
        $viewData['products'] = $products;
        
    } elseif (Auth::check() && Auth::user()->role === 'admin') {
        // Admin view logic (tidak berubah)
        $viewData['isAdminView'] = true;
        if ($searchQuery) {
            $products = Product::where('nama_produk', 'like', '%' . $searchQuery . '%')
                             ->orWhere('deskripsi', 'like', '%' . $searchQuery . '%')
                             ->orWhere('bahan', 'like', '%' . $searchQuery . '%')
                             ->orderBy('id', 'asc')
                             ->paginate(10);
        } else {
            $products = Product::orderBy('id', 'asc')->paginate(10);
        }
        $viewData['products'] = $products;
        
    } else {
        // Customer/guest view logic (tidak berubah)
        $viewData['isAdminView'] = false;
        if ($searchQuery) {
            $products = Product::where('nama_produk', 'like', '%' . $searchQuery . '%')
                             ->orWhere('deskripsi', 'like', '%' . $searchQuery . '%')
                             ->orderBy('id', 'asc')
                             ->get();
        } else {
            $products = Product::with('orders.transactions')->orderBy('id', 'asc')->get();
            
            if (!$searchQuery) {
                foreach ($products as $product) {
                    if ($product->orders->isNotEmpty()) {
                        foreach ($product->orders as $order) {
                            if ($order->transactions->isNotEmpty()) {
                                $viewData['transaction'] = $order->transactions->first();
                                break 2;
                            }
                        }
                    }
                }
            }
        }
        $viewData['products'] = $products;
    }

    return view('product.index', $viewData);
}

    /**
     * Menampilkan form untuk membuat produk baru.
     * Hanya bisa diakses oleh admin.
     */
    public function create()
    {
        // Proteksi rute, hanya admin yang bisa mengakses
        if (!(Auth::check() && Auth::user()->role === 'admin')) {
            return redirect()->route('products.index')->with('error', 'Anda tidak memiliki akses.');
        }
        // Mengirim flag isAdminView ke view
        return view('product.create', ['isAdminView' => true]);
    }

    /**
     * Menyimpan produk baru ke database.
     * Hanya bisa diakses oleh admin.
     */
    public function store(Request $request)
    {
        // Proteksi rute, hanya admin yang bisa mengakses
        if (!(Auth::check() && Auth::user()->role === 'admin')) {
            return redirect()->route('product.index')->with('error', 'Anda tidak memiliki akses.');
        }

        // Validasi input dari form
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'bahan' => 'required|string|max:255',
            'ukuran' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048', // Validasi file gambar
        ]);

        // Ambil semua data request kecuali 'gambar'
        $data = $request->except('gambar');

        // Proses upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            // Buat nama unik untuk file gambar
            $imageName = time() . '_' . $image->getClientOriginalName();
            // Simpan file gambar di direktori storage/app/public/images
            $image->storeAs('public/images', $imageName);
            // Simpan path gambar relatif ke database
            $data['gambar'] = 'images/' . $imageName;
        } else {
            // Jika tidak ada gambar diupload, gunakan gambar placeholder
            $data['gambar'] = 'images/placeholder.png';
        }

        // Buat record produk baru di database
        Product::create($data);

        // Redirect kembali ke halaman daftar produk dengan pesan sukses
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit produk.
     * Hanya bisa diakses oleh admin.
     */
    public function edit($id)
    {
        // Proteksi rute, hanya admin yang bisa mengakses
        if (!(Auth::check() && Auth::user()->role === 'admin')) {
            return redirect()->route('products.index')->with('error', 'Anda tidak memiliki akses.');
        }
        // Cari produk yang akan diedit
        $product = Product::findOrFail($id);
        // Mengirim data produk dan flag isAdminView ke view 'product.edit'
        return view('product.edit', compact('product') + ['isAdminView' => true]);
    }

    /**
     * Memperbarui data produk di database.
     * Hanya bisa diakses oleh admin.
     */
    public function update(Request $request, $id)
    {
        // Proteksi rute, hanya admin yang bisa mengakses
        if (!(Auth::check() && Auth::user()->role === 'admin')) {
            return redirect()->route('products.index')->with('error', 'Anda tidak memiliki akses.');
        }
        
        // Validasi input dari form
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'bahan' => 'required|string|max:255',
            'ukuran' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        // Cari produk yang akan diperbarui
        $product = Product::findOrFail($id);
        
        // Ambil semua data request kecuali 'gambar'
        $data = $request->except('gambar');

        // Proses upload gambar baru jika ada
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada dan bukan gambar placeholder
            if ($product->gambar && $product->gambar !== 'images/placeholder.png' && Storage::disk('public')->exists($product->gambar)) {
                Storage::disk('public')->delete($product->gambar);
            }
            
            $image = $request->file('gambar');
            
            // Buat nama unik untuk file gambar baru
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Simpan file gambar baru di direktori storage/app/public/images
            $imagePath = $image->storeAs('images', $imageName, 'public');
            
            // Update path gambar di database (simpan path relatif dari public disk)
            $data['gambar'] = $imagePath; // ini akan menjadi 'images/filename.jpg'
            
            // Debug: Log path untuk memastikan
            \Log::info('Image saved to: ' . $imagePath);
            \Log::info('Full path: ' . Storage::disk('public')->path($imagePath));
        }

        // Perbarui data produk di database
        $product->update($data);

        // Redirect kembali ke halaman daftar produk dengan pesan sukses
        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Menghapus produk dari database.
     * Hanya bisa diakses oleh admin.
     */
    public function destroy($id)
    {
        // Proteksi rute, hanya admin yang bisa mengakses
        if (!(Auth::check() && Auth::user()->role === 'admin')) {
            return redirect()->route('products.index')->with('error', 'Anda tidak memiliki akses.');
        }
        // Cari produk yang akan dihapus
        $product = Product::findOrFail($id);
        // Hapus file gambar terkait jika ada dan bukan gambar placeholder
        if ($product->gambar && $product->gambar !== 'images/placeholder.png' && Storage::disk('public')->exists($product->gambar)) {
            Storage::disk('public')->delete($product->gambar);
        }
        // Hapus record produk dari database
        $product->delete();

        // Redirect kembali ke halaman daftar produk dengan pesan sukses
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    // Metode search() yang terpisah tidak lagi diperlukan karena logika search
    // sudah digabungkan ke dalam metode index().
    // public function search(Request $request) { ... }
}
