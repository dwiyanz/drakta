@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-4 px-4 sm:py-8 bg-gradient-to-br from-indigo-50 to-purple-50 overflow-y-auto">
    <div class="w-full max-w-sm md:max-w-md bg-white rounded-2xl shadow-xl p-5 sm:p-6 relative overflow-hidden transform transition-all duration-300 ease-in-out hover:scale-[1.01]">
        <div class="absolute -top-8 -left-8 w-40 h-40 bg-purple-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
        <div class="absolute -bottom-8 -right-8 w-40 h-40 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>

        <button
            type="button"
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition-colors duration-200 focus:outline-none z-20 p-1.5 rounded-full hover:bg-gray-100"
            aria-label="Close"
            onclick="window.history.back();"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <h1 class="text-center text-2xl font-extrabold text-gray-900 mb-6 relative z-10 tracking-tight leading-tight">Buat Akun Baru</h1>
        @if ($errors->has('username'))
            <script>
                alert("Username sudah terpakai. Silakan masukkan username lain.");
            </script>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-3 relative z-10">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="name">Nama Lengkap</label>
                <input
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm placeholder-gray-500 transition duration-200 ease-in-out hover:border-gray-300"
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Masukkan nama lengkap Anda"
                    required
                >
                @error('name')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="username">Username</label>
                <input
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm placeholder-gray-500 transition duration-200 ease-in-out hover:border-gray-300"
                    type="text"
                    id="username" {{-- Pastikan ada ID ini --}}
                    name="username"
                    value="{{ old('username') }}"
                    placeholder="Masukkan username unik"
                    required
                >
                @error('username')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
                {{-- Ini adalah tempat peringatan username akan muncul --}}
                <div id="username-warning" class="text-red-500 text-xs mt-1 hidden"></div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="email">Alamat E-mail</label>
                <input
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm placeholder-gray-500 transition duration-200 ease-in-out hover:border-gray-300"
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="contoh@email.com"
                    required
                >
                @error('email')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="password">Kata Sandi</label>
                    <input
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm placeholder-gray-500 transition duration-200 ease-in-out hover:border-gray-300"
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Min. 8 karakter"
                        required
                    >
                    @error('password')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="password_confirmation">Konfirmasi Sandi</label>
                    <input
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm placeholder-gray-500 transition duration-200 ease-in-out hover:border-gray-300"
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Ulangi sandi"
                        required
                    >
                    @error('password_confirmation')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="telepon">No. Telepon</label>
                <input
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm placeholder-gray-500 transition duration-200 ease-in-out hover:border-gray-300"
                    type="tel"
                    id="telepon"
                    name="telepon"
                    value="{{ old('telepon') }}"
                    placeholder="08xxxxxxxxxx"
                    required
                >
                @error('telepon')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="alamat">Alamat Lengkap</label>
                <textarea
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm placeholder-gray-500 transition duration-200 ease-in-out hover:border-gray-300"
                    id="alamat"
                    name="alamat"
                    rows="2"
                    placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan, Kota, Kode Pos"
                    required
                >{{ old('alamat') }}</textarea>
                @error('alamat')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Bagian Informasi Rekening --}}
            <div class="pt-4 mt-4 border-t border-gray-200">
                <p class="text-base font-semibold text-gray-800 mb-3 text-center">Informasi Rekening</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="jenis_rekening">Jenis Rekening</label>
                        <input
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm placeholder-gray-500 transition duration-200 ease-in-out hover:border-gray-300"
                            type="text"
                            id="jenis_rekening"
                            name="jenis_rekening"
                            value="{{ old('jenis_rekening') }}"
                            placeholder="Contoh: BCA, Mandiri"
                        >
                        @error('jenis_rekening')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="no_rekening">No. Rekening</label>
                        <input
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm placeholder-gray-500 transition duration-200 ease-in-out hover:border-gray-300"
                            type="text"
                            id="no_rekening"
                            name="no_rekening"
                            value="{{ old('no_rekening') }}"
                            placeholder="Contoh: 1234567890"
                        >
                        @error('no_rekening')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div>
                <button
                    type="submit"
                    class="w-full flex justify-center py-3 px-3 border border-transparent rounded-lg shadow-md text-base font-semibold text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-purple-600 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-700 transition duration-300 ease-in-out transform hover:-translate-y-0.5 hover:shadow-xl"
                >
                    Daftar Sekarang
                </button>
            </div>

            <div class="text-center text-xs text-gray-600 mt-4">
                Sudah punya akun?
                <a href="javascript:void(0)" onclick="openLoginModal()" class="font-medium text-blue-600 hover:text-purple-600 transition-colors duration-200">
                    Masuk di sini
                </a>
            </div>  
        </form>
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

<style>
    /* Keyframes untuk animasi blob latar belakang */
    @keyframes blob {
        0% {
            transform: translate(0, 0) scale(1);
        }
        33% {
            transform: translate(30px, -50px) scale(1.1);
        }
        66% {
            transform: translate(-20px, 20px) scale(0.9);
        }
        100% {
            transform: translate(0, 0) scale(1);
        }
    }

    .animate-blob {
        animation: blob 7s infinite cubic-bezier(0.6, 0.2, 0.4, 0.8);
    }

    .animation-delay-2000 {
        animation-delay: 2s;
    }
</style>
@endsection

{{-- Tambahkan script ini di bagian bawah blade file, sebelum </body> --}}
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

    // ===== USERNAME VALIDATION =====
    const usernameInput = document.getElementById('username');
    const usernameWarning = document.getElementById('username-warning');

    if (!usernameWarning) {
        console.error('Elemen #username-warning tidak ditemukan!');
        return;
    }
    
    usernameWarning.classList.add('hidden');

    let debounceTimeout;
    const MIN_USERNAME_LENGTH = 5;

    function displayWarning(message, isError = true) {
        usernameWarning.textContent = message;
        usernameWarning.classList.remove('hidden');
        if (isError) {
            usernameWarning.classList.remove('text-green-600');
            usernameWarning.classList.add('text-red-600');
        } else {
            usernameWarning.classList.remove('text-red-600');
            usernameWarning.classList.add('text-gray-600');
        }
    }

    function hideWarning() {
        usernameWarning.textContent = '';
        usernameWarning.classList.add('hidden');
    }

    function checkUsernameAvailability(username) {
        displayWarning('Memeriksa username...', false);

        // Ganti dengan route yang benar sesuai dengan aplikasi Anda
        fetch(`/check-username?username=${encodeURIComponent(username)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.exists) {
                    displayWarning('Username ini sudah terdaftar.');
                } else {
                    hideWarning();
                }
            })
            .catch(error => {
                console.error('Error checking username:', error);
                displayWarning('Terjadi kesalahan saat memeriksa username.');
            });
    }

    if (usernameInput) {
        usernameInput.addEventListener('input', function () {
            clearTimeout(debounceTimeout);
            const username = this.value.trim();

            if (username.length === 0) {
                hideWarning();
                return;
            }

            if (username.length < MIN_USERNAME_LENGTH) {
                displayWarning(`Username minimal ${MIN_USERNAME_LENGTH} karakter.`);
                return;
            }

            debounceTimeout = setTimeout(() => {
                checkUsernameAvailability(username);
            }, 500);
        });

        usernameInput.addEventListener('blur', function() {
            clearTimeout(debounceTimeout);
            const username = this.value.trim();

            if (username.length === 0) {
                hideWarning();
                return;
            }

            if (username.length < MIN_USERNAME_LENGTH) {
                displayWarning(`Username minimal ${MIN_USERNAME_LENGTH} karakter.`);
            } else {
                checkUsernameAvailability(username);
            }
        });
    }
});

    
</script>