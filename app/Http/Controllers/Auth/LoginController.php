<?php

namespace App\Http\Controllers\Auth; // Sesuaikan namespace jika berbeda

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use App\Models\User; // <-- Pastikan Anda mengimpor model User Anda
// use Illuminate\Validation\ValidationException; // Jika Anda ingin kembali menggunakan ini

class LoginController extends Controller
{
    public function username()
    {
        return 'username';
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('login')
                        ->withErrors($validator)
                        ->withInput($request->except('password'));
        }

        $credentials = $request->only($this->username(), 'password');
        $usernameInput = $request->input($this->username());
        $passwordInput = $request->input('password');
        $remember = $request->boolean('remember');

        // 1. Cek apakah username ada di database
        $user = User::where($this->username(), $usernameInput)->first();

        if (!$user) {
            // Username tidak ditemukan
            $errors = new MessageBag([
                $this->username() => [__('Username tidak ditemukan.')],
                // Kita bisa juga menambahkan error di password dengan pesan yang sama jika ingin "keduanya salah"
                // 'password' => [__('Username atau password salah.')]
            ]);
            return redirect()->route('login')
                        ->withErrors($errors)
                        ->withInput($request->except('password'));
        }

        // 2. Jika username ada, coba autentikasi dengan password yang diberikan
        if (Auth::attempt($credentials, $remember)) {
            // Login berhasil
            $request->session()->regenerate();
            if (auth()->user()->role === 'admin') {
                return redirect()->intended(route('admin.home'));
            }
            return redirect()->intended(route('pelanggan.home'));
        } else {
            // Username benar, tetapi password salah
            $errors = new MessageBag([
                'password' => [__('Password salah.')],
                // Kita bisa juga menambahkan error di username dengan pesan yang sama jika ingin "keduanya salah"
                // $this->username() => [__('Username atau password salah.')]
            ]);
            return redirect()->route('login')
                        ->withErrors($errors)
                        ->withInput($request->except('password'));
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}