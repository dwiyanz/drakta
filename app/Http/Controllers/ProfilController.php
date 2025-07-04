<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfilController extends Controller
{
    /**
     * Menampilkan halaman profil pengguna yang sedang login.
     * Route: GET /profil (profile.show)
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */


    public function showProfile()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login.');
        }

        $user = Auth::user();
        return view('pelanggan.profil-user', compact('user'));
    }

    /**
     * Menampilkan form untuk mengedit profil pengguna yang sedang login.
     * Route: GET /profil/edit (profil.edit)
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit()
    {
        // Pastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengedit profil.');
        }

        // Ambil data pengguna yang sedang login
        $user = Auth::user();

        // Tampilkan view edit profil
        // Menggunakan 'pelanggan.edit-profil' sesuai dengan lokasi file view Anda
        try {
            return view('pelanggan.edit-profil', compact('user'));
        } catch (\InvalidArgumentException $e) {
            Log::error('View pelanggan.edit-profil tidak ditemukan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Halaman edit profil tidak dapat dimuat. View tidak ditemukan.');
        }
    }

    /**
     * Menyimpan perubahan data profil pengguna yang sedang login.
     * Route: PUT /profil (profil.update)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Pastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk memperbarui profil.');
        }

        // Ambil data pengguna yang sedang login
        $user = Auth::user();

        // Validasi input dari form
        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'alamat' => 'nullable|string|max:255',
            'jenis_rekening' => 'nullable|string|max:50',
            'no_rekening' => 'nullable|string|max:50',
        ];

        // LOGIKA PENTING UNTUK PASSWORD:
        // Jika pengguna mengisi field password baru ATAU password lama,
        // maka terapkan validasi khusus untuk password.
        if ($request->filled('password') || $request->filled('current_password')) {
            $rules['current_password'] = [
                'required', // Password lama wajib diisi
                function ($attribute, $value, $fail) use ($user) {
                    // Gunakan nama lengkap (fully qualified name) untuk Hash::check()
                    // Ini adalah cara paling robust untuk memastikan Hash dikenali dalam closure
                    if (!\Illuminate\Support\Facades\Hash::check($value, $user->password)) {
                        $fail('Password lama yang Anda masukkan tidak cocok.');
                    }
                },
            ];
            $rules['password'] = 'required|string|min:8|confirmed';
            $rules['password_confirmation'] = 'required|string|min:8';
        }

        // --- PENTING: BARIS INI HARUS AKTIF DAN TIDAK DIKOMENTARI ---
        // Lakukan validasi semua input berdasarkan aturan yang telah ditetapkan.
        // Jika ada validasi yang gagal (termasuk password lama tidak cocok),
        // Laravel akan otomatis menghentikan eksekusi dan me-redirect kembali
        // ke form dengan pesan error.
        $request->validate($rules);

        // Perbarui data profil pengguna
        $user->name = $request->name;
        $user->username = $request->username;
        $user->telepon = $request->telepon;
        $user->email = $request->email;

        // Jika field 'password' (password baru) diisi, maka update password pengguna.
        // Bagian ini HANYA akan dijalankan jika SEMUA validasi sebelumnya,
        // termasuk validasi 'current_password' (password lama), telah berhasil.
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password); // Hash password baru sebelum disimpan
        }
        // Perbarui alamat, jenis rekening, dan nomor rekening jika ada
        $user->alamat = $request->alamat;
        $user->jenis_rekening = $request->jenis_rekening;
        $user->no_rekening = $request->no_rekening;
        $user->save();

        // Redirect kembali ke halaman profil.show dengan pesan sukses
        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Menghapus akun pengguna yang sedang login.
     * Route: POST /profil/destroy (jika Anda menggunakannya)
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        // Pastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk menghapus akun.');
        }

        // Ambil data pengguna yang sedang login
        $user = Auth::user();

        // Logout pengguna sebelum menghapus akun
        Auth::logout();

        // Hapus akun pengguna
        $user->delete();

        // Redirect ke halaman home publik setelah penghapusan akun
        return redirect('/')->with('success', 'Akun Anda berhasil dihapus.');
    }
}
