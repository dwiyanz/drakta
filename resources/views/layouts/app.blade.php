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
                    const form = doc.querySelector('form'); // ambil elemen <form> saja
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
        <div class="w-full w-[450px] bg-white rounded-lg h-full flex flex-col">
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
    <div id="paymentModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-[999]">
        <div class="bg-cyan-100 p-4 rounded-lg shadow-lg w-[80%] max-h-[90%] overflow-y-auto">
            <!-- Header Modal -->
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-bold">Detail Pembayaran</h1>
            </div>

            <!-- Daftar Card Produk -->
            <div id="modalProductCards" class="space-y-4">
                <!-- Card Produk akan ditambahkan di sini oleh JavaScript -->
            </div>

            <!-- Total Harga -->
            <div id="totalHargaContainer" class="mb-2 bg-teal-600 text-white px-4 py-2 rounded text-center mt-4">
                <span class="text-lg font-semibold">Total Harga:</span>
                <span id="totalHarga" class="text-lg font-bold">0</span>
            </div>
            <!-- Tombol Aksi -->
            <div class="flex justify-center mt-4 space-x-4">
                <button type="button" class="bg-teal-500 text-white px-4 py-2 rounded hover:bg-teal-300 hover:text-teal-500" id="payButton">Bayar</button>
                <button type="button" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-300 hover:text-red-500" id="cancelButton">Cancel</button>
            </div>
        </div>
    </div>

    <script>
document.addEventListener("DOMContentLoaded", function() {
    console.log("Script berjalan!");

    const dropdownButton = document.getElementById("dropdownButton");
    const dropdownMenu = document.getElementById("dropdownMenu");

    if (dropdownButton && dropdownMenu) {
        dropdownButton.addEventListener("click", function(event) {
            event.stopPropagation(); // Menghentikan propagasi klik agar tidak memicu klik pada dokumen
            dropdownMenu.classList.toggle("hidden");
        });

        // Menyembunyikan dropdown saat klik di luar
        document.addEventListener("click", function() {
            if (!dropdownMenu.classList.contains("hidden")) {
                dropdownMenu.classList.add("hidden");
            }
        });
    }

    document.addEventListener('change', function(event) {
        if (event.target.classList.contains('cart-checkbox')) {
            updateSidebarTotal();
        }
    });

    // Check the state of the select all checkbox
    function updateSelectAllCheckboxState() {
        const selectAllCheckbox = document.getElementById('selectAllCarts');
        const cartCheckboxes = document.querySelectorAll('.cart-checkbox');
        
        if (cartCheckboxes.length === 0) {
            selectAllCheckbox.checked = false;
            return;
        }
        
        const allChecked = Array.from(cartCheckboxes).every(checkbox => checkbox.checked);
        selectAllCheckbox.checked = allChecked;
    }

    const updateCartItems = () => {
        fetch('/cart/user', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            })
            .then(response => response.json())
            .then(cart => {
                console.log('Data cart:', cart);
                const cartItemsContainer = document.getElementById('cartItems');
                const totalAmountElement = document.getElementById('totalAmount');
                cartItemsContainer.innerHTML = '';

                if (!cart || cart.length === 0) {
                    cartItemsContainer.innerHTML = '<p class="text-xl font-semibold mb-2">Keranjang anda kosong</p> &nbsp <p class="text-md text-center">Tambahkan produk ke keranjang untuk melanjutkan belanja.</p>';
                    totalAmountElement.innerText = '0';
                    document.getElementById('checkoutCount').innerText = '0';
                    return;
                }

                cart.forEach(item => {
                    if (item && item.product) {
                        const totalHargaProduk = item.product.harga * item.kuantitas;

                        const cartItem = `
            <div class="flex items-center bg-white p-4 rounded-lg shadow-sm mb-4"> <input type="checkbox" class="cart-checkbox mr-4" data-cart-id="${item.id}" data-harga="${item.product.harga}" data-qty="${item.kuantitas}" />
                <img
                    alt="${item.product.nama_produk}"
                    class="w-24 h-24 mr-4 rounded-lg object-cover"
                    src="${item.product.gambar_url}"
                    onerror="this.src='/images/placeholder.png'; this.onerror=null;"
                />
                <div class="flex-grow"> <p class="text-lg font-bold">${item.product.nama_produk}</p>
                    <p class="text-lg">Rp${Number(totalHargaProduk).toLocaleString('id-ID')}</p>
                    <div class="flex items-center mt-2"> <button class="decrease-quantity text-2xl" data-cart-id="${item.id}">-</button>
                        <input type="text" value="${item.kuantitas}" class="quantity-input w-12 text-center mx-2" data-cart-id="${item.id}" />
                        <button class="increase-quantity text-2xl" data-cart-id="${item.id}">+</button>
                    </div>
                </div>
            </div>`;
                        cartItemsContainer.innerHTML += cartItem;
                    }
                });

                setupQuantityListeners();
                setupCartCheckboxListeners(); // Set up checkbox listeners
                updateSidebarTotal(); // Calculate total based on checked items
                updateSelectAllCheckboxState(); // Update the "select all" checkbox state
                updateCheckoutButtonState(); 
            })
            .catch(error => {
                console.error('Error memuat keranjang:', error);
                document.getElementById('cartItems').innerHTML = '<p>Error memuat keranjang. Silakan coba lagi.</p>';
            });
    };

    // Add new function to set up checkbox listeners
    const setupCartCheckboxListeners = () => {
        document.querySelectorAll('.cart-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSidebarTotal();
                updateCheckoutButtonState();
                updateSelectAllCheckboxState(); // Update "select all" checkbox when individual checkboxes change
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

        document.getElementById('totalAmount').innerText = total.toLocaleString('id-ID');
    };


    const updateCartQuantity = (cartId, quantity) => {
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
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    updateCartItems(); // Refresh tampilan keranjang
                } else {
                    alert('Gagal mengupdate kuantitas: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupdate kuantitas');
            });
    };

    const selectAllCarts = document.getElementById('selectAllCarts');

    selectAllCarts.addEventListener('change', function() {
        const cartCheckboxes = document.querySelectorAll('.cart-checkbox');
        const isChecked = this.checked;
        
        cartCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        
        updateSidebarTotal();
        updateCheckoutButtonState();
    });

    // Update function to update checkout button state and count
    function updateCheckoutButtonState() {
        const checkedItems = document.querySelectorAll('.cart-checkbox:checked');
        const checkoutButton = document.getElementById('checkoutButton');
        const checkoutCountElement = document.getElementById('checkoutCount');
        
        checkoutButton.disabled = checkedItems.length === 0;
        checkoutCountElement.textContent = checkedItems.length;
    }

    // Call when page loads
    updateCheckoutButtonState();

    // Update the quantity listeners setup
    const setupQuantityListeners = () => {
        // Event listener untuk tombol tambah
        document.querySelectorAll('.increase-quantity').forEach(button => {
            button.addEventListener('click', function() {
                const cartId = this.getAttribute('data-cart-id');
                const input = document.querySelector(`.quantity-input[data-cart-id="${cartId}"]`);
                const newQuantity = parseInt(input.value) + 1;
                updateCartQuantity(cartId, newQuantity);
            });
        });

        // Event listener untuk tombol kurang
        document.querySelectorAll('.decrease-quantity').forEach(button => {
            button.addEventListener('click', function() {
                const cartId = this.getAttribute('data-cart-id');
                const input = document.querySelector(`.quantity-input[data-cart-id="${cartId}"]`);
                const newQuantity = Math.max(1, parseInt(input.value) - 1); // Minimal 1
                updateCartQuantity(cartId, newQuantity);
            });
        });

        // Event listener untuk input langsung
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                const cartId = this.getAttribute('data-cart-id');
                const newQuantity = Math.max(1, parseInt(this.value) || 1);
                this.value = newQuantity; // Update input value
                updateCartQuantity(cartId, newQuantity);
            });
        });
    };

    // Modify the removeSelectedItems function to show confirmation modal first
    const removeSelectedItems = () => {
        const selectedCheckboxes = document.querySelectorAll('.cart-checkbox:checked');

        if (selectedCheckboxes.length === 0) {
            alert('Pilih item yang ingin dihapus!');
            return;
        }

        // Show confirmation modal
        const confirmationModal = document.getElementById('confirmationModal');
        confirmationModal.classList.remove('hidden');
    };

    // Add event listeners for confirmation modal buttons
    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
        const selectedCheckboxes = document.querySelectorAll('.cart-checkbox:checked');
        const cartIdsToDelete = Array.from(selectedCheckboxes).map(checkbox =>
            checkbox.getAttribute('data-cart-id')
        );

        Promise.all(
                cartIdsToDelete.map(cartId =>
                    fetch(`/cart/${cartId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                    })
                )
            )
            .then(responses => {
                const errors = responses.filter(response => !response.ok);
                if (errors.length > 0) {
                    alert('Beberapa item gagal dihapus.');
                } else {
                    alert('Item berhasil dihapus.');
                }
                // Hide confirmation modal
                document.getElementById('confirmationModal').classList.add('hidden');
                // Update cart items display
                updateCartItems();
            })
            .catch(error => {
                console.error('Error menghapus item:', error);
                alert('Terjadi kesalahan saat menghapus item');
                document.getElementById('confirmationModal').classList.add('hidden');
            });
    });

    // Add event listener for cancel button
    document.getElementById('cancelDeleteButton').addEventListener('click', function() {
        document.getElementById('confirmationModal').classList.add('hidden');
    });

    // Update the delete button event listener
    document.getElementById('deleteSelected').addEventListener('click', removeSelectedItems);

    document.getElementById('checkoutButton').addEventListener('click', function() {
        // Get selected items from cart
        const selectedCheckboxes = document.querySelectorAll('.cart-checkbox:checked');

        if (selectedCheckboxes.length === 0) {
            alert('Pilih produk yang ingin dibayar!');
            return;
        }

        const cartSidebar = document.getElementById('cartSidebar');
        const paymentModal = document.getElementById('paymentModal');
        const modalProductCards = document.getElementById('modalProductCards');

        // Hide cart sidebar and show payment modal
        cartSidebar.classList.add('hidden');
        paymentModal.classList.remove('hidden');

        modalProductCards.innerHTML = ''; // Clear previous content

        // Get cart items from server
        fetch('/cart/user', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            })
            .then(response => response.json())
            .then(cartItems => {
                const selectedCartIds = Array.from(selectedCheckboxes).map(checkbox =>
                    checkbox.getAttribute('data-cart-id')
                );

                let totalHarga = 0;

                // Filter only selected items and create their orders
                cartItems.forEach(item => {
                    if (selectedCartIds.includes(item.id.toString())) {
                        const hargaProduk = item.product.harga * item.kuantitas;
                        totalHarga += hargaProduk;

                        // Create order for each selected item
                        fetch('/order/store', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                },
                                body: JSON.stringify({
                                    product_id: item.product.id_produk,
                                    kuantitas: item.kuantitas,
                                    total_pembayaran: hargaProduk
                                })
                            })
                            .then(response => response.json())
                            .then(data => console.log(data))
                            .catch(error => console.error('Error creating order:', error));

                        const productCard = `
                        <div class="bg-teal-600 text-white p-4 rounded-lg">
                            <h2 class="text-lg font-bold">Pesanan: ${item.product.nama_produk}</h2>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <input type="hidden" name="cart_id" value="${item.id}">
                                <input type="hidden" name="product_id" value="${item.product.id}">
                                <div>
                                    <img src="${item.product.gambar_url}" alt="${item.product.nama_produk}" class="w-32 h-32 object-cover rounded-lg">
                                </div><br>
                                <div>
                                    <label class="block mb-2">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ Auth::user()->name }}" class="w-full p-2 rounded bg-teal-900" disabled>
                                </div>
                                <div>
                                    <label class="block mb-2">Alamat</label>
                                    <input type="text" name="alamat" value="{{ Auth::user()->alamat }}" class="w-full p-2 bg-teal-900 rounded" disabled>
                                </div>
                                <div>
                                    <label class="block mb-2">Nomor Telepon/WA</label>
                                    <input type="text" name="telepon" value="{{ Auth::user()->telepon }}" class="w-full p-2 bg-teal-900 rounded" disabled>
                                </div>
                                <div>
                                    <label class="block mb-2">Upload Custom Gambar</label>
                                    <input type="file" name="custom_image" accept="image/*" class="w-full p-2 bg-teal-900 rounded">
                                </div>
                                <div>
                                    <label class="block mb-2">Jumlah Produk</label>
                                    <input type="text" name="quantity" value="${item.kuantitas}" class="w-full p-2 rounded bg-teal-900" disabled>
                                </div>
                            </div>
                        </div>
                    `;
                        modalProductCards.innerHTML += productCard;
                    }
                });

                document.getElementById('totalHarga').innerText = totalHarga.toLocaleString('id-ID');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memuat data produk');
            });
    });

    document.getElementById('cancelButton').addEventListener('click', function() {
        const paymentModal = document.getElementById('paymentModal');
        paymentModal.classList.add('hidden');
    });

    document.getElementById('payButton').addEventListener('click', async function() {
        try {
            // 1. Get all checked items from cart
            const selectedCheckboxes = document.querySelectorAll('.cart-checkbox:checked');

            if (selectedCheckboxes.length === 0) {
                alert("Pilih item yang ingin dibayar!");
                return;
            }

            // 2. Get custom image if exists
            const customImageInput = document.querySelector("input[name='custom_image']");

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

                // Add custom image if exists
                if (customImageInput && customImageInput.files[0]) {
                    formData.append("custom_image", customImageInput.files[0]);
                }

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


    document.getElementById('cartIcon').addEventListener('click', () => {
        const cartSidebar = document.getElementById('cartSidebar');
        cartSidebar.classList.remove('hidden');
        updateCartItems();
    });

    document.getElementById('closeCart').addEventListener('click', () => {
        const cartSidebar = document.getElementById('cartSidebar');
        cartSidebar.classList.add('hidden');
    });
    
    // Initially update cart items when page loads
    updateCartItems();
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