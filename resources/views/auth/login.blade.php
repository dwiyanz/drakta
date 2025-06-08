
@extends('layouts.app')

@section('content')
<div data-login-content>
<div class="flex items-center justify-center rounded-lg p-6">
    <div class="shadow-2xl overflow-hidden w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 transform transition-all duration-500 hover:scale-[1.005] hover:shadow-3xl z-[999]">

        <div class="hidden md:block bg-cover bg-center relative" style="background-image: url('/images/coverperusahaan.jpg')">
            {{-- Optional: Subtle overlay for image --}}
            <div class="absolute inset-0 bg-black opacity-20 rounded-l-[2.5rem]"></div>
        </div>

        <div class="p-8 md:p-12"> {{-- Sedikit penyesuaian padding untuk konsistensi --}}
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 text-center mb-8 tracking-tight">Selamat Datang Kembali</h2>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input id="username" name="username" type="text" value="{{ old('username') }}" required {{-- Pastikan name="username" --}}
                        class="w-full px-4 py-3 border rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:outline-none transition duration-200
                  {{ $errors->has('username') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }}"
                        placeholder="Masukkan username Anda" aria-describedby="username-error">
                    @error('username') {{-- Ini akan menangkap error dengan kunci 'username' --}}
                    <p class="text-sm text-red-600 mt-1" id="username-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input id="password" name="password" type="password" required
                        class="w-full px-4 py-3 border rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:outline-none transition duration-200
                  {{ $errors->has('password') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }}"
                        placeholder="Masukkan kata sandi Anda" aria-describedby="password-error">
                    @error('password')
                    <p class="text-sm text-red-600 mt-1" id="password-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-sm">
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">
                        Lupa kata sandi?
                    </a>
                    @endif
                    {{-- Ganti javascript:void(0); dan openRegisterModal() dengan route yang benar jika ada halaman registrasi --}}
                    {{-- Contoh: <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">Daftar</a> --}}
                    <a href="javascript:void(0);" onclick="openRegisterModal()" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">
                        Daftar
                    </a>
                </div>

                <button type="submit"
                    class="w-full py-3 px-6 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-semibold rounded-xl shadow-lg
                               hover:from-indigo-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                               transition duration-300 ease-in-out transform hover:-translate-y-0.5">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</div>
</div>
@endsection