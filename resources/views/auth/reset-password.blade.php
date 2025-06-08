@extends('layouts.app')

@section('content')
{{-- Container utama yang mengisi layar dengan gradien latar belakang --}}
<div class="min-h-screen bg-gradient-to-br from-emerald-50 to-teal-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">

    {{-- Kartu formulir --}}
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8 sm:p-10 relative overflow-hidden transform transition-all duration-300 hover:scale-[1.01] hover:shadow-xl">
        {{-- Lapisan gradien transparan untuk sentuhan visual di dalam kartu --}}
        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-500 to-teal-500 opacity-5 rounded-2xl pointer-events-none"></div>

        {{-- Judul Formulir --}}
        <h1 class="text-center text-3xl font-extrabold text-gray-900 mb-8 relative z-10 tracking-tight">Reset Kata Sandi</h1>

        {{-- Formulir --}}
        <form action="{{ route('password.update') }}" method="POST" class="space-y-6 relative z-10">
            @csrf
            {{-- Token tersembunyi untuk reset kata sandi --}}
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Bidang Input Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="email">Alamat Email</label>
                <input
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm placeholder-gray-400 transition duration-200 ease-in-out"
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}" {{-- Menampilkan nilai email yang lama jika ada validasi error --}}
                    placeholder="Masukkan alamat email Anda"
                    required
                >
                @error('email')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Bidang Input Kata Sandi Baru --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="password">Kata Sandi Baru</label>
                <input
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm placeholder-gray-400 transition duration-200 ease-in-out"
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Minimal 8 karakter"
                    required
                >
                @error('password')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Bidang Input Konfirmasi Kata Sandi --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="password_confirmation">Konfirmasi Kata Sandi</label>
                <input
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm placeholder-gray-400 transition duration-200 ease-in-out"
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Ulangi kata sandi baru"
                    required
                >
                @error('password_confirmation') {{-- Error untuk password_confirmation --}}
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tombol Submit --}}
            <div>
                <button
                    type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-lg font-semibold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-teal-600 hover:to-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-700 transition duration-300 ease-in-out transform hover:-translate-y-0.5"
                >
                    Reset Kata Sandi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection