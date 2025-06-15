<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DR AKTA PERCETAKAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>

<div class="min-h-screen flex flex-col">
    <header class="w-full md:w-auto bg-gradient-to-r from-green-400 to-teal-500 text-white font-semibold py-2 px-6 rounded-md shadow-md transition duration-300 ease-in-out"
        style="position: sticky; top: 0; z-index: 100; background-color: rgba(19, 162, 157, 0.9);">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">DR AKTA PERCETAKAN</h1>
            @guest
            <span class="text-gray-200">👋 Halo, Selamat Datang</span>
            @else
            <div class="relative">
                @if(Auth::user()->role === 'admin')
                <button class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 ease-in-out focus:outline-none hover:scale-105">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="block">👋 Hai Admin, Logout</a>
                </button>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
                @else
                <div class="relative inline-block text-left">
                    <button id="dropdownButton"
                        class="bg-blue-600 hover:bg-blue-700 text-white
                   px-5 py-2.5 rounded-md shadow-md hover:shadow-lg focus:outline-none
                   focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50
                   transition-all duration-200 ease-in-out
                   hover:-translate-y-1 active:translate-y-0.5
                   font-semibold text-sm tracking-wide"> 👋 Hai, {{ Auth::user()->name }}
                    </button>

                    <div id="dropdownMenu"
                        class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-xl ring-1 ring-black ring-opacity-5 z-50
                transform origin-top-right
                transition-all duration-200 ease-out
                opacity-0 scale-95 invisible">
                        <div class="py-1" role="none">
                            <a href="{{ route('profile') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100" role="menuitem"> Profil & Saldo
                            </a>
                            {{-- <div class="h-px bg-gray-200"></div> --}}
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100" role="menuitem"> Logout
                            </a>
                        </div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        console.log('DOM Content Loaded: Script dropdown dijalankan.'); // Debugging

                        const dropdownButton = document.getElementById('dropdownButton');
                        const dropdownMenu = document.getElementById('dropdownMenu');

                        // --- Debugging: Pastikan elemen ditemukan ---
                        if (!dropdownButton) {
                            console.error('Error: Button dengan ID "dropdownButton" tidak ditemukan!');
                        }
                        if (!dropdownMenu) {
                            console.error('Error: Menu dengan ID "dropdownMenu" tidak ditemukan!');
                        }
                        // --- End Debugging ---

                        // Fungsi untuk menampilkan dropdown
                        function showDropdown() {
                            console.log('Fungsi showDropdown() dipanggil.'); // Debugging
                            dropdownMenu.classList.remove('opacity-0', 'scale-95', 'invisible');
                            dropdownMenu.classList.add('opacity-100', 'scale-100', 'visible');
                            dropdownButton.setAttribute('aria-expanded', 'true');
                        }

                        // Fungsi untuk menyembunyikan dropdown
                        function hideDropdown() {
                            console.log('Fungsi hideDropdown() dipanggil.'); // Debugging
                            dropdownMenu.classList.remove('opacity-100', 'scale-100', 'visible');
                            // Gunakan timeout agar transisi penutupan terlihat sebelum disembunyikan total
                            setTimeout(() => {
                                dropdownMenu.classList.add('opacity-0', 'scale-95', 'invisible');
                                dropdownButton.setAttribute('aria-expanded', 'false');
                            }, 200); // Sesuaikan dengan durasi transisi Tailwind (duration-200)
                        }

                        // Event listener untuk tombol klik
                        if (dropdownButton) { // Pastikan tombol ditemukan sebelum menambahkan event listener
                            dropdownButton.addEventListener('click', function(event) {
                                console.log('Tombol dropdown diklik.'); // Debugging
                                event.stopPropagation(); // Mencegah event bubble ke document
                                if (dropdownMenu.classList.contains('opacity-0')) {
                                    showDropdown();
                                } else {
                                    hideDropdown();
                                }
                            });
                        }


                        // Event listener untuk menutup dropdown saat klik di luar
                        if (dropdownMenu && dropdownButton) { // Pastikan kedua elemen ditemukan
                            document.addEventListener('click', function(event) {
                                // Jika target klik BUKAN bagian dari menu DAN BUKAN tombol
                                if (!dropdownMenu.contains(event.target) && !dropdownButton.contains(event.target)) {
                                    console.log('Klik di luar dropdown/tombol.'); // Debugging
                                    hideDropdown();
                                }
                            });

                            // Opsional: Tutup dropdown saat tombol Escape ditekan
                            document.addEventListener('keydown', function(event) {
                                if (event.key === 'Escape' && dropdownMenu.classList.contains('visible')) {
                                    console.log('Tombol Escape ditekan.'); // Debugging
                                    hideDropdown();
                                }
                            });
                        } else {
                            console.error('Dropdown menu atau button tidak ditemukan, event listener "click outside" tidak diaktifkan.'); // Debugging
                        }
                    });
                </script>
                @endif
            </div>
            @endguest
        </div>
    </header>

    <!-- Navbar Section -->
    @guest
    <div class="bg-white shadow-md rounded-lg mx-auto container" id="navbar" style="position: sticky; top: 60px; z-index: 90; background-color: white;">
        <style>
            .animated-link span {
                display: inline-block;
                /* Memungkinkan transform pada elemen inline */
                transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;
                /* Transisi halus untuk gerakan dan warna */
            }

            .animated-link:hover span {
                transform: translateY(-3px);
                /* Gerakkan teks sedikit ke atas saat di-hover */
                color: #10b981;
                /* Ubah warna teks menjadi green-600 saat di-hover (menggunakan nilai hex Tailwind) */
            }

            .animated-link:hover span:nth-child(odd) {
                transform: translateY(3px);
                /* Kata-kata ganjil bergerak sedikit ke bawah untuk efek bergantian */
            }
        </style>
        <nav class="flex justify-between items-center p-4">
            <div class="flex space-x-6">
                {{-- Bungkus setiap kata dalam span untuk memungkinkan animasi per kata --}}
                {{-- Menggunakan route('home') jika belum login, route('product.index') jika sudah login --}}
                <a href="{{ auth()->check() ? route('product.index') : route('home') }}" class="text-gray-700 hover:text-green-700 animated-link"><span>Home</span></a>
                <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-green-700 animated-link"><span>Katalog</span> <span>Produk</span></a>
                <a href="#" onclick="openLoginModal(); return false;" class="text-gray-700 hover:text-green-700 animated-link"><span>Cek</span> <span>Pesanan</span></a>
                <a href="{{ route('profil-perusahaan') }}" class="text-gray-700 hover:text-green-700 animated-link"><span>Profil</span> <span>Perusahaan</span></a>
            </div>
            {{-- Masuk/Daftar Button with hover effect --}}
            {{-- Menambahkan kelas hover:scale-105 untuk efek membesar saat di-hover --}}
            <button onclick="openLoginModal()"
                class="bg-green-400 hover:bg-green-300 text-gray-900 px-4 py-2 rounded-md focus:outline-none transition-all duration-300 ease-in-out hover:scale-105"> {{-- Menambahkan kelas transisi dan skala hover --}}
                Masuk/Daftar
            </button>
        </nav>
    </div>
    <!-- Modal Login -->
    <div id="loginModal"
        class="fixed inset-0 hidden z-[999] flex items-center justify-center px-4">
        <div class="p-6 bg-white w-full max-w-4xl rounded-3xl relative shadow-2xl overflow-hidden transform translate-y-10">
            <button onclick="closeLoginModal()"
                class="absolute top-4 right-4 text-gray-500 text-3xl z-10">&times;</button>
            <div id="loginModalContent" class="relative z-0">Memuat...</div>
        </div>
    </div>

    <!-- Modal Register -->
    <div id="registerModal"
        class="fixed inset-0 hidden bg-black bg-opacity-50 z-[999] flex items-center justify-center">
        <div class="bg-white w-full max-w-xl p-6 rounded-lg relative shadow-lg">
            <button onclick="closeRegisterModal()"
                class="absolute top-2 right-2 text-gray-500 text-2xl">&times;</button>
            <div id="registerModalContent">Memuat...</div>
        </div>
    </div>

    <script>
        function openLoginModal() {
            const modal = document.getElementById('loginModal');
            const content = document.getElementById('loginModalContent');
            modal.classList.remove('hidden');
            content.innerHTML = 'Memuat...';

            fetch('/login')
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const form = doc.querySelchecector('form'); // ambil elemen <form> saja
                    const mainContent = doc.querySelector('[data-login-content]');
                    if (mainContent) {
                        content.innerHTML = '';
                        content.appendChild(mainContent.cloneNode(true));
                    } else {
                        content.innerHTML = '<p class="text-red-500">Form login tidak ditemukan.</p>';
                    }
                })
                .catch(() => {
                    content.innerHTML = '<p class="text-red-500">Gagal memuat form login.</p>';
                });
        }

        function closeLoginModal() {
            document.getElementById('loginModal').classList.add('hidden');
        }

        function openRegisterModal() {
            const modal = document.getElementById('registerModal');
            const content = document.getElementById('registerModalContent');
            modal.classList.remove('hidden');
            content.innerHTML = 'Memuat...';

            fetch('/register')
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const form = doc.querySelector('form'); // Ambil elemen form-nya
                    if (form) {
                        content.innerHTML = '';
                        content.appendChild(form);
                    } else {
                        content.innerHTML = '<p class="text-red-500">Form pendaftaran tidak ditemukan.</p>';
                    }
                })
                .catch(() => {
                    content.innerHTML = '<p class="text-red-500">Gagal memuat form daftar.</p>';
                });
        }

        function closeRegisterModal() {
            document.getElementById('registerModal').classList.add('hidden');
        }
    </script>

    @else
    @if (Auth::check() && Auth::user()->role === 'admin')
    <div class="bg-white shadow-md rounded-lg mx-auto container" id="navbar" style="position: sticky; top: 60px; z-index: 90; background-color: white;">
        <style>
            .animated-link span {
                display: inline-block;
                /* Memungkinkan transform pada elemen inline */
                transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;
                /* Transisi halus untuk gerakan dan warna */
            }

            .animated-link:hover span {
                transform: translateY(-3px);
                /* Gerakkan teks sedikit ke atas saat di-hover */
                color: #10b981;
                /* Ubah warna teks menjadi green-600 saat di-hover (menggunakan nilai hex Tailwind) */
            }

            .animated-link:hover span:nth-child(odd) {
                transform: translateY(3px);
                /* Kata-kata ganjil bergerak sedikit ke bawah untuk efek bergantian */
            }
        </style>
        {{-- ... kode HTML lainnya di layouts/app.blade.php ... --}}

        <nav class="flex justify-between items-center p-4">
            <div class="flex space-x-6">
                {{-- Bungkus setiap kata dalam span untuk memungkinkan animasi per kata --}}
                {{-- PERBAIKAN: Mengganti route('HomeAdmin') menjadi route('admin.home') --}}
                <a href="{{ route('admin.home') }}" class="text-gray-700 hover:text-green-700 animated-link"><span>Home</span></a>

                {{-- PERBAIKAN: Mengganti route('product.index') menjadi route('products.index') --}}
                {{-- Route index produk publik/pelanggan --}}
                <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-green-700 animated-link"><span>Data</span> <span>Produk</span></a>

                {{-- PERBAIKAN: Mengganti route('data-pemesanan') menjadi route('admin.data-pemesanan.index') --}}
                {{-- Route data pesanan untuk admin --}}
                <a href="{{ route('admin.data-pemesanan.index') }}" class="text-gray-700 hover:text-green-700 animated-link"><span>Data</span> <span>Pemesanan</span></a>

                {{-- PERBAIKAN: Mengganti route('laporan') menjadi route('admin.laporan.index') --}}
                {{-- Route laporan untuk admin --}}
                <a href="{{ route('admin.laporan.index') }}" class="text-gray-700 hover:text-green-700 animated-link"><span>Laporan</span></a>

                {{-- PERBAIKAN: Mengganti route('profil-perusahaan-edit') menjadi route('admin.profil-perusahaan.edit') --}}
                {{-- Route edit profil perusahaan untuk admin --}}
                <a href="{{ route('admin.profil-perusahaan.edit') }}" class="text-gray-700 hover:text-green-700 animated-link"><span>Manajemen</span> <span>Profil</span> <span>Perusahaan</span></a>

                {{-- PERBAIKAN: Mengganti route('manajemen-user') menjadi route('admin.users-index') --}}
                {{-- Route manajemen user untuk admin --}}
                <a href="{{ route('admin.users-index') }}" class="text-gray-700 hover:text-green-700 animated-link"><span>Manajemen</span> <span>User</span></a>

                {{-- PERBAIKAN: Mengganti route('beranda-edit') menjadi route('admin.beranda.edit') --}}
                {{-- Route edit beranda untuk admin --}}
                <a href="{{ route('admin.beranda.edit') }}" class="text-gray-700 hover:text-green-700 animated-link"><span>Manajemen</span> <span>Beranda</span></a>
            </div>
        </nav>

        {{-- ... kode HTML lainnya di layouts/app.blade.php ... --}}

    </div>
    @elseif (Auth::check() && Auth::user()->role === 'pelanggan')

    <div class="bg-white shadow-md rounded-lg mx-auto container" id="navbar" style="position: sticky; top: 60px; z-index: 90; background-color: white;">
        <style>
            .animated-link span {
                display: inline-block;
                /* Memungkinkan transform pada elemen inline */
                transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;
                /* Transisi halus untuk gerakan dan warna */
            }

            .animated-link:hover span {
                transform: translateY(-3px);
                /* Gerakkan teks sedikit ke atas saat di-hover */
                color: #10b981;
                /* Ubah warna teks menjadi green-600 saat di-hover (menggunakan nilai hex Tailwind) */
            }

            .animated-link:hover span:nth-child(odd) {
                transform: translateY(3px);
                /* Kata-kata ganjil bergerak sedikit ke bawah untuk efek bergantian */
            }
        </style>
        <div class="flex justify-between items-center p-4">
            <div class="flex space-x-6">
                {{-- Bungkus setiap kata dalam span untuk memungkinkan animasi per kata --}}
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-green-700 animated-link"><span>Home</span></a>
                <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-green-700 animated-link"><span>Katalog</span> <span>Produk</span></a>
                <a href="{{ route('data-pesanan') }}" class="text-gray-700 hover:text-green-700 animated-link"><span>Cek</span> <span>Pesanan</span></a>
                <a href="{{ route('profil-perusahaan') }}" class="text-gray-700 hover:text-green-700 animated-link"><span>Profil</span> <span>Perusahaan</span></a>
            </div>
            <div class="flex items-center space-x-4 ml-auto">
                <i class="fas fa-shopping-cart text-gray-700 cursor-pointer" id="cartIcon"></i>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="confirmationModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-[9999] hidden flex items-center justify-center">
        <div class="bg-white z-[999] p-6 rounded-lg shadow-lg max-w-sm w-full">
            <h2 class="text-lg font-bold mb-4">Apakah Anda ingin menghapus produk yang dipilih?</h2>
            <div class="flex justify-end space-x-4">
                <button id="confirmDeleteButton" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-400">Ya</button>
                <button id="cancelDeleteButton" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-400">Tidak</button>
            </div>
        </div>
    </div>

    <!-- Sidebar Keranjang -->
    <div id="cartSidebar" class="fixed inset-y-0 right-0 bg-gray-800 bg-opacity-50 z-[999] hidden shadow-xl h-screen flex flex-col">
        <div class="w-[500px] bg-white rounded-lg h-full flex flex-col">
            <div class="relative flex items-center justify-between p-6 shadow-sm">
                <i class="fas fa-arrow-left text-2xl cursor-pointer text-green-500 hover:text-green-700" id="closeCart"></i>
                <h1 class="text-2xl font-bold text-gray-900 absolute left-1/2 transform -translate-x-1/2">Keranjang Saya</h1>
                <div class="w-6"></div>
            </div>

            <div id="cartItems" class="space-y-6 flex-grow p-6 overflow-y-auto">
            </div>

            <div class="border-t border-gray-200 mt-4 pt-4"></div>

            <div id="cartActions" class="bg-gray-100 p-4 rounded-b-lg">
                <div class="flex items-center justify-between mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" id="selectAllCarts" class="mr-2">
                        <span class="text-gray-900">Semua</span>
                    </label>
                    <div class="text-green-900">
                        <p class="text-lg font-bold text-gray-900">Total Bayar: <span class="text-green-600">Rp<span
                                    id="totalAmount">0</span></span></p>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <button id="deleteSelected"
                        class="bg-red-400 text-white px-4 py-2 rounded-md hover:bg-red-300 transition duration-200">
                        Hapus
                    </button>
                    <button id="checkoutButton"
                        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-grey-400 transition duration-200">
                        Checkout (<span id="checkoutCount">0</span>)
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Container Modal -->
    <div id="paymentModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-[999] p-4">
        <!-- Mengubah max-w-lg menjadi max-w-2xl untuk membuatnya lebih lebar -->
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90%] overflow-y-auto transform transition-all duration-300 scale-100 opacity-100">
            <!-- Header Modal -->
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h1 class="text-2xl font-extrabold text-gray-900">Detail Pembayaran</h1>
                <button type="button" id="closeModalButton" class="text-gray-400 hover:text-gray-600 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Daftar Card Produk -->
            <!-- Mengubah p-6 menjadi p-8 untuk padding yang lebih besar -->
            <div id="modalProductCards" class="p-8 space-y-6 border-b border-gray-200">
                <!-- Card Produk akan ditambahkan di sini oleh JavaScript -->
                <p class="text-gray-500 text-center italic">Produk akan ditampilkan di sini...</p>
            </div>

            <!-- Total Harga -->
            <div id="totalHargaContainer" class="p-6 bg-white-600 text-white flex justify-between items-center rounded-b-xl">
                <span class="text-xl font-semibold">Total Harga:</span>
                <span id="totalHarga" class="text-2xl font-bold">Rp 0</span>
            </div>

            <!-- Informasi Saldo -->
            <div class="p-6 bg-blue-50 border-t border-blue-200">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-md font-medium text-blue-800">Saldo Anda:</span>
                    <span class="text-xl font-bold text-blue-900" id="userSaldoCart" data-saldo="{{ Auth::user()->saldo ?? 0 }}">
                        Rp {{ number_format(Auth::user()->saldo ?? 0, 0, ',', '.') }}
                    </span>
                </div>
                <div id="saldoStatusCart" class="text-sm text-white-700">
                    <!-- Status saldo akan ditampilkan di sini -->
                </div>
            </div>

            <!-- Bagian Upload Dokumen Tambahan - Dipindahkan ke dalam setiap card produk oleh JavaScript -->
            <!-- Tombol Aksi -->
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4 p-6">
                <button type="button"
                    class="flex items-center justify-center gap-2 px-5 py-2 rounded-full shadow-lg text-base font-semibold text-white
                bg-gradient-to-r from-violet-600 to-indigo-700
                hover:from-violet-700 hover:to-indigo-800 hover:scale-105
                focus:outline-none focus:ring-4 focus:ring-violet-300
                transition duration-300 ease-in-out transform min-w-fit"
                    id="payButton">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h10m-9 4h8a2 2 0 002-2v-2a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2z" />
                    </svg>
                    Bayar dengan Midtrans
                </button>

                <button type="button"
                    id="paySaldoBtnCart"
                    class="flex items-center justify-center gap-2 px-5 py-2 rounded-full shadow-lg text-base font-semibold text-white
                    bg-gradient-to-r from-emerald-500 to-teal-600
                    hover:from-emerald-600 hover:to-teal-700 hover:scale-105
                    focus:outline-none focus:ring-4 focus:ring-emerald-300
                    transition duration-300 ease-in-out transform min-w-fit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V4m0 8v4m-6 1h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Bayar Pakai Saldo
                </button>

                <button type="button"
                    class="flex items-center justify-center gap-2 px-5 py-2 rounded-full shadow-lg text-base font-semibold text-white
                bg-gradient-to-r from-red-500 to-rose-600
                hover:from-red-600 hover:to-rose-700 hover:scale-105
                focus:outline-none focus:ring-4 focus:ring-red-300
                transition duration-300 ease-in-out transform min-w-fit"
                    id="cancelButton">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batal
                </button>
            </div>
        </div>
    </div>


    <script>
        /// Global variables untuk menyimpan data cart yang dipilih
        let selectedCartItems = [];
        let totalCartPrice = 0;

        // Pastikan modal ada sebelum mencoba menambah event listener
        const paymentModal = document.getElementById('paymentModal');
        const closeModalButton = document.getElementById('closeModalButton');

        if (closeModalButton && paymentModal) {
            closeModalButton.addEventListener('click', () => {
                paymentModal.classList.add('hidden'); // Sembunyikan modal
            });
        }

        // Fungsi untuk menampilkan modal (contoh, kamu mungkin sudah punya ini)
        function showPaymentModal() {
            if (paymentModal) {
                paymentModal.classList.remove('hidden');
            }
        }

        // Fungsi untuk update status saldo di cart
        function updateCartSaldoStatus() {
            const userSaldoElement = document.getElementById('userSaldoCart');
            const saldoStatusElement = document.getElementById('saldoStatusCart');
            const paySaldoBtn = document.getElementById('paySaldoBtnCart');

            if (!userSaldoElement || !saldoStatusElement || !paySaldoBtn) return;

            const userSaldo = parseInt(userSaldoElement.dataset.saldo) || 0;
            const totalPembayaran = totalCartPrice;

            console.log('Checking saldo:', userSaldo, 'vs total:', totalPembayaran);

            if (userSaldo >= totalPembayaran && totalPembayaran > 0) {
                saldoStatusElement.innerHTML = '<span class="text-sm text-green-600 font-medium">✓ Saldo mencukupi</span>';
                paySaldoBtn.disabled = false;
                paySaldoBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else if (totalPembayaran > 0) {
                const kurang = totalPembayaran - userSaldo;
                saldoStatusElement.innerHTML = `<span class="text-sm text-red-600 font-medium">✗ Saldo kurang Rp ${kurang.toLocaleString('id-ID')}</span>`;
                paySaldoBtn.disabled = true;
                paySaldoBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                saldoStatusElement.innerHTML = '<span class="text-sm text-gray-600 font-medium">Pilih produk untuk checkout</span>';
                paySaldoBtn.disabled = true;
                paySaldoBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        // Fixed payCartWithSaldo function
let isProcessingPayment = false;

async function payCartWithSaldo() {
    // Cegah double processing
    if (isProcessingPayment) {
        console.log('Payment already in progress, ignoring duplicate call');
        return;
    }

    let originalText = 'Bayar Pakai Saldo';
    const payButton = document.getElementById('paySaldoBtnCart');

    try {
        console.log('Starting saldo payment for cart items...');

        if (selectedCartItems.length === 0) {
            alert('Tidak ada item yang dipilih untuk pembayaran!');
            return;
        }

        // Set flag processing
        isProcessingPayment = true;

        // Validasi saldo
        const userSaldoElement = document.getElementById('userSaldoCart');
        const userSaldo = parseInt(userSaldoElement.dataset.saldo) || 0;

        if (userSaldo < totalCartPrice) {
            alert('Saldo tidak mencukupi untuk pembayaran ini!');
            return;
        }

        // Disable button untuk mencegah double click
        if (payButton) {
            originalText = payButton.innerHTML;
            payButton.disabled = true;
            payButton.innerHTML = '<span class="animate-spin">⏳</span> Memproses...';
        }

        // Prepare FormData - buat baru setiap kali
        const formData = new FormData();
        formData.append('payment_method', 'saldo');
        formData.append('total_amount', parseFloat(totalCartPrice));

        selectedCartItems.forEach((item, index) => {
            const productId = item.product.id || item.product.id_produk;
            const productPrice = parseFloat(item.product.harga || item.product.price) || 0;
            const quantity = parseInt(item.kuantitas) || 1;
            const total = productPrice * quantity;

            // Tambahkan data item ke FormData
            formData.append(`items[${index}][product_id]`, productId);
            formData.append(`items[${index}][quantity]`, quantity);
            formData.append(`items[${index}][price]`, productPrice);
            formData.append(`items[${index}][total]`, total);
            formData.append(`items[${index}][cart_id]`, item.id);

            // PERBAIKAN UTAMA: Handle file uploads dengan lebih konsisten
            // Cari file input berdasarkan product ID yang sesuai
            const fileInputSelectors = [
                `input[name="custom_images[${productId}]"]`,
                `input[name="custom_images[${productId}][]"]`,
                `input[type="file"][name*="${productId}"]`
            ];

            let fileInput = null;
            for (const selector of fileInputSelectors) {
                fileInput = document.querySelector(selector);
                if (fileInput) break;
            }

            if (fileInput && fileInput.files && fileInput.files.length > 0) {
                console.log(`Files found for product ${productId}:`, Array.from(fileInput.files));
                
                // PERBAIKAN: Append files dengan struktur yang konsisten dengan backend
                Array.from(fileInput.files).forEach((file, fileIndex) => {
                    console.log(`Appending file ${file.name} for product ${productId}`);
                    // Gunakan format yang sama dengan yang diharapkan backend
                    formData.append(`custom_images[${productId}][]`, file);
                });
            } else {
                console.log(`No files found for product ${productId}`);
            }
        });

        // Log FormData contents for debugging
        console.log('FormData contents:');
        for (let [key, value] of formData.entries()) {
            if (value instanceof File) {
                console.log(`${key}: File(${value.name}, ${value.size} bytes)`);
            } else {
                console.log(`${key}: ${value}`);
            }
        }

        console.log('Sending payment data:', {
            total_amount: totalCartPrice,
            items_count: selectedCartItems.length
        });

        // Validasi total di frontend
        let calculatedTotal = 0;
        selectedCartItems.forEach(item => {
            const productPrice = parseFloat(item.product.harga || item.product.price);
            const quantity = parseInt(item.kuantitas) || 1;
            calculatedTotal += productPrice * quantity;
        });

        const tolerance = 0.01;
        if (Math.abs(calculatedTotal - totalCartPrice) > tolerance) {
            throw new Error(`Total tidak sesuai: Frontend=${totalCartPrice}, Calculated=${calculatedTotal}`);
        }

        // Send transaction request dengan timeout
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout

        const response = await fetch('/transaction/saldo', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
                // PENTING: Jangan set Content-Type untuk FormData, biarkan browser yang handle
            },
            signal: controller.signal
        });

        clearTimeout(timeoutId);

        if (!response.ok) {
            const errorText = await response.text();
            console.error('Server response:', errorText);
            throw new Error(`Server error: ${response.status} - ${errorText}`);
        }

        const result = await response.json();
        console.log('Payment response:', result);

        if (result.success) {
            // Delete paid items from cart
            const deletePromises = selectedCartItems.map(item =>
                fetch(`/cart/${item.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                }).catch(error => {
                    console.error(`Failed to delete cart item ${item.id}:`, error);
                    return null;
                })
            );

            await Promise.allSettled(deletePromises);

            // Update user saldo in UI
            if (result.remaining_saldo !== undefined) {
                userSaldoElement.setAttribute('data-saldo', result.remaining_saldo);
                userSaldoElement.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(result.remaining_saldo);
            }

            const message = result.message || 'Pembayaran berhasil!';
            const detailInfo = result.orders_created ?
                `\n\nDetail:\n- ${result.orders_created.length} pesanan dibuat\n- ${result.transactions_created.length} transaksi berhasil` : '';

            alert(message + detailInfo);

            const paymentModal = document.getElementById('paymentModal');
            if (paymentModal) {
                paymentModal.classList.add('hidden');
            }

            if (typeof updateCartItems === 'function') {
                updateCartItems();
            }

            selectedCartItems = [];
            totalCartPrice = 0;
        } else {
            throw new Error(result.message || 'Pembayaran gagal');
        }

    } catch (error) {
        console.error('Error in saldo payment:', error);
        
        // Handle specific error types
        if (error.name === 'AbortError') {
            alert('Request timeout. Silakan coba lagi.');
        } else {
            alert('Terjadi kesalahan: ' + (error.message || 'Pembayaran gagal. Silakan coba lagi.'));
        }
    } finally {
        // Reset processing flag dan button state
        isProcessingPayment = false;
        
        if (payButton) {
            payButton.disabled = false;
            payButton.innerHTML = originalText;
        }
    }
}


        document.addEventListener("DOMContentLoaded", function() {
            console.log("Cart script initialized!");

            // Dropdown functionality
            const dropdownButton = document.getElementById("dropdownButton");
            const dropdownMenu = document.getElementById("dropdownMenu");

            if (dropdownButton && dropdownMenu) {
                dropdownButton.addEventListener("click", function(event) {
                    event.stopPropagation();
                    dropdownMenu.classList.toggle("hidden");
                });

                document.addEventListener("click", function() {
                    if (!dropdownMenu.classList.contains("hidden")) {
                        dropdownMenu.classList.add("hidden");
                    }
                });
            }

            // Cart checkbox change listener
            document.addEventListener('change', function(event) {
                if (event.target.classList.contains('cart-checkbox')) {
                    updateSidebarTotal();
                    updateSelectAllCheckboxState();
                    updateCheckoutButtonState();
                }
            });

            function updateSelectAllCheckboxState() {
                const selectAllCheckbox = document.getElementById('selectAllCarts');
                const cartCheckboxes = document.querySelectorAll('.cart-checkbox');

                if (!selectAllCheckbox) return;

                if (cartCheckboxes.length === 0) {
                    selectAllCheckbox.checked = false;
                    return;
                }

                const allChecked = Array.from(cartCheckboxes).every(checkbox => checkbox.checked);
                selectAllCheckbox.checked = allChecked;
            }

            // PERBAIKAN FUNGSI UPDATE CART ITEMS
            const updateCartItems = () => {
                console.log('Fetching cart items...');

                fetch('/cart/user', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(cart => {
                        console.log('Cart data received:', cart);
                        const cartItemsContainer = document.getElementById('cartItems');
                        const totalAmountElement = document.getElementById('totalAmount');

                        if (!cartItemsContainer) {
                            console.error('Cart items container not found');
                            return;
                        }

                        cartItemsContainer.innerHTML = '';

                        if (!cart || cart.length === 0) {
                            cartItemsContainer.innerHTML = `
                        <div class="text-center py-8">
                            <p class="text-xl font-semibold mb-2">Keranjang anda kosong</p>
                            <p class="text-md text-gray-600">Tambahkan produk ke keranjang untuk melanjutkan belanja.</p>
                        </div>
                    `;
                            if (totalAmountElement) totalAmountElement.innerText = '0';
                            const checkoutCount = document.getElementById('checkoutCount');
                            if (checkoutCount) checkoutCount.innerText = '0';
                            return;
                        }

                        cart.forEach(item => {
                            if (item && item.product) {
                                const productId = item.product.id || item.product.id_produk;
                                const productName = item.product.nama_produk || item.product.name;
                                const productPrice = item.product.harga || item.product.price;
                                const productImage = item.product.gambar_url || item.product.image_url || '/images/placeholder.png';

                                const totalHargaProduk = productPrice * item.kuantitas;

                                const cartItem = `
                            <div class="flex items-center bg-white p-4 rounded-lg shadow-sm mb-4"> 
                                <input type="checkbox" class="cart-checkbox mr-4" 
                                    data-cart-id="${item.id}" 
                                    data-harga="${productPrice}" 
                                    data-qty="${item.kuantitas}" />
                                <img
                                    alt="${productName}"
                                    class="w-24 h-24 mr-4 rounded-lg object-cover"
                                    src="${productImage}"
                                    onerror="this.src='/images/placeholder.png'; this.onerror=null;"
                                />
                                <div class="flex-grow"> 
                                    <p class="text-lg font-bold">${productName}</p>
                                    <p class="text-lg text-gray-600">Rp${Number(totalHargaProduk).toLocaleString('id-ID')}</p>
                                    <div class="flex items-center mt-2"> 
                                        <button class="decrease-quantity bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded-l" data-cart-id="${item.id}">-</button>
                                        <input type="number" 
                                            value="${item.kuantitas}" 
                                            min="1"
                                            class="quantity-input w-16 text-center border-t border-b border-gray-200 py-1" 
                                            data-cart-id="${item.id}" />
                                        <button class="increase-quantity bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded-r" data-cart-id="${item.id}">+</button>
                                    </div>
                                </div>
                            </div>
                        `;
                                cartItemsContainer.innerHTML += cartItem;
                            }
                        });

                        setupQuantityListeners();
                        setupCartCheckboxListeners();
                        updateSidebarTotal();
                        updateSelectAllCheckboxState();
                        updateCheckoutButtonState();
                    })
                    .catch(error => {
                        console.error('Error loading cart:', error);
                        const cartItemsContainer = document.getElementById('cartItems');
                        if (cartItemsContainer) {
                            cartItemsContainer.innerHTML = `
                        <div class="text-center py-8 text-red-600">
                            <p class="text-lg font-semibold">Error memuat keranjang</p>
                            <p class="text-sm">${error.message}</p>
                            <button onclick="updateCartItems()" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                Coba Lagi
                            </button>
                        </div>
                    `;
                        }
                    });
            };

            const setupCartCheckboxListeners = () => {
                document.querySelectorAll('.cart-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        updateSidebarTotal();
                        updateCheckoutButtonState();
                        updateSelectAllCheckboxState();
                    });
                });
            };

            const updateSidebarTotal = () => {
                let total = 0;
                const checkedItems = document.querySelectorAll('.cart-checkbox:checked');

                checkedItems.forEach(checkbox => {
                    const harga = parseInt(checkbox.getAttribute('data-harga')) || 0;
                    const qty = parseInt(checkbox.getAttribute('data-qty')) || 1;
                    total += harga * qty;
                });

                const totalAmountElement = document.getElementById('totalAmount');
                if (totalAmountElement) {
                    totalAmountElement.innerText = total.toLocaleString('id-ID');
                }
            };

            // PERBAIKAN FUNGSI UPDATE QUANTITY
            const updateCartQuantity = (cartId, quantity) => {
                console.log(`Updating cart ${cartId} to quantity ${quantity}`);

                fetch(`/cart/update/${cartId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            kuantitas: quantity
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(`HTTP ${response.status}: ${text}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Update response:', data);
                        if (data.success) {
                            updateCartItems();
                        } else {
                            alert('Gagal mengupdate kuantitas: ' + (data.message || 'Unknown error'));
                            updateCartItems(); // Refresh to show correct values
                        }
                    })
                    .catch(error => {
                        console.error('Error updating quantity:', error);
                        alert('Terjadi kesalahan saat mengupdate kuantitas: ' + error.message);
                        updateCartItems(); // Refresh to show correct values
                    });
            };

            // Select all functionality
            const selectAllCarts = document.getElementById('selectAllCarts');
            if (selectAllCarts) {
                selectAllCarts.addEventListener('change', function() {
                    const cartCheckboxes = document.querySelectorAll('.cart-checkbox');
                    const isChecked = this.checked;

                    cartCheckboxes.forEach(checkbox => {
                        checkbox.checked = isChecked;
                    });

                    updateSidebarTotal();
                    updateCheckoutButtonState();
                });
            }

            function updateCheckoutButtonState() {
                const checkedItems = document.querySelectorAll('.cart-checkbox:checked');
                const checkoutButton = document.getElementById('checkoutButton');
                const checkoutCountElement = document.getElementById('checkoutCount');

                if (checkoutButton) {
                    checkoutButton.disabled = checkedItems.length === 0;
                    if (checkedItems.length === 0) {
                        checkoutButton.classList.add('opacity-50', 'cursor-not-allowed');
                    } else {
                        checkoutButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                }
                if (checkoutCountElement) {
                    checkoutCountElement.textContent = checkedItems.length;
                }
            }

            const setupQuantityListeners = () => {
                // Increase quantity buttons
                document.querySelectorAll('.increase-quantity').forEach(button => {
                    button.addEventListener('click', function() {
                        const cartId = this.getAttribute('data-cart-id');
                        const input = document.querySelector(`.quantity-input[data-cart-id="${cartId}"]`);
                        if (input) {
                            const newQuantity = parseInt(input.value) + 1;
                            input.value = newQuantity;
                            updateCartQuantity(cartId, newQuantity);
                        }
                    });
                });

                // Decrease quantity buttons
                document.querySelectorAll('.decrease-quantity').forEach(button => {
                    button.addEventListener('click', function() {
                        const cartId = this.getAttribute('data-cart-id');
                        const input = document.querySelector(`.quantity-input[data-cart-id="${cartId}"]`);
                        if (input) {
                            const newQuantity = Math.max(1, parseInt(input.value) - 1);
                            input.value = newQuantity;
                            updateCartQuantity(cartId, newQuantity);
                        }
                    });
                });

                // Direct input change
                document.querySelectorAll('.quantity-input').forEach(input => {
                    input.addEventListener('change', function() {
                        const cartId = this.getAttribute('data-cart-id');
                        const newQuantity = Math.max(1, parseInt(this.value) || 1);
                        this.value = newQuantity;
                        updateCartQuantity(cartId, newQuantity);
                    });
                });
            };

            // Delete functionality
            const removeSelectedItems = () => {
                const selectedCheckboxes = document.querySelectorAll('.cart-checkbox:checked');

                if (selectedCheckboxes.length === 0) {
                    alert('Pilih item yang ingin dihapus!');
                    return;
                }

                const confirmationModal = document.getElementById('confirmationModal');
                if (confirmationModal) {
                    confirmationModal.classList.remove('hidden');
                }
            };

            // Ganti bagian ini di layout.app
            document.getElementById('payButton').addEventListener('click', async function() {
                try {
                    // 1. Get all checked items from cart
                    const selectedCheckboxes = document.querySelectorAll('.cart-checkbox:checked');

                    if (selectedCheckboxes.length === 0) {
                        alert("Pilih item yang ingin dibayar!");
                        return;
                    }

                    // 2. Get custom image if exists - PERBAIKAN DI SINI
                    const customImageInput = document.querySelector("input[name='custom_image']");
                    
                    // TAMBAHKAN: Cek juga input dengan name yang berbeda dari modal
                    const customImageInputs = document.querySelectorAll("input[type='file'][name^='custom_images']");

                    try {
                        // 3. Get cart items data
                        const response = await fetch('/cart/user', {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            }
                        });

                        const cartItems = await response.json();
                        const selectedCartIds = Array.from(selectedCheckboxes).map(cb => cb.getAttribute('data-cart-id'));
                        const selectedItems = cartItems.filter(item => selectedCartIds.includes(item.id.toString()));

                        // 4. Create orders first
                        const orderPromises = selectedItems.map(item =>
                            fetch('/order/store', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                },
                                body: JSON.stringify({
                                    product_id: item.id_produk,
                                    kuantitas: item.kuantitas,
                                    total_pembayaran: item.product.harga * item.kuantitas
                                })
                            }).then(res => res.json())
                        );

                        const orders = await Promise.all(orderPromises);

                        // 5. Prepare form data for transaction
                        const formData = new FormData();

                        // PERBAIKAN: Add custom image dengan nama yang benar
                        if (customImageInput && customImageInput.files[0]) {
                            formData.append("custom_image", customImageInput.files[0]);
                        }

                        // TAMBAHAN: Handle multiple custom images dari modal
                        customImageInputs.forEach((input, index) => {
                            if (input.files[0]) {
                                // Extract product ID dari name attribute
                                const match = input.name.match(/custom_images\[(\d+)\]/);
                                const productId = match ? match[1] : index;
                                formData.append(`custom_images[${productId}]`, input.files[0]);
                            }
                        });

                        // 6. Add cart items with their order IDs
                        const cartData = selectedItems.map((item, index) => {
                            if (orders[index] && orders[index].order_id) {
                                return {
                                    user_id: item.id_user,
                                    product_id: item.id_produk,
                                    order_id: orders[index].order_id,
                                    kuantitas: item.kuantitas,
                                    total_pembayaran: item.product.harga
                                };
                            }
                        }).filter(Boolean);

                        // Modified formData preparation in your JavaScript
                        formData.append('cart', JSON.stringify(cartData));

                        cartData.forEach((item, index) => {
                            formData.append(`cart[${index}][user_id]`, item.user_id);
                            formData.append(`cart[${index}][product_id]`, item.product_id);
                            formData.append(`cart[${index}][order_id]`, item.order_id);
                            formData.append(`cart[${index}][kuantitas]`, item.kuantitas.toString());
                            formData.append(`cart[${index}][total_pembayaran]`, item.total_pembayaran.toString());
                        });

                        // 7. Send transaction request
                        const transactionResponse = await fetch("/transaction/cart", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                            body: formData
                        });

                        const result = await transactionResponse.json();

                        if (result.success && result.snap_token) {
                            // 8. Handle Midtrans payment
                            window.snap.pay(result.snap_token, {
                                onSuccess: async function(result) {
                                    try {
                                        // Delete paid items from cart
                                        const deletePromises = selectedCartIds.map(cartId =>
                                            fetch(`/cart/${cartId}`, {
                                                method: 'DELETE',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                                }
                                            })
                                        );

                                        await Promise.all(deletePromises);

                                        // Update payment status
                                        await fetch(`/payment/update-status/${result.transaction_id}`, {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                            },
                                            body: JSON.stringify({
                                                status: 'success',
                                                payment_data: result
                                            })
                                        });

                                        // redirect
                                        window.location.href = '/product';
                                    } catch (error) {
                                        console.error('Error in success callback:', error);
                                    }
                                },
                                onPending: function(result) {
                                    window.location.href = `/product`;
                                },
                                onError: function(result) {
                                    console.error("Payment error:", result);
                                    alert("Pembayaran gagal!");
                                },
                                onClose: function() {
                                    alert("Anda menutup popup tanpa menyelesaikan pembayaran");
                                }
                            });
                        } else {
                            throw new Error(result.message || "Gagal memulai pembayaran.");
                        }
                    } catch (error) {
                        console.error("Error:", error);
                        alert(error.message || "Terjadi kesalahan saat memproses pembayaran.");
                    }
                } catch (error) {
                    console.error("Error in payment process:", error);
                    alert("Terjadi kesalahan saat memproses pembayaran.");
                }
            });

            // Delete confirmation handlers
            const confirmDeleteButton = document.getElementById('confirmDeleteButton');
            if (confirmDeleteButton) {
                confirmDeleteButton.addEventListener('click', function() {
                    const selectedCheckboxes = document.querySelectorAll('.cart-checkbox:checked');
                    const cartIdsToDelete = Array.from(selectedCheckboxes).map(checkbox =>
                        checkbox.getAttribute('data-cart-id')
                    );

                    const deletePromises = cartIdsToDelete.map(cartId =>
                        fetch(`/cart/${cartId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                        }).catch(error => {
                            console.error(`Failed to delete cart item ${cartId}:`, error);
                            return {
                                ok: false,
                                error: error.message
                            };
                        })
                    );

                    Promise.allSettled(deletePromises)
                        .then(results => {
                            const failures = results.filter(result =>
                                result.status === 'rejected' ||
                                (result.value && !result.value.ok)
                            );

                            if (failures.length > 0) {
                                console.warn('Some deletions failed:', failures);
                                alert(`${cartIdsToDelete.length - failures.length} item berhasil dihapus, ${failures.length} item gagal dihapus.`);
                            } else {
                                alert('Semua item berhasil dihapus.');
                            }

                            const confirmationModal = document.getElementById('confirmationModal');
                            if (confirmationModal) {
                                confirmationModal.classList.add('hidden');
                            }
                            updateCartItems();
                        })
                        .catch(error => {
                            console.error('Error during deletion:', error);
                            alert('Terjadi kesalahan saat menghapus item');
                            const confirmationModal = document.getElementById('confirmationModal');
                            if (confirmationModal) {
                                confirmationModal.classList.add('hidden');
                            }
                        });
                });
            }

            const cancelDeleteButton = document.getElementById('cancelDeleteButton');
            if (cancelDeleteButton) {
                cancelDeleteButton.addEventListener('click', function() {
                    const confirmationModal = document.getElementById('confirmationModal');
                    if (confirmationModal) {
                        confirmationModal.classList.add('hidden');
                    }
                });
            }

            const deleteSelectedButton = document.getElementById('deleteSelected');
            if (deleteSelectedButton) {
                deleteSelectedButton.addEventListener('click', removeSelectedItems);
            }

            // CHECKOUT BUTTON HANDLER - DISEDERHANAKAN
            const checkoutButton = document.getElementById('checkoutButton');
            if (checkoutButton) {
                checkoutButton.addEventListener('click', async function() {
                    console.log('Checkout button clicked!');

                    const selectedCheckboxes = document.querySelectorAll('.cart-checkbox:checked');

                    if (selectedCheckboxes.length === 0) {
                        alert('Pilih produk yang ingin dibayar!');
                        return;
                    }

                    try {
                        // Fetch current cart data
                        const response = await fetch('/cart/user', {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`Failed to fetch cart: ${response.status}`);
                        }

                        const cartItems = await response.json();
                        console.log('Cart items for checkout:', cartItems);

                        const selectedCartIds = Array.from(selectedCheckboxes).map(checkbox =>
                            checkbox.getAttribute('data-cart-id')
                        );

                        // Prepare selected items and calculate total
                        selectedCartItems = [];
                        totalCartPrice = 0;

                        for (const item of cartItems) {
                            if (selectedCartIds.includes(item.id.toString())) {
                                const productPrice = item.product.harga || item.product.price;
                                const hargaTotal = productPrice * item.kuantitas;
                                totalCartPrice += hargaTotal;

                                selectedCartItems.push({
                                    id: item.id,
                                    product: {
                                        id: item.product.id || item.product.id_produk,
                                        nama_produk: item.product.nama_produk || item.product.name,
                                        harga: productPrice,
                                        gambar_url: item.product.gambar_url || item.product.image_url || '/images/placeholder.png'
                                    },
                                    kuantitas: item.kuantitas,
                                    harga_total: hargaTotal
                                });
                            }
                        }

                        console.log('Selected items for payment:', selectedCartItems);
                        console.log('Total price:', totalCartPrice);

                        // Show payment modal
                        showPaymentModal();

                    } catch (error) {
                        console.error('Error during checkout:', error);
                        alert('Terjadi kesalahan saat memuat data: ' + error.message);
                    }
                });
            }

            // Function to show payment modal
            function showPaymentModal() {
    const cartSidebar = document.getElementById('cartSidebar');
    const paymentModal = document.getElementById('paymentModal');
    const modalProductCards = document.getElementById('modalProductCards');

    // Hide cart sidebar
    if (cartSidebar) {
        cartSidebar.classList.add('hidden');
    }

    // Show payment modal
    if (paymentModal) {
        paymentModal.classList.remove('hidden');
    }

    // Clear and populate modal content
    if (modalProductCards) {
        modalProductCards.innerHTML = ''; // Pastikan konten lama dihapus

        selectedCartItems.forEach((item) => {
            const productName = item.product.nama_produk || 'Nama Produk Tidak Diketahui';
            const productPrice = item.product.harga || 0;
            const totalHargaProduk = item.harga_total || (productPrice * item.kuantitas);
            const productImage = item.product.gambar_url || '/images/placeholder.png';
            const productId = item.product.id || item.product.id_produk;

            const productCard = `
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <div class="bg-gray-200 text-black p-4">
                    <h2 class="text-xl font-semibold">Pesanan: ${productName}</h2>
                </div>
                <div class="flex flex-col sm:flex-row items-center p-4 gap-4">
                    <div class="flex-shrink-0">
                        <img src="${productImage}" alt="${productName}" class="w-32 h-32 object-cover rounded-lg shadow-sm" onerror="this.src='/images/placeholder.png';" />
                    </div>
                    <div class="flex-grow w-full sm:w-auto space-y-3">
                        <div>
                            <label class="block mb-1 text-sm text-gray-600">Jumlah Produk</label>
                            <div class="w-full p-2 rounded-lg bg-gray-100 text-gray-800 font-medium text-lg">${item.kuantitas}</div>
                        </div>
                        <div>
                            <label class="block mb-1 text-sm text-gray-600">Harga Satuan</label>
                            <div class="w-full p-2 rounded-lg bg-gray-100 text-gray-800 font-medium text-lg">Rp ${Number(productPrice).toLocaleString('id-ID')}</div>
                        </div>
                        <div>
                            <label class="block mb-1 text-sm text-gray-600">Total Harga</label>
                            <div class="w-full p-2 rounded-lg bg-white-50 text-black-800 font-bold text-xl">Rp ${Number(totalHargaProduk).toLocaleString('id-ID')}</div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 p-4">
                    <label class="block mb-1 text-sm text-gray-600">Unggah Dokumen Tambahan</label>
                    <input type="file" 
                           name="custom_images[${productId}]" 
                           class="w-full p-2 border border-gray-300 rounded-lg" 
                           accept="image/jpeg,image/png,image/jpg" 
                           multiple 
                           data-product-id="${productId}" />
                    <p class="text-xs text-gray-500 mt-1">Format: JPEG, PNG, JPG. Maksimal 2MB per file.</p>
                </div>
            </div>
        `;
            modalProductCards.innerHTML += productCard;
        });
    }

    // Update total price display
    const totalHargaElement = document.getElementById('totalHarga');
    if (totalHargaElement) {
        totalHargaElement.innerText = 'Rp ' + totalCartPrice.toLocaleString('id-ID');
    }

    updateCartSaldoStatus();
}

            // Payment button handlers
            const paySaldoBtnCart = document.getElementById('paySaldoBtnCart');
            if (paySaldoBtnCart) {
                // Remove onclick attribute jika ada
                paySaldoBtnCart.removeAttribute('onclick');
                
                // Add event listener hanya sekali
                paySaldoBtnCart.addEventListener('click', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    payCartWithSaldo();
                }, { once: false }); // Jangan gunakan once: true karena button bisa digunakan lagi
            }

            const cancelButton = document.getElementById('cancelButton');
            if (cancelButton) {
                cancelButton.addEventListener('click', function() {
                    const paymentModal = document.getElementById('paymentModal');
                    if (paymentModal) {
                        paymentModal.classList.add('hidden');
                    }

                    // Reset selected items
                    selectedCartItems = [];
                    totalCartPrice = 0;
                });
            }

            // Cart sidebar handlers
            const cartIcon = document.getElementById('cartIcon');
            if (cartIcon) {
                cartIcon.addEventListener('click', () => {
                    const cartSidebar = document.getElementById('cartSidebar');
                    if (cartSidebar) {
                        cartSidebar.classList.remove('hidden');
                        updateCartItems();
                    }
                });
            }

            const closeCart = document.getElementById('closeCart');
            if (closeCart) {
                closeCart.addEventListener('click', () => {
                    const cartSidebar = document.getElementById('cartSidebar');
                    if (cartSidebar) {
                        cartSidebar.classList.add('hidden');
                    }
                });
            }

            // Make updateCartItems globally available
            window.updateCartItems = updateCartItems;

            // Initialize
            updateCheckoutButtonState();
            updateCartItems();
            updateCartSaldoStatus();
        });
    </script>

    @endif
    @endguest
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.querySelector('header');
            const navbar = document.getElementById('navbar');

            function setNavbarTop() {
                const headerHeight = header.offsetHeight;
                if (navbar) {
                    navbar.style.top = headerHeight + 'px';
                }
            }

            // Set initial top position
            setNavbarTop();

            // Update on resize
            window.addEventListener('resize', setNavbarTop);
        });
    </script>

    <!-- Main Content Section -->
    <main class="container mx-auto py-8 flex-grow">
        @yield('content')
    </main>

    <!-- Footer Section -->
    <footer class="w-full md:w-auto bg-gradient-to-r from-green-400 to-teal-500 text-white font-semibold py-2 px-6 rounded-md shadow-md transition duration-300 ease-in-out">
        <div class="container mx-auto">
            <p>&copy;
                2025 DR AKTA PERCETAKAN. All rights reserved.</p>
        </div>
    </footer>

    <!-- ICON CHAT-->
    <!-- Chat Button -->
    <a href="http://localhost:8000/chatify"
        target="_blank"
        rel="noopener"
        class="fixed bottom-6 right-6 flex items-center bg-green-500 hover:bg-green-400 text-white font-semibold py-2 pl-3 pr-4 rounded-full shadow-lg transition duration-300">

        <!-- Chat Icon -->
        <div class="bg-white rounded-full w-6 h-6 flex items-center justify-center mr-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-4 h-4 text-green-500">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8 10h.01M12 10h.01M16 10h.01M9 16h6m-9 5a1 1 0 0 1-1-1v-4a9 9 0 1 1 18 0v4a1 1 0 0 1-1 1H5z" />
            </svg>
        </div>

        <!-- Text -->
        <span>Live Chat 24/7</span>
    </a>

</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}">
</script>
@stack('scripts')
@yield('scripts')
</body>

</html>