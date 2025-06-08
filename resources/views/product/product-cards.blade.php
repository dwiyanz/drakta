<!-- <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-4 sm:px-6 lg:px-8 py-8"> {{-- Added some horizontal and vertical padding for the grid container itself --}}
    @forelse ($products as $product)
    <div class="bg-white text-gray-900 p-6 rounded-2xl shadow-xl flex flex-col h-full transform transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl"> {{-- Updated card styling --}}
    <img
        class="w-full h-48 object-cover rounded-xl cursor-pointer hover:opacity-80 transition-opacity mb-4"
        src="{{ $product->gambar ? asset('storage/' . $product->gambar) : 'https://via.placeholder.com/300/CCCCCC/FFFFFF?text=Tidak+Ada+Gambar' }}"
        alt="{{ $product->nama_produk }}"
        onclick="openImageModal('{{ $product->gambar ? asset('storage/' . $product->gambar) : 'https://via.placeholder.com/300/CCCCCC/FFFFFF?text=Tidak+Ada+Gambar' }}')">
        <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $product->nama_produk }}</h2> {{-- Added text color and adjusted mb --}}
        <p class="text-base text-gray-700 mb-2">{{ $product->deskripsi }}</p> {{-- Added text size and color --}}
        <p class="text-lg font-semibold text-emerald-700 mb-2">Harga Rp{{ number_format($product->harga, 0, ',', '.') }}</p> {{-- Highlighted price --}}
        <p class="text-sm text-gray-600 mb-1">Bahan: {{ $product->bahan }}</p> {{-- Added text size and color --}}
        <p class="text-sm text-gray-600 mb-1">Ukuran: {{ $product->ukuran }}</p> {{-- Added text size and color --}}
        <p class="text-sm text-gray-600 mb-4">Stok: {{ $product->stok }}</p> {{-- Added text size and color --}}

        <a href="javascript:void(0)"
            class="block w-full text-center py-3 px-4 rounded-lg shadow-md text-lg font-semibold text-white
           bg-gradient-to-r from-emerald-600 to-teal-600
           hover:from-teal-500 hover:to-emerald-500 hover:scale-105
           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-700
           transition duration-300 ease-in-out transform mt-auto" {{-- Updated button styling --}}
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
    @empty
    <p class="text-center text-gray-500 col-span-full py-12">Tidak ada produk yang ditemukan.</p> {{-- Added col-span-full for centering in grid --}}
    @endforelse
</div> -->