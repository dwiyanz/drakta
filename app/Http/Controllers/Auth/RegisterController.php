<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    // Menampilkan halaman form registrasi
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Menangani pendaftaran pengguna
    public function register(Request $request)
    {
        // Validasi input dari form
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'telepon' => 'required|string|max:15',
            'alamat' => 'required|string',
            'jenis_rekening' => 'nullable|string|max:255',
            'no_rekening' => 'required|string|max:255|unique:users,no_rekening',
        ]);

        // Jika validasi gagal, kembali ke form dengan pesan error dan input lama
        if ($validator->fails()) {
            return redirect()
                ->route('register') // pastikan route ini menunjuk ke showRegistrationForm()
                ->withErrors($validator)
                ->withInput();
        }

        // Buat user baru
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'jenis_rekening' => $request->jenis_rekening,
            'no_rekening' => $request->no_rekening,
        ]);

        // Redirect ke login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan login.');
    }

        public function checkUsername(Request $request)
    {
        $username = $request->query('username');
        $exists = User::where('username', $username)->exists();

        return response()->json(['available' => !$exists]);
    }

}
