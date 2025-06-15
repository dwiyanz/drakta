@extends('layouts.app')

@section('content')
@guest
<div class="container mx-auto py-10">

    <div class="relative z-10 max-w-3xl mx-auto">
        <form method="GET" action="{{ route('products.index') }}" class="relative">
            <div class="flex items-center bg-white rounded-full shadow-lg border border-gray-200 overflow-hidden
                    focus-within:ring-4 focus-within:ring-emerald-200 focus-within:border-emerald-400
                    transition-all duration-300">
                <div class="flex-1 relative">
                    <input
                        type="text"
                        name="query"
                        value="{{ request('query') }}"
                        placeholder="Cari produk..."
                        class="w-full h-full pl-14 pr-4 py-3.5 text-gray-800 bg-transparent focus:outline-none text-base sm:text-lg rounded-l-full"
                        autocomplete="off">
                    <div class="absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <button
                    type="submit"
                    class="px-8 py-3 bg-gradient-to-br from-emerald-500 to-teal-600 text-white font-bold rounded-r-full
                       hover:from-teal-600 hover:to-emerald-500 transition-all duration-300 transform hover:scale-105 active:scale-95
                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    Cari
                </button>
            </div>
        </form>

        @if(request('query'))
        <div class="mt-8 p-5 bg-white rounded-xl shadow-lg flex items-center justify-center border border-gray-100 animate-fade-in">
            <p class="text-lg text-gray-700 font-medium text-center">
                Hasil pencarian untuk: <span class="font-extrabold text-emerald-600">"{{ request('query') }}"</span>
                @if(isset($products) && $products->count() > 0)
                <span class="ml-3 px-4 py-1 bg-emerald-100 text-emerald-800 text-sm font-semibold rounded-full shadow-sm">
                    {{ $products->count() }} produk ditemukan
                </span>
                @endif
            </p>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 p-6 md:p-10 bg-gray-50">
        @forelse ($products as $product)
        <div class="bg-white rounded-3xl shadow-xl flex flex-col transform transition-all duration-300 hover:scale-[1.03] hover:shadow-2xl overflow-hidden group border border-gray-100">
            <div class="relative overflow-hidden">
                <img
                    class="w-full h-56 object-cover rounded-t-3xl cursor-pointer transition-transform duration-500 group-hover:scale-110"
                    src="{{ $product->gambar ? asset('storage/' . $product->gambar) : 'https://via.placeholder.com/400/E5E7EB/4B5563?text=Tidak+Ada+Gambar' }}"
                    alt="{{ $product->nama_produk }}"
                    onclick="openImageModal('{{ $product->gambar ? asset('storage/' . $product->gambar) : 'https://via.placeholder.com/400/E5E7EB/4B5563?text=Tidak+Ada+Gambar' }}')">
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-4">
                    <button onclick="openImageModal('{{ $product->gambar ? asset('storage/' . $product->gambar) : 'https://via.placeholder.com/400/E5E7EB/4B5563?text=Tidak+Ada+Gambar' }}')"
                        class="bg-white text-gray-800 px-4 py-2 rounded-full text-sm font-semibold shadow-md hover:bg-gray-100 transition-colors">
                        Lihat Gambar
                    </button>
                </div>
            </div>

            <div class="p-6 flex flex-col flex-grow">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-2 truncate">{{ $product->nama_produk }}</h2>
                <p class="text-base text-gray-600 mb-3 line-clamp-3">{{ $product->deskripsi }}</p>
                <p class="text-xl font-bold text-emerald-700 mb-3">Rp{{ number_format($product->harga, 0, ',', '.') }}</p>
                <div class="text-sm text-gray-500 space-y-1 mb-4">
                    <p><span class="font-medium">Bahan:</span> {{ $product->bahan }}</p>
                    <p><span class="font-medium">Ukuran:</span> {{ $product->ukuran }}</p>
                    <p><span class="font-medium">Stok:</span> <span class="font-semibold {{ $product->stok > 0 ? 'text-emerald-600' : 'text-red-500' }}">{{ $product->stok }}</span></p>
                </div>

                <div class="mt-auto">
                    <button onclick="openLoginModal()" class="block w-full text-center py-3 px-4 rounded-lg shadow-md text-lg font-semibold text-white
                bg-gradient-to-r from-emerald-600 to-teal-600
                hover:from-teal-500 hover:to-emerald-500 hover:scale-105
                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-700
                transition duration-300 ease-in-out transform mt-auto">
                        Pesan Sekarang
                    </button>
                </div>
            </div>
        </div>

        @empty
        <div class="col-span-full text-center py-12">
            <p class="text-2xl text-gray-500 font-semibold">Ups! Tidak ada produk yang ditemukan.</p>
            <p class="text-gray-400 mt-2">Coba cari dengan kata kunci lain atau hapus filter pencarian.</p>
        </div>
        @endforelse
    </div>

    <div id="imageModal" class="fixed inset-0 bg-gray-900 bg-opacity-70 z-[9999] hidden flex items-center justify-center p-4" onclick="closeImageModal()">
        <div class="max-w-5xl w-full bg-white rounded-xl shadow-2xl overflow-hidden relative" onclick="event.stopPropagation()">
            <img id="modalImage" src="" alt="Large preview" class="w-full h-auto max-h-[90vh] object-contain mx-auto">
            <button onclick="closeImageModal()" class="absolute top-4 right-4 bg-gray-800 bg-opacity-70 text-white rounded-full p-3 hover:bg-gray-700 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <script>
        function openLoginModal() {
            const modal = document.getElementById('loginModal');
            const content = document.getElementById('loginModalContent');

            modal.classList.remove('hidden');
            content.innerHTML = 'Loading...';

            fetch('/login')
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Ambil container lengkap yang mencakup gambar dan form
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
        }

        function closeLoginModal() {
            document.getElementById('loginModal').classList.add('hidden');
        }

        function openImageModal(imageUrl) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');

            modalImage.src = imageUrl;
            modal.classList.remove('hidden');

            // Prevent scrolling on the background
            document.body.style.overflow = 'hidden';
        }


        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');

            // Re-enable scrolling
            document.body.style.overflow = 'auto';
        }
    </script>

    @else
    @if (Auth::user()->role === 'admin')
    <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-8 pb-4 border-b border-gray-200">
            <h1 class="text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Manajemen Produk</h1>
            <a href="{{ route('product.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition duration-300 ease-in-out transform hover:scale-105 flex items-center">
                <i class="fas fa-plus mr-2"></i>Tambah Produk Baru
            </a>
        </div>

        @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
            <div class="flex">
                <div class="py-1"><svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                    </svg></div>
                <div>
                    <p class="font-bold">Berhasil!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif
        @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
            <div class="flex">
                <div class="py-1"><svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                    </svg></div>
                <div>
                    <p class="font-bold">Gagal!</p>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            {{-- overflow-x-auto tetap dipertahankan sebagai fallback jika tabel masih terlalu lebar --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 admin-product-table"> {{-- Tambahkan class untuk styling responsif opsional --}}
                    <thead class="bg-green-200">
                        <tr>
                            {{-- Kolom Gambar dibuat lebih ringkas --}}
                            <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-900 uppercase tracking-wider w-20">Gambar</th>
                            {{-- Kolom Nama Produk dibuat lebih fleksibel --}}
                            <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-900 uppercase tracking-wider">Nama Produk - ID - BAHAN - UKURAN</th> {{-- Header digabung --}}
                            {{-- Kolom Deskripsi ditambahkan --}}
                            <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-900 uppercase tracking-wider">Deskripsi</th>
                            {{-- Kolom Harga dibuat ringkas --}}
                            <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-900 uppercase tracking-wider w-28">Harga</th>
                            {{-- Kolom Stok dibuat sangat ringkas --}}
                            <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-900 uppercase tracking-wider w-16">Stok</th>
                            {{-- Kolom Aksi dibuat ringkas dan rata tengah --}}
                            <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-900 uppercase tracking-wider w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($products as $index => $product)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            {{-- Data Kolom Gambar, ukuran tetap kecil --}}
                            <td class="px-3 py-2 whitespace-nowrap" data-label="Gambar:"> {{-- Tambahkan data-label untuk responsif --}}
                                <img
                                    class="w-12 h-12 object-cover rounded-md shadow-sm border border-gray-200"
                                    src="{{ $product->gambar ? asset('storage/' . $product->gambar) : 'https://via.placeholder.com/300/CCCCCC/FFFFFF?text=Tidak+Ada+Gambar' }}"
                                    alt="{{ $product->nama_produk }}"
                                    onclick="openImageModal('{{ $product->gambar ? asset('storage/' . $product->gambar) : 'https://via.placeholder.com/300/CCCCCC/FFFFFF?text=Tidak+Ada+Gambar' }}')">
                            </td>
                            {{-- Data Kolom Nama Produk & ID --}}
                            <td class="px-3 py-2" data-label="Nama Produk - ID - BAHAN - UKURAN:"> {{-- Tambahkan data-label untuk responsif --}}
                                <div class="text-sm font-semibold text-gray-900">{{ $product->nama_produk }}</div>
                                {{-- Menambahkan ID dan Jenis di bawah nama produk --}}
                                <div class="text-xs text-gray-500">ID: {{ $product->id }}</div>
                                @if(isset($product->bahan))
                                <div class="text-xs text-gray-500">Bahan: {{ $product->bahan }}</div>
                                @endif
                                @if(isset($product->ukuran))
                                <div class="text-xs text-gray-500">Ukuran: {{ $product->ukuran }}</div>
                                @endif
                            </td>

                            {{-- Data Kolom Deskripsi --}}
                            <td class="px-3 py-2 text-xs text-gray-600" data-label="Deskripsi:"> {{-- Tambahkan data-label untuk responsif --}}
                                {{-- Tampilkan deskripsi, potong jika terlalu panjang --}}
                                {{ Str::limit($product->deskripsi, 100) }}
                            </td>

                            {{-- Data Kolom Harga --}}
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700" data-label="Harga:">Rp{{ number_format($product->harga, 0, ',', '.') }}</td>

                            {{-- Data Kolom Stok --}}
                            <td class="px-3 py-2 whitespace-nowrap" data-label="Stok:">
                                @if($product->stok > 10)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ $product->stok }}</span>
                                @elseif($product->stok > 0)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ $product->stok }}</span>
                                @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Habis</span>
                                @endif
                            </td>

                            {{-- Data Kolom Aksi, menggunakan flex untuk menata tombol --}}
                            <td class="px-3 py-2 whitespace-nowrap text-center text-sm font-medium" data-label="Aksi:">
                                <div class="flex items-center justify-center space-x-1"> {{-- Menggunakan flexbox untuk tombol --}}
                                    <a href="{{ route('product.edit', $product->id) }}" class="text-indigo-600 hover:text-indigo-800 transition-colors duration-150 ease-in-out p-1 rounded-md hover:bg-indigo-100 text-base" title="Ubah"> {{-- Ukuran icon disesuaikan --}}
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmDelete('{{ $product->id }}', '{{ $product->nama_produk }}')" class="text-red-600 hover:text-red-800 transition-colors duration-150 ease-in-out p-1 rounded-md hover:bg-red-100 text-base" title="Hapus"> {{-- Ukuran icon disesuaikan --}}
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $product->id }}" action="{{ route('product.destroy', $product->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            {{-- colspan disesuaikan karena satu kolom ditambahkan (dari 5 menjadi 6) --}}
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center text-gray-500">
                                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                    </svg>
                                    <p class="text-xl font-semibold mb-1">Tidak Ada Produk</p>
                                    @if($searchQuery)
                                    <p class="text-sm">Tidak ada produk yang cocok dengan pencarian "<span class="font-medium">{{ $searchQuery }}</span>".</p>
                                    <p class="mt-2"><a href="{{ route('products.index') }}" class="text-green-600 hover:text-green-700 hover:underline text-sm">Tampilkan semua produk</a></p>
                                    @else
                                    <p class="mt-2"><a href="{{ route('product.create') }}" class="text-green-600 hover:text-green-700 hover:underline text-sm">Tambahkan produk baru</a></p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- First, add this modal HTML after your table div -->
    <div id="deleteConfirmationModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
            <h2 class="text-lg font-bold mb-4">Apakah Anda yakin ingin menghapus produk ini?</h2>
            <div class="flex justify-center space-x-4">
                <form id="deleteProductForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-400">Ya</button>
                </form>
                <button id="cancelDeleteProduct" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-400">Tidak</button>
            </div>
        </div>
    </div>


    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-[999] hidden flex items-center justify-center">
        <div class="max-w-4xl w-full mx-4 bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="relative">
                <img id="modalImage" src="" alt="Large preview" class="w-full h-auto max-h-[80vh] object-contain">
                <button
                    class="delete-btn text-red-600 hover:text-red-800 transition-colors duration-150 ease-in-out p-1 rounded-md hover:bg-red-100 text-base"
                    title="Hapus"
                    data-product-id="{{ $product->id }}"
                    data-product-name="{{ addslashes($product->nama_produk) }}">
                    <i class="fas fa-trash"></i>
                </button>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Modify your delete button to use the confirmation -->
    <script>
        function confirmDelete(productId, productName) {
            const modal = document.getElementById('deleteConfirmationModal');
            const deleteForm = document.getElementById('deleteProductForm');

            // Set action URL untuk form delete
            deleteForm.action = `/product/destroy/${productId}`;

            // Tampilkan modal
            modal.classList.remove('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('deleteConfirmationModal');
            const deleteForm = document.getElementById('deleteProductForm');
            const cancelButton = document.getElementById('cancelDeleteProduct');

            // Handle cancel button click
            cancelButton.addEventListener('click', function() {
                modal.classList.add('hidden');
            });

            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });

            // Handle the actual delete form submission
            deleteForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Submit the form using fetch to handle the DELETE request
                fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            '_method': 'DELETE',
                            '_token': document.querySelector('input[name="_token"]').value
                        })
                    })
                    .then(response => {
                        if (response.ok) {
                            window.location.reload(); // Reload the page after successful deletion
                        } else {
                            throw new Error('Gagal menghapus produk');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus produk');
                    })
                    .finally(() => {
                        modal.classList.add('hidden');
                    });
            });
        });


        function openImageModal(imageUrl) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');

            modalImage.src = imageUrl;
            modal.classList.remove('hidden');

            // Prevent scrolling on the background
            document.body.style.overflow = 'hidden';
        }


        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');

            // Re-enable scrolling
            document.body.style.overflow = 'auto';
        }
    </script>
    @elseif (Auth::user()->role === 'pelanggan')
    <div class="products-container mx-auto py-10">

        <!-- Form Pencarian -->
        <div class="relative z-10 max-w-3xl mx-auto">
            <form method="GET" action="{{ route('products.index') }}" class="relative">
                <div class="flex items-center bg-white rounded-full shadow-lg border border-gray-200 overflow-hidden
                    focus-within:ring-4 focus-within:ring-emerald-200 focus-within:border-emerald-400
                    transition-all duration-300">
                    <div class="flex-1 relative">
                        <input
                            type="text"
                            name="query"
                            value="{{ request('query') }}"
                            placeholder="Cari produk..."
                            class="w-full h-full pl-14 pr-4 py-3.5 text-gray-800 bg-transparent focus:outline-none text-base sm:text-lg rounded-l-full"
                            autocomplete="off">
                        <div class="absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="px-8 py-3 bg-gradient-to-br from-emerald-500 to-teal-600 text-white font-bold rounded-r-full
                       hover:from-teal-600 hover:to-emerald-500 transition-all duration-300 transform hover:scale-105 active:scale-95
                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        Cari
                    </button>
                </div>
            </form>

            @if(request('query'))
            <div class="mt-8 p-5 bg-white rounded-xl shadow-lg flex items-center justify-center border border-gray-100 animate-fade-in">
                <p class="text-lg text-gray-700 font-medium text-center">
                    Hasil pencarian untuk: <span class="font-extrabold text-emerald-600">"{{ request('query') }}"</span>
                    @if(isset($products) && $products->count() > 0)
                    <span class="ml-3 px-4 py-1 bg-emerald-100 text-emerald-800 text-sm font-semibold rounded-full shadow-sm">
                        {{ $products->count() }} produk ditemukan
                    </span>
                    @endif
                </p>
            </div>
            @endif
        </div>

        @include('product.product-cards')
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 p-6 md:p-10 bg-gray-50">
            @forelse ($products as $product)
            <div class="bg-white rounded-3xl shadow-xl flex flex-col transform transition-all duration-300 hover:scale-[1.03] hover:shadow-2xl overflow-hidden group border border-gray-100">
                {{-- Bagian Gambar --}}
                <div class="relative overflow-hidden">
                    <img
                        class="w-full h-56 object-cover rounded-t-3xl cursor-pointer transition-transform duration-500 group-hover:scale-110"
                        src="{{ $product->gambar ? asset('storage/' . $product->gambar) : 'https://via.placeholder.com/400/E5E7EB/4B5563?text=Tidak+Ada+Gambar' }}"
                        alt="{{ $product->nama_produk }}"
                        onclick="openImageModal('{{ $product->gambar ? asset('storage/' . $product->gambar) : 'https://via.placeholder.com/400/E5E7EB/4B5563?text=Tidak+Ada+Gambar' }}')">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-6">
                        <button onclick="openImageModal('{{ $product->gambar ? asset('storage/' . $product->gambar) : 'https://via.placeholder.com/400/E5E7EB/4B5563?text=Tidak+Ada+Gambar' }}')"
                            class="bg-white text-gray-800 px-4 py-2 rounded-full text-sm font-semibold shadow-md hover:bg-gray-100 transition-colors">
                            Lihat Gambar
                        </button>
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <h2 class="text-2xl font-extrabold text-gray-900 mb-2 truncate">{{ $product->nama_produk }}</h2>
                    <p class="text-base text-gray-600 mb-3 line-clamp-3">{{ $product->deskripsi }}</p>
                    <p class="text-xl font-bold text-emerald-700 mb-3">Rp{{ number_format($product->harga, 0, ',', '.') }}</p>
                    <div class="text-sm text-gray-500 space-y-1 mb-4">
                        <p><span class="font-medium">Bahan:</span> {{ $product->bahan }}</p>
                        <p><span class="font-medium">Ukuran:</span> {{ $product->ukuran }}</p>
                        <p><span class="font-medium">Stok:</span> <span class="font-semibold {{ $product->stok > 0 ? 'text-emerald-600' : 'text-red-500' }}">{{ $product->stok }}</span></p>
                    </div>
                    <a href="javascript:void(0)"
                        class="block w-full text-center py-3 px-4 rounded-lg shadow-md text-lg font-semibold text-white
                bg-gradient-to-r from-emerald-600 to-teal-600
                hover:from-teal-500 hover:to-emerald-500 hover:scale-105
                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-700
                transition duration-300 ease-in-out transform mt-auto"
                        onclick="showOrderModal(
                        '{{ $product->id }}',
                        '{{ $product->nama_produk }}',
                        '{{ $product->harga }}',
                        '{{ $product->gambar ? asset('storage/' . $product->gambar) : 'https://via.placeholder.com/300/CCCCCC/FFFFFF?text=Tidak+Ada+Gambar' }}',
                        '{{ $product->stok }}',
                    )">
                        Pesan Sekarang
                    </a>
                </div>
            </div>

            <!-- Image Modal -->
            <div id="imageModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-[999] hidden flex items-center justify-center">
                <div class="max-w-4xl w-full mx-4 bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="relative">
                        <img id="modalImage" src="" alt="Large preview" class="w-full h-auto max-h-[80vh] object-contain">
                        <button onclick="closeImageModal()" class="absolute top-2 right-2 bg-gray-800 text-white rounded-full p-2 hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal pertama (Order Modal) -->
            <div id="orderModal{{ $product->id }}" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-[999]">
                <div class="bg-white w-[90%] max-w-md p-5 rounded-lg shadow-lg">
                    <img
                        id="modalProductImage{{ $product->id }}"
                        class="w-full h-48 object-cover rounded mb-4"
                        src="{{ $product->gambar ? asset('storage/' . $product->gambar) : 'https://via.placeholder.com/300x200?text=No+Image' }}"
                        alt="Produk"
                        onerror="this.src='https://via.placeholder.com/300x200?text=Image+Not+Found'" />
                    <h3 class="text-xl font-semibold mb-4" id="modalProductName{{ $product->id }}">Nama Produk</h3>
                    <p class="text-gray-700 mb-2" id="modalProductPrice{{ $product->id }}">Harga per Unit: Rp0</p>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-gray-700">Jumlah</span>
                        <div class="flex items-center space-x-2">
                            <button class="px-2 py-1 bg-gray-300 rounded" id="decreaseButton{{ $product->id }}">-</button>
                            <input type="number" id="quantity{{ $product->id }}" name="kuantitas" value="1" min="1" max="{{ $product->stok }}" class="w-16 text-center border rounded px-2 py-1" />
                            <button class="px-2 py-1 bg-gray-300 rounded" id="increaseButton{{ $product->id }}">+</button>
                        </div>
                        <input type="hidden" id="productId{{ $product->id }}" value="{{ $product->id}}">
                    </div>
                    <p class="text-gray-700 mb-2">Total Harga: <span id="modalTotalPrice{{ $product->id }}">Rp0</span></p>
                    <p class="text-gray-700 mb-2">Stok Tersisa: <span id="modalProductStock{{ $product->id }}">0</span></p>
                    <div class="flex justify-between space-x-2">
                        <button type="button" id="buyNowButton{{ $product->id }}" class="bg-green-500 text-white px-4 py-2 rounded flex-1 text-center hover:bg-green-200 hover:text-green-500" onclick="createTransaction('{{ $product->id}}')">Beli Langsung</button>
                        <button type="button" id="addToCartButton{{ $product->id }}" class="bg-blue-500 text-white px-4 py-2 rounded flex-1 text-center hover:bg-blue-200 hover:text-blue-500" onclick="addToCart({{ json_encode($product) }})">Tambah ke Keranjang</button>
                    </div>
                    <button type="button" class="block mt-4 mx-auto bg-gray-800 text-gray-300 px-4 py-2 rounded hover:bg-gray-300 hover:text-gray-800" onclick="closeModal('{{ $product->id }}')">Batal</button>
                </div>
            </div>

            <!-- Modal ke 2 - Updated with Saldo Payment -->
            <div
                id="secondModal{{ $product->id }}"
                class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-[999]">
                <div class="bg-cyan-100 p-4 rounded-lg shadow-lg w-[80%] mt-4 mb-4">
                    <form id="transactionForm{{ $product->id }}" enctype="multipart/form-data">
                        @csrf
                        <div class="flex justify-between items-center">
                            <h1 class="text-lg font-semibold">Pesanan: {{ $product->nama_produk }}</h1>
                        </div>
                        <div class="mt-4">
                            <input
                                type="hidden"
                                id="orderId{{ $product->id }}"
                                name="order_id"
                                value="{{ optional($product->order)->id }}">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-2">Nama Lengkap</label>
                                    <input
                                        type="text"
                                        name="name"
                                        value="{{ Auth::user()->name }}"
                                        class="w-full p-2 border border-gray-300 rounded"
                                        readonly>
                                </div>
                                <div>
                                    <label class="block mb-2">Alamat</label>
                                    <input
                                        type="text"
                                        name="alamat"
                                        value="{{ Auth::user()->alamat }}"
                                        class="w-full p-2 border border-gray-300 rounded"
                                        readonly>
                                </div>
                                <div>
                                    <label class="block mb-2">Nomor Telepon/WA</label>
                                    <input
                                        type="text"
                                        name="telepon"
                                        value="{{ Auth::user()->telepon }}"
                                        class="w-full p-2 border border-gray-300 rounded"
                                        readonly>
                                </div>

                                <div>
                                    <label class="block mb-2">Jumlah Produk</label>
                                    <input
                                        type="text"
                                        name="kuantitas"
                                        id="quantityInput{{ $product->id }}"
                                        value="0"
                                        class="w-full p-2 border border-gray-300 rounded"
                                        readonly>
                                </div>
                                <div>
                                    <label class="block mb-2">Upload Custom Gambar</label>
                                    <input
                                        type="file"
                                        name="custom_image"
                                        accept="image/*"
                                        class="w-full p-2 border border-gray-300 rounded">
                                </div>
                                <div>
                                    <label class="block mb-2">Total Pembayaran</label>
                                    <input
                                        type="text"
                                        name="total_pembayaran"
                                        id="totalPembayaranInput{{ $product->id }}"
                                        value="0"
                                        class="w-full p-2 border border-gray-300 rounded"
                                        readonly>
                                </div>
                            </div>

                            <!-- Informasi Saldo -->
                            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-blue-800">Saldo Anda:</span>
                                    <span class="text-lg font-bold text-blue-900"
                                        id="userSaldo{{ $product->id }}"
                                        data-saldo="{{ Auth::user()->saldo ?? 0 }}">
                                        Rp {{ number_format(Auth::user()->saldo ?? 0, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="mt-2" id="saldoStatus{{ $product->id }}">
                                    <!-- Status saldo akan ditampilkan di sini -->
                                </div>
                            </div>

                            <div class="flex justify-center mt-4 space-x-4">
                                <!-- Tombol Bayar dengan Midtrans -->
                                <button
                                    type="button"
                                    class="flex items-center justify-center gap-2 px-6 py-3 rounded-lg shadow-lg text-lg font-semibold text-white
               bg-gradient-to-r from-blue-500 to-blue-600
               hover:from-blue-600 hover:to-blue-700 hover:scale-105
               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
               transition duration-300 ease-in-out transform"
                                    onclick="submitTransaction('{{ $product->id }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h10m-9 4h8a2 2 0 002-2v-2a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2z" />
                                    </svg>
                                    Bayar dengan Midtrans
                                </button>

                                <!-- Tombol Bayar dengan Saldo -->
                                <button
                                    type="button"
                                    id="paySaldoBtn{{ $product->id }}"
                                    class="flex items-center justify-center gap-2 px-6 py-3 rounded-lg shadow-lg text-lg font-semibold text-white
               bg-gradient-to-r from-emerald-500 to-emerald-600
               hover:from-emerald-600 hover:to-emerald-700 hover:scale-105
               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500
               transition duration-300 ease-in-out transform"
                                    onclick="payWithSaldo('{{ $product->id }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V4m0 8v4m-6 1h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Bayar Pakai Saldo
                                </button>

                                <button
                                    type="button"
                                    class="flex items-center justify-center gap-2 px-6 py-3 rounded-lg shadow-lg text-lg font-semibold text-white
               bg-gradient-to-r from-red-500 to-red-600
               hover:from-red-600 hover:to-red-700 hover:scale-105
               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500
               transition duration-300 ease-in-out transform"
                                    onclick="closeModal('{{ $product->id }}', true)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Cancel
                                </button>
                            </div>
                            @if(session('message'))
                            <script>
                                alert("{{ session('message') }}");
                            </script>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Konfirmasi Cancel -->
            <div id="cancelConfirmationModal{{ $product->id }}" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-[9999] hidden flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
                    <h2 class="text-lg font-bold mb-4">Apakah Anda yakin ingin membatalkan pesanan ini?</h2>
                    <div class="flex justify-end space-x-4">
                        <button onclick="confirmCancel('{{ $product->id }}')" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-400">Ya</button>
                        <button onclick="closeCancelConfirmation('{{ $product->id }}')" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-400">Tidak</button>
                    </div>
                </div>
            </div>

            <!-- Modal Konfirmasi Saldo -->
            <div id="saldoConfirmationModal{{ $product->id }}" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-50 hidden flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
                    <h2 class="text-lg font-bold mb-4">Apakah Anda yakin ingin membayar <span id="modalTotalPembayaran{{ $product->id }}"></span> menggunakan saldo?</h2>
                    <div class="flex justify-end space-x-4">
                        <button onclick="confirmPaySaldo('{{ $product->id }}')" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-500">Ya</button>
                        <button onclick="closeSaldoConfirmation('{{ $product->id }}')" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-400">Tidak</button>
                    </div>
                </div>
            </div>

            @empty
            <div class="col-span-full text-center py-12">
                <p class="text-2xl text-gray-500 font-semibold">Ups! Tidak ada produk yang ditemukan.</p>
                <p class="text-gray-400 mt-2">Coba cari dengan kata kunci lain atau hapus filter pencarian.</p>
            </div>
            @endforelse
        </div>

        <!-- Modal untuk Konfirmasi Barang Ditambahkan ke Keranjang -->
        <div id="cartConfirmationModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-60">
            <div class="bg-white w-[90%] max-w-md p-5 rounded-lg shadow-lg text-center">
                <p class="text-xl font-semibold mb-4">Barang Ditambahkan ke Keranjang!</p>
                <button onclick="closeCartConfirmationModal()" class="bg-teal-500 text-white px-4 py-2 rounded hover:bg-teal-200 hover:text-teal-500">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        let productsData = @json($products);
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const highlightProductId = urlParams.get('highlight');

            console.log('Highlight Product ID:', highlightProductId); // Debug log

            if (highlightProductId) {
                // Cari data product yang ID-nya sama dengan highlightProductId
                let product = productsData.find(p => p.id == highlightProductId); // Menggunakan == untuk membandingkan string dan number

                console.log('Found Product:', product); // Debug log

                if (product) {
                    // Panggil showOrderModal() dengan data product
                    setTimeout(() => { // Tambahkan delay kecil untuk memastikan DOM sudah loaded
                        showOrderModal(
                            product.id,
                            product.nama_produk,
                            product.harga,
                            product.gambar ? `/storage/${product.gambar}` : 'https://via.placeholder.com/300/CCCCCC/FFFFFF?text=Tidak+Ada+Gambar',
                            product.stok
                        );
                    }, 100);
                } else {
                    console.log("Produk dengan ID " + highlightProductId + " tidak ditemukan di productsData");
                    console.log("Available products:", productsData); // Debug log
                }
            }
        });

        function openImageModal(imageUrl) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');

            modalImage.src = imageUrl;
            modal.classList.remove('hidden');

            // Prevent scrolling on the background
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');

            // Re-enable scrolling
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Add keyboard support to close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('imageModal').classList.contains('hidden')) {
                closeImageModal();
            }
        });

        function showOrderModal(productId, productName, productPrice, productImage, productStock, buyUrl) {
            const modal = document.getElementById('orderModal' + productId);
            const quantityElement = document.getElementById('quantity' + productId);
            const totalPriceElement = document.getElementById('modalTotalPrice' + productId);
            const buyNowButton = document.getElementById('buyNowButton' + productId);

            // Pastikan productPrice adalah angka
            const formattedProductPrice = parseFloat(productPrice).toLocaleString();

            // Set data produk ke modal
            document.getElementById('modalProductImage' + productId).src = productImage;
            document.getElementById('modalProductName' + productId).innerText = productName;
            document.getElementById('modalProductStock' + productId).innerText = productStock;
            document.getElementById('modalProductPrice' + productId).innerText = 'Harga per Unit: Rp' + formattedProductPrice;

            // Atur kuantitas awal ke 1
            quantityElement.innerText = 1;

            // Hitung total harga awal
            const initialTotalPrice = parseFloat(productPrice);
            totalPriceElement.innerText = 'Rp' + initialTotalPrice.toLocaleString();

            // Atur URL beli langsung untuk kuantitas awal
            buyNowButton.setAttribute('href', buyUrl + '?quantity=1');

            // Tampilkan modal
            modal.classList.remove('hidden');

            // Event listener untuk tombol + dan -
            const increaseButton = document.getElementById('increaseButton' + productId);
            const decreaseButton = document.getElementById('decreaseButton' + productId);

            const quantityInput = document.getElementById('quantity' + productId);

            quantityInput.addEventListener('input', () => {
                let quantity = parseInt(quantityInput.value);

                if (isNaN(quantity) || quantity < 1) {
                    quantity = 1;
                } else if (quantity > productStock) {
                    quantity = productStock;
                }

                quantityInput.value = quantity;

                const totalPrice = quantity * parseFloat(productPrice);
                totalPriceElement.innerText = 'Rp' + totalPrice.toLocaleString();

                buyNowButton.setAttribute('href', buyUrl + '?quantity=' + quantity);
            });

            increaseButton.onclick = () => {
                let quantity = parseInt(quantityElement.innerText);
                if (quantity < productStock) {
                    quantity++;
                    quantityElement.innerText = quantity;

                    const totalPrice = quantity * parseFloat(productPrice);
                    totalPriceElement.innerText = 'Rp' + totalPrice.toLocaleString();

                    buyNowButton.setAttribute('href', buyUrl + '?quantity=' + quantity);
                }
            };

            decreaseButton.onclick = () => {
                let quantity = parseInt(quantityElement.innerText);
                if (quantity > 1) {
                    quantity--;
                    quantityElement.innerText = quantity;

                    const totalPrice = quantity * parseFloat(productPrice);
                    totalPriceElement.innerText = 'Rp' + totalPrice.toLocaleString();

                    buyNowButton.setAttribute('href', buyUrl + '?quantity=' + quantity);
                }
            };
        }

        // Modify the existing closeModal function
        function closeModal(productId, isCancel = false) {
            if (isCancel) {
                // Show confirmation modal instead of directly canceling
                const confirmationModal = document.getElementById('cancelConfirmationModal' + productId);
                confirmationModal.classList.remove('hidden');
                return;
            }

            const firstModal = document.getElementById('orderModal' + productId);
            const secondModal = document.getElementById('secondModal' + productId);

            if (firstModal) firstModal.classList.add('hidden');
            if (secondModal) secondModal.classList.add('hidden');
        }

        // Add new function to handle cancel confirmation
        function confirmCancel(productId) {
            const orderIdElement = document.getElementById('orderId' + productId);

            if (orderIdElement && orderIdElement.value) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/order/destroy/${orderIdElement.value}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    })
                    .then(async response => {
                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || `HTTP error! status: ${response.status}`);
                        }

                        // Reset orderId and hide all modals
                        orderIdElement.value = '';
                        const firstModal = document.getElementById('orderModal' + productId);
                        const secondModal = document.getElementById('secondModal' + productId);
                        const confirmationModal = document.getElementById('cancelConfirmationModal' + productId);

                        if (firstModal) firstModal.classList.add('hidden');
                        if (secondModal) secondModal.classList.add('hidden');
                        if (confirmationModal) confirmationModal.classList.add('hidden');

                        // Show success message
                        alert(data.message || 'Pesanan berhasil dibatalkan');
                    })
                    .catch(error => {
                        console.error('Error deleting order:', error);
                        alert(error.message || 'Terjadi kesalahan saat membatalkan pesanan. Silakan coba lagi.');
                    });
            }
        }

        // Add function to close cancel confirmation modal
        function closeCancelConfirmation(productId) {
            const confirmationModal = document.getElementById('cancelConfirmationModal' + productId);
            if (confirmationModal) {
                confirmationModal.classList.add('hidden');
            }
        }

        // Helper function for deleting orders
        async function deleteOrder(orderId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch(`/order/destroy/${orderId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Gagal menghapus pesanan');
                }

                return data;
            } catch (error) {
                console.error('Terjadi kesalahan saat menghapus pesanan:', error);
                throw error;
            }
        }

        function createTransaction(productId) {
            const quantity = parseInt(
                document.getElementById('quantity' + productId).value
            );
            const rawPrice = document
                .getElementById('modalTotalPrice' + productId)
                .innerText.replace(/[^\d]/g, '');
            const totalPrice = parseFloat(rawPrice);

            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content');
            const productIdValue = document.getElementById('productId' + productId).value;

            fetch('/order/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        product_id: productIdValue,
                        kuantitas: quantity,
                        total_pembayaran: totalPrice
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(text || 'Terjadi kesalahan');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.order_id) {
                        // simpan order_id ke form modal kedua
                        document.getElementById('orderId' + productId).value = data.order_id;

                        const qtyInput = document.getElementById('quantityInput' + productId);
                        const totalInput = document.getElementById('totalPembayaranInput' + productId);
                        if (qtyInput) qtyInput.value = quantity;
                        if (totalInput) totalInput.value = totalPrice;

                        // Update status saldo
                        updateSaldoStatus(productId);

                        // sembunyikan modal pertama
                        const firstModal = document.getElementById('orderModal' + productId);
                        if (firstModal) firstModal.classList.add('hidden');

                        // tampilkan modal kedua
                        const secondModal = document.getElementById('secondModal' + productId);
                        if (secondModal) secondModal.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + (error.message || 'Unknown error'));
                });
        }

        // Function to show the cart confirmation modal
        function showCartConfirmationModal() {
            const modal = document.getElementById('cartConfirmationModal');
            modal.classList.remove('hidden'); // Menampilkan modal
        }

        // Function to close the cart confirmation modal
        function closeCartConfirmationModal() {
            const modal = document.getElementById('cartConfirmationModal');
            modal.classList.add('hidden'); // Menyembunyikan modal
        }

        // Function to add product to cart and trigger modal flow
        function addToCart(product) {
            const {
                id,
                nama_produk,
                stok,
                harga
            } = product;
            const quantity = parseInt(document.getElementById('quantity' + id)?.textContent || 0);

            if (!id || !quantity) {
                console.error("Produk atau kuantitas tidak valid.");
                return;
            }

            // Cek stok sebelum mengirim request
            if (stok === 0 || quantity > stok) {
                console.error(`Stok ${nama_produk} habis atau tidak mencukupi. Stok tersedia: ${stok}`);
                alert(`Stok ${nama_produk} habis atau tidak mencukupi. Stok tersedia: ${stok}`);
                return;
            }

            fetch('/cart/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        id_produk: id,
                        kuantitas: quantity,
                        subtotal: harga * quantity,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close the order modal
                        const orderModal = document.getElementById('orderModal' + id);
                        orderModal.classList.add('hidden');

                        // Show the confirmation modal
                        const confirmationModal = document.getElementById('cartConfirmationModal');
                        confirmationModal.classList.remove('hidden');

                        console.log("Berhasil menambahkan ke keranjang:", data.cart);
                    } else {
                        console.error("Gagal menambahkan ke keranjang:", data.message);
                        alert("Gagal menambahkan ke keranjang: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Terjadi kesalahan saat menambahkan ke keranjang");
                });
        }

        // Update fungsi submitTransaction yang sudah ada
        function submitTransaction(productId) {
            const form = document.getElementById('transactionForm' + productId);
            const formData = new FormData(form);

            // Validasi
            if (!validateForm(formData)) {
                return;
            }

            // Tambahkan order_id ke formData
            const orderId = document.getElementById('orderId' + productId).value;
            formData.append('order_id', orderId);
            formData.append('payment_method', 'midtrans');

            // Kirim request
            fetch('/transaction/store', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.snap_token) {
                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                handlePaymentSuccess(result, data.transaction_id);
                            },
                            onPending: function(result) {
                                handlePaymentPending(result, data.transaction_id);
                            },
                            onError: function(result) {
                                handlePaymentError(result);
                            },
                            onClose: function() {
                                handlePaymentClose();
                            }
                        });
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + (error.message || 'Unknown error'));
                });
        }

        // Fungsi helper untuk validasi form
        function validateForm(formData) {
            // Tambahkan validasi sesuai kebutuhan
            return true;
        }

        // Handler untuk berbagai status pembayaran Midtrans
        function handlePaymentSuccess(result, transactionId) {
            alert('Pembayaran berhasil!');
            window.location.href = '/product';
        }

        function handlePaymentPending(result, transactionId) {
            alert('Pembayaran pending. Silakan selesaikan pembayaran Anda.');
            window.location.href = '/product';
        }

        function handlePaymentError(result) {
            alert('Pembayaran gagal. Silakan coba lagi.');
        }

        function handlePaymentClose() {
            console.log('Payment popup closed');
        }

        // 1. PERBAIKAN: Tambahkan logging untuk debugging
        function updateSaldoStatus(productId) {
            const userSaldoElement = document.getElementById('userSaldo' + productId);
            const saldoStatusElement = document.getElementById('saldoStatus' + productId);
            const totalPembayaranInput = document.getElementById('totalPembayaranInput' + productId);
            const paySaldoBtn = document.getElementById('paySaldoBtn' + productId);

            if (!userSaldoElement || !totalPembayaranInput) return;

            const userSaldo = parseInt(userSaldoElement.dataset.saldo) || 0;
            const totalPembayaran = parseInt(totalPembayaranInput.value) || 0;

            if (userSaldo >= totalPembayaran) {
                saldoStatusElement.innerHTML = '<span class="text-sm text-green-600 font-medium">✓ Saldo mencukupi</span>';
                paySaldoBtn.disabled = false;
                paySaldoBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                const kurang = totalPembayaran - userSaldo;
                saldoStatusElement.innerHTML = `<span class="text-sm text-red-600 font-medium">✗ Saldo kurang Rp ${kurang.toLocaleString()}</span>`;
                paySaldoBtn.disabled = true;
                paySaldoBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        // 2. PERBAIKAN: Fungsi validasi yang lebih lengkap
        function validateForm(formData) {
            console.log('Validating form...');

            // Ambil semua data dari FormData untuk debugging
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            const kuantitas = formData.get('kuantitas');
            const totalPembayaran = formData.get('total_pembayaran');

            if (!kuantitas || kuantitas == '0') {
                alert('Jumlah produk tidak boleh kosong atau 0');
                return false;
            }

            if (!totalPembayaran || totalPembayaran == '0') {
                alert('Total pembayaran tidak boleh kosong atau 0');
                return false;
            }

            console.log('Form validation passed');
            return true;
        }

        // 3. PERBAIKAN: Event listener yang lebih komprehensif
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded - Setting up event listeners');

            // TAMBAHAN: Force update semua status saldo setelah DOM ready
            setTimeout(function() {
                document.querySelectorAll('[id^="paySaldoBtn"]').forEach(function(button) {
                    const productId = button.id.replace('paySaldoBtn', '');
                    console.log('Force updating status for product:', productId);
                    updateSaldoStatus(productId);
                });
            }, 100);

            // Update status saldo ketika input total pembayaran berubah
            document.querySelectorAll('[id^="totalPembayaranInput"]').forEach(function(input) {
                const productId = input.id.replace('totalPembayaranInput', '');
                console.log('Setting up listeners for product:', productId);

                // Multiple event listeners untuk memastikan perubahan tertangkap
                ['input', 'change', 'keyup', 'blur'].forEach(function(eventType) {
                    input.addEventListener(eventType, function() {
                        console.log(`${eventType} event triggered for product ${productId}`);
                        updateSaldoStatus(productId);
                    });
                });

                // Initial update
                updateSaldoStatus(productId);
            });

            // TAMBAHAN: Update ketika input kuantitas berubah (jika ada)
            document.querySelectorAll('[id^="quantityInput"]').forEach(function(input) {
                const productId = input.id.replace('quantityInput', '');

                ['input', 'change', 'keyup', 'blur'].forEach(function(eventType) {
                    input.addEventListener(eventType, function() {
                        console.log(`Quantity ${eventType} event triggered for product ${productId}`);
                        // Delay sedikit untuk memastikan total pembayaran sudah diupdate
                        setTimeout(function() {
                            updateSaldoStatus(productId);
                        }, 10);
                    });
                });
            });

            // TAMBAHAN: Observer untuk perubahan programmatic
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' &&
                        (mutation.attributeName === 'value' || mutation.attributeName === 'data-saldo')) {
                        const target = mutation.target;

                        if (target.id && target.id.startsWith('totalPembayaranInput')) {
                            const productId = target.id.replace('totalPembayaranInput', '');
                            console.log('Mutation observed for totalPembayaran:', productId);
                            updateSaldoStatus(productId);
                        } else if (target.id && target.id.startsWith('userSaldo')) {
                            const productId = target.id.replace('userSaldo', '');
                            console.log('Mutation observed for userSaldo:', productId);
                            updateSaldoStatus(productId);
                        }
                    }

                    // Observer untuk perubahan textContent/innerHTML
                    if (mutation.type === 'childList' || mutation.type === 'characterData') {
                        const target = mutation.target;
                        if (target.id && target.id.startsWith('totalPembayaranInput')) {
                            const productId = target.id.replace('totalPembayaranInput', '');
                            console.log('Content change observed for totalPembayaran:', productId);
                            setTimeout(function() {
                                updateSaldoStatus(productId);
                            }, 10);
                        }
                    }
                });
            });

            // Observe semua input yang relevan
            document.querySelectorAll('[id^="totalPembayaranInput"], [id^="userSaldo"]').forEach(function(element) {
                observer.observe(element, {
                    attributes: true,
                    attributeFilter: ['value', 'data-saldo'],
                    childList: true,
                    characterData: true,
                    subtree: true
                });
            });
        });

        function confirmPaySaldo(productId) {
            const form = document.getElementById('transactionForm' + productId);
            const formData = new FormData(form);

            const totalPembayaran = parseFloat(document.getElementById('totalPembayaranInput' + productId).value.replace(/[^\d]/g, '')) || 0;
            const orderId = document.getElementById('orderId' + productId).value;

            formData.append('order_id', orderId);
            formData.append('payment_method', 'saldo');

            const payButton = document.getElementById('paySaldoBtn' + productId);
            const originalText = payButton.innerHTML;
            payButton.disabled = true;
            payButton.innerHTML = 'Memproses...';

            fetch('/transaction/store', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) return response.json().then(err => Promise.reject(err));
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Pembayaran berhasil! Pesanan Anda sedang diproses.');
                        if (data.remaining_saldo !== undefined) {
                            const saldoElement = document.getElementById('userSaldo' + productId);
                            saldoElement.setAttribute('data-saldo', data.remaining_saldo);
                            saldoElement.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.remaining_saldo);
                            updateSaldoStatus(productId);
                        }
                        closeSaldoConfirmation(productId);
                        window.location.reload();
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + (error.message || 'Unknown error'));
                })
                .finally(() => {
                    payButton.disabled = false;
                    payButton.innerHTML = originalText;
                });
        }

        function closeSaldoConfirmation(productId) {
            document.getElementById('saldoConfirmationModal' + productId).classList.add('hidden');
        }


        // 4. FUNGSI YANG SUDAH ADA - Update dengan logging
        function payWithSaldo(productId) {
            const form = document.getElementById('transactionForm' + productId);
            const formData = new FormData(form);

            // Validasi
            if (!validateForm(formData)) {
                return;
            }

            // Tambahkan order_id dan payment_method ke formData
            const orderId = document.getElementById('orderId' + productId).value;
            formData.append('order_id', orderId);
            formData.append('payment_method', 'saldo');

            // Disable button untuk mencegah double click
            const payButton = document.getElementById('paySaldoBtn' + productId);
            const originalText = payButton.textContent;
            payButton.disabled = true;
            payButton.textContent = 'Memproses...';

            // Kirim request
            fetch('/transaction/store', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Pembayaran berhasil
                        alert(data.message);

                        // Tutup modal
                        const secondModal = document.getElementById('secondModal' + productId);
                        if (secondModal) secondModal.classList.add('hidden');

                        // Redirect atau refresh halaman
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            window.location.reload();
                        }
                    } else {
                        throw new Error(data.message || 'Pembayaran gagal');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + (error.message || 'Unknown error'));
                })
                .finally(() => {
                    // Enable button kembali
                    payButton.disabled = false;
                    payButton.textContent = originalText;
                });
        }

        // 5. FUNGSI DEBUGGING - Tambahkan untuk testing
        function debugSaldoStatus(productId) {
            console.log('=== DEBUG SALDO STATUS ===');
            console.log('Product ID:', productId);

            const elements = {
                totalPembayaran: document.getElementById('totalPembayaranInput' + productId),
                saldo: document.getElementById('userSaldo' + productId),
                status: document.getElementById('saldoStatus' + productId),
                payButton: document.getElementById('paySaldoBtn' + productId)
            };

            Object.keys(elements).forEach(key => {
                const element = elements[key];
                console.log(`${key}:`, {
                    exists: !!element,
                    value: element?.value || element?.textContent || 'N/A',
                    dataSaldo: element?.getAttribute?.('data-saldo') || 'N/A',
                    disabled: element?.disabled,
                    classList: element?.classList?.toString() || 'N/A'
                });
            });

            console.log('=========================');
        }

        function validateForm(formData) {
            const requiredFields = {
                'total_pembayaran': 'Total Pembayaran'
            };

            let isValid = true;
            let errorMessage = 'Mohon lengkapi data berikut:\n';

            for (const [field, label] of Object.entries(requiredFields)) {
                if (!formData.get(field)) {
                    errorMessage += `- ${label}\n`;
                    isValid = false;
                }
            }

            if (!isValid) {
                alert(errorMessage);
            }

            return isValid;
        }

        function handlePaymentSuccess(result, transactionId) {
            console.log('success', result);
            window.location.href = `/product`;
        }

        function handlePaymentPending(result, transactionId) {
            console.log('pending', result);
            window.location.href = `/product`;
        }

        function handlePaymentError(result) {
            console.log('error', result);
            alert('Pembayaran gagal!');
        }

        function handlePaymentClose() {
            alert('Anda menutup popup tanpa menyelesaikan pembayaran');
        }

        // SOLUSI 1: Tambahkan fungsi untuk memicu update saldo secara manual
        function triggerSaldoUpdate(productId) {
            console.log('Manually triggering saldo update for product:', productId);
            updateSaldoStatus(productId);
        }

        // SOLUSI 2: Gunakan MutationObserver yang lebih spesifik untuk readonly fields
        function setupReadonlyFieldObserver() {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'value') {
                        const target = mutation.target;

                        if (target.id && target.id.startsWith('quantityInput')) {
                            const productId = target.id.replace('quantityInput', '');
                            console.log('Quantity value changed via mutation for product:', productId);
                            setTimeout(() => triggerSaldoUpdate(productId), 10);
                        }

                        if (target.id && target.id.startsWith('totalPembayaranInput')) {
                            const productId = target.id.replace('totalPembayaranInput', '');
                            console.log('Total pembayaran changed via mutation for product:', productId);
                            setTimeout(() => triggerSaldoUpdate(productId), 10);
                        }
                    }

                    // Observe perubahan property value (bukan attribute)
                    if (mutation.type === 'childList' && mutation.target.tagName === 'INPUT') {
                        const input = mutation.target;
                        if (input.id && (input.id.startsWith('quantityInput') || input.id.startsWith('totalPembayaranInput'))) {
                            const productId = input.id.replace(/^(quantityInput|totalPembayaranInput)/, '');
                            console.log('Input content changed for product:', productId);
                            setTimeout(() => triggerSaldoUpdate(productId), 10);
                        }
                    }
                });
            });

            // Observe semua input yang readonly
            document.querySelectorAll('[id^="quantityInput"], [id^="totalPembayaranInput"]').forEach(function(element) {
                observer.observe(element, {
                    attributes: true,
                    attributeFilter: ['value'],
                    childList: true,
                    subtree: true,
                    characterData: true
                });

                // Tambahkan observer untuk parent element juga
                if (element.parentNode) {
                    observer.observe(element.parentNode, {
                        childList: true,
                        subtree: true
                    });
                }
            });
        }

        // SOLUSI 3: Polling sebagai fallback
        function startSaldoPolling() {
            setInterval(function() {
                document.querySelectorAll('[id^="quantityInput"]').forEach(function(input) {
                    const productId = input.id.replace('quantityInput', '');
                    const currentValue = input.value;

                    // Simpan nilai terakhir yang dicheck
                    if (!input.dataset.lastCheckedValue) {
                        input.dataset.lastCheckedValue = currentValue;
                    }

                    // Jika nilai berubah, update saldo
                    if (input.dataset.lastCheckedValue !== currentValue) {
                        console.log(`Polling detected change for product ${productId}: ${input.dataset.lastCheckedValue} -> ${currentValue}`);
                        input.dataset.lastCheckedValue = currentValue;
                        triggerSaldoUpdate(productId);
                    }
                });
            }, 500); // Check setiap 500ms
        }

        // SOLUSI 4: Override setValue function untuk readonly inputs
        function createReadonlyInputSetter() {
            // Override untuk quantity input
            window.setQuantityValue = function(productId, value) {
                const input = document.getElementById('quantityInput' + productId);
                if (input) {
                    input.value = value;
                    console.log(`Set quantity value for product ${productId}: ${value}`);
                    // Trigger update saldo setelah set value
                    setTimeout(() => triggerSaldoUpdate(productId), 10);
                }
            };

            // Override untuk total pembayaran input
            window.setTotalPembayaranValue = function(productId, value) {
                const input = document.getElementById('totalPembayaranInput' + productId);
                if (input) {
                    input.value = value;
                    console.log(`Set total pembayaran value for product ${productId}: ${value}`);
                    // Trigger update saldo setelah set value
                    setTimeout(() => triggerSaldoUpdate(productId), 10);
                }
            };
        }

        // SOLUSI 5: Event listener untuk semua kemungkinan perubahan
        function setupComprehensiveEventListeners() {
            document.addEventListener('DOMContentLoaded', function() {
                console.log('Setting up comprehensive event listeners...');

                // Setup observer untuk readonly fields
                setupReadonlyFieldObserver();

                // Setup polling sebagai fallback
                startSaldoPolling();

                // Setup custom setter functions
                createReadonlyInputSetter();

                // Setup click listeners untuk readonly fields (untuk debugging)
                document.querySelectorAll('[id^="quantityInput"]').forEach(function(input) {
                    const productId = input.id.replace('quantityInput', '');

                    input.addEventListener('click', function() {
                        console.log('Quantity input clicked for product:', productId);
                        setTimeout(() => triggerSaldoUpdate(productId), 10);
                    });

                    input.addEventListener('focus', function() {
                        console.log('Quantity input focused for product:', productId);
                        setTimeout(() => triggerSaldoUpdate(productId), 10);
                    });
                });

                // Initial update untuk semua produk
                setTimeout(function() {
                    document.querySelectorAll('[id^="quantityInput"]').forEach(function(input) {
                        const productId = input.id.replace('quantityInput', '');
                        console.log('Initial saldo update for product:', productId);
                        triggerSaldoUpdate(productId);
                    });
                }, 100);

                // Update setiap kali modal dibuka
                const modalObserver = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                            const target = mutation.target;
                            if (target.id && target.id.startsWith('secondModal')) {
                                const productId = target.id.replace('secondModal', '');
                                if (!target.classList.contains('hidden')) {
                                    console.log('Modal opened for product:', productId);
                                    setTimeout(() => triggerSaldoUpdate(productId), 100);
                                }
                            }
                        }
                    });
                });

                document.querySelectorAll('[id^="secondModal"]').forEach(function(modal) {
                    modalObserver.observe(modal, {
                        attributes: true,
                        attributeFilter: ['class']
                    });
                });
            });
        }

        // Jalankan setup
        setupComprehensiveEventListeners();
    </script>

</div>
@endif
@endguest
@endsection
@section("scripts")
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endsection