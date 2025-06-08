<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini jika belum ada
use Illuminate\Support\Facades\Log; // Tambahkan ini untuk logging jika diperlukan

class UserController extends Controller
{
    /**
     * Menampilkan daftar user.
     * Hanya bisa diakses oleh admin.
     */
    public function index(Request $request)
    {
        // Proteksi rute, hanya admin yang bisa mengakses
        if (!(Auth::check() && Auth::user()->role === 'admin')) {
            return redirect()->route('pelanggan.home')->with('error', 'Anda tidak memiliki akses.');
        }

        $query = User::query();
        $perPage = $request->input('entries', 10);

        // Search functionality first
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                // Menggunakan 'id' bukan 'id_user' jika primary key di model User adalah 'id'
                $q->where('id', 'LIKE', "%{$searchTerm}%")
                ->orWhere('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('username', 'LIKE', "%{$searchTerm}%")
                ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                ->orWhere('telepon', 'LIKE', "%{$searchTerm}%")
                ->orWhere('alamat', 'LIKE', "%{$searchTerm}%")
                ->orWhere('role', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Role filter after search
        if ($request->has('role_filter') && $request->role_filter != 'all') {
            $query->where('role', $request->role_filter);
        }

        // Mengurutkan berdasarkan ID dari terkecil ke terbesar
        $users = $query->orderBy('id', 'asc')->paginate($perPage)->withQueryString();

        // Mengirim data ke view 'admin.user'
        return view('admin.user', compact('users'));
    }

    /**
     * Menampilkan form untuk mengedit user.
     * Hanya bisa diakses oleh admin.
     */
    public function edit($id)
    {
         // Proteksi rute, hanya admin yang bisa mengakses
        if (!(Auth::check() && Auth::user()->role === 'admin')) {
            return redirect()->route('pelanggan.home')->with('error', 'Anda tidak memiliki akses.');
        }
        // Cari user berdasarkan ID
        $user = User::findOrFail($id);

        // Return view untuk edit user
        return view('admin.edit-user', compact('user'));
    }

    /**
     * Memperbarui data user di database.
     * Hanya bisa diakses oleh admin.
     */
    public function update(Request $request, $id)
    {
         // Proteksi rute, hanya admin yang bisa mengakses
        if (!(Auth::check() && Auth::user()->role === 'admin')) {
            return redirect()->route('pelanggan.home')->with('error', 'Anda tidak memiliki akses.');
        }
        // Validasi data
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telepon' => 'required|string|max:15',
            'alamat' => 'nullable|string|max:255',
            // Mengganti 'user' menjadi 'pelanggan' jika role pelanggan disimpan sebagai 'pelanggan' di database
            'role' => 'required|in:admin,pelanggan',
        ]);

        // Update data user
        $user = User::findOrFail($id);
        // Menggunakan $request->only() untuk hanya mengambil field yang divalidasi
        $user->update($request->only(['name', 'username', 'email', 'telepon', 'alamat', 'role']));

        // Redirect kembali ke halaman daftar user admin dengan pesan sukses
        // PERBAIKAN DI SINI: Mengganti route('manajemen-user') menjadi route('admin.users.index')
        return redirect()->route('admin.users-index')->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Menghapus user dari database.
     * Hanya bisa diakses oleh admin.
     */
    public function destroy($id)
    {
         // Proteksi rute, hanya admin yang bisa mengakses
        if (!(Auth::check() && Auth::user()->role === 'admin')) {
            return redirect()->route('pelanggan.home')->with('error', 'Anda tidak memiliki akses.');
        }
        // Hapus user berdasarkan ID
        $user = User::findOrFail($id);
        $user->delete();

        // Redirect kembali ke halaman daftar user admin dengan pesan sukses
        // PERBAIKAN DI SINI: Mengganti route('manajemen-user') menjadi route('admin.users.index')
        return redirect()->route('admin.users-index')->with('success', 'Data user berhasil dihapus.');
    }
}
