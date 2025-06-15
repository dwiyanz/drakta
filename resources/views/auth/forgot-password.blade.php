@extends('layouts.app')

@section('content')
{{-- Container utama yang mengisi layar dengan gradien latar belakang --}}
<div class="min-h-screen bg-gradient-to-br from-emerald-50 to-teal-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">

    {{-- Kartu formulir --}}
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8 sm:p-10 relative overflow-hidden transform transition-all duration-300 hover:scale-[1.01] hover:shadow-xl">
        {{-- Lapisan gradien transparan untuk sentuhan visual di dalam kartu --}}
        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-500 to-teal-500 opacity-5 rounded-2xl pointer-events-none"></div>

        {{-- Judul Formulir --}}
        <h1 class="text-center text-3xl font-extrabold text-gray-900 mb-8 relative z-10 tracking-tight">Lupa Kata Sandi</h1>

        {{-- Pesan Status (jika ada) --}}
        @if (session('status'))
            <div class="mb-6 bg-emerald-50 border border-emerald-400 text-emerald-700 p-4 rounded-lg shadow-sm relative z-10">
                {{ session('status') }}
            </div>
        @endif

        {{-- Formulir --}}
        <form action="{{ route('password.email') }}" method="POST" class="space-y-6 relative z-10">
            @csrf
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

            {{-- Tombol Submit --}}
            <div>
                <button
                    type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-lg font-semibold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-teal-600 hover:to-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-700 transition duration-300 ease-in-out transform hover:-translate-y-0.5"
                >
                    Kirim Link Reset Kata Sandi
                </button>
            </div>
        </form>

        {{-- Tautan Kembali ke Login --}}
        <div class="text-center mt-6 relative z-10">
            <a class="font-medium text-emerald-600 hover:text-teal-600 transition-colors duration-200" href="{{ route('login') }}">
                Kembali ke Login
            </a>
        </div>
    </div>
    <div id="loginModal"
    class="fixed inset-0 hidden z-[999] flex items-center justify-center px-4 bg-black bg-opacity-50">
        <div class="p-6 bg-white w-full max-w-4xl rounded-3xl relative shadow-2xl overflow-hidden transform translate-y-10">
            <button onclick="closeLoginModal()"
                class="absolute top-4 right-4 text-gray-500 text-3xl z-10">&times;</button>
            <div id="loginModalContent" class="relative z-0">Memuat...</div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    // ===== MODAL LOGIN FUNCTIONS =====
    window.openLoginModal = function() {
        const modal = document.getElementById('loginModal');
        const content = document.getElementById('loginModalContent');
        
        if (!modal || !content) {
            console.error('Modal login tidak ditemukan di halaman ini');
            return;
        }

        modal.classList.remove('hidden');
        content.innerHTML = 'Memuat...';

        fetch('/login')
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const loginWrapper = doc.querySelector('[data-login-content]');

                if (loginWrapper) {
                    content.innerHTML = '';
                    content.appendChild(loginWrapper.cloneNode(true));
                } else {
                    content.innerHTML = '<p class="text-red-500">Konten login tidak ditemukan.</p>';
                }
            })
            .catch(() => {
                content.innerHTML = '<p class="text-red-500">Gagal memuat form login.</p>';
            });
    };

    window.closeLoginModal = function() {
        const modal = document.getElementById('loginModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };
});
</script>
@endsection