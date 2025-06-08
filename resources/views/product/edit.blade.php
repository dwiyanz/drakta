@extends('layouts.app')

@section('content')

{{-- Main container for the form with a clean, bright background and subtle shadow --}}
{{-- Using a very light background with a subtle gradient and increased shadow depth --}}
<div class="container mx-auto px-8 py-10 bg-gradient-to-r from-blue-100 via-teal-100 to-purple-100 p-8 rounded-2xl shadow-2xl mt-8 mb-8 max-w-3xl"> {{-- Light green to teal gradient background, larger rounded corners, deeper shadow, max width --}}

    {{-- Title of the form --}}
    {{-- Styled for prominence and better visual hierarchy with a vibrant green color --}}
    <h1 class="text-center text-4xl font-extrabold text-gray-800 mb-12 drop-shadow-sm">Edit Produk</h1> {{-- Vibrant green title for readability on light background, added subtle text shadow --}}

    {{-- The form for editing product details --}}
    {{-- Increased vertical space between form groups and added a subtle border --}}
    <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8 border-t border-gray-200 pt-8"> {{-- Increased vertical space, added top border and padding --}}
        @csrf
        @method('PUT')

        {{-- Input field for Product Name --}}
        <div>
            {{-- Label text color updated for better contrast on light background --}}
            <label for="nama_produk" class="block text-sm font-semibold text-gray-700 mb-2">Nama Produk</label> {{-- Label text color (slightly darker gray for contrast) --}}
            {{-- Input styling with light border, green focus indicator, padding, and transition --}}
            <input type="text" name="nama_produk" id="nama_produk" value="{{ old('nama_produk', $product->nama_produk) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md" required> {{-- Light gray border, green focus ring, increased padding, added transition and focus shadow --}}
        </div>

        {{-- Textarea for Product Description --}}
        <div>
            {{-- Label text color updated for better contrast on light background --}}
            <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label> {{-- Label text color (slightly darker gray for contrast) --}}
            {{-- Using textarea for multi-line description input with light border, green focus, padding, and transition --}}
            <textarea name="deskripsi" id="deskripsi" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md" required>{{ old('deskripsi', $product->deskripsi) }}</textarea> {{-- Light gray border, green focus ring, increased padding, rows set to 4, added transition and focus shadow --}}
        </div>

        {{-- Input field for Price --}}
        <div>
            {{-- Label text color updated for better contrast on light background --}}
            <label for="harga" class="block text-sm font-semibold text-gray-700 mb-2">Harga (Rp)</label> {{-- Label text color (slightly darker gray for contrast) --}}
            {{-- Input styling with light border, green focus indicator, padding, and transition --}}
            <input type="number" step="0.01" name="harga" id="harga" value="{{ old('harga', $product->harga) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md" required> {{-- Light gray border, green focus ring, increased padding, added transition and focus shadow --}}
        </div>

        {{-- Input field for Material --}}
        <div>
            {{-- Label text color updated for better contrast on light background --}}
            <label for="bahan" class="block text-sm font-semibold text-gray-700 mb-2">Bahan</label> {{-- Label text color (slightly darker gray for contrast) --}}
            {{-- Input styling with light border, green focus indicator, padding, and transition --}}
            <input type="text" name="bahan" id="bahan" value="{{ old('bahan', $product->bahan) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md" required> {{-- Light gray border, green focus ring, increased padding, added transition and focus shadow --}}
        </div>

        {{-- Input field for Size --}}
        <div>
            {{-- Label text color updated for better contrast on light background --}}
            <label for="ukuran" class="block text-sm font-semibold text-gray-700 mb-2">Ukuran</label> {{-- Label text color (slightly darker gray for contrast) --}}
            {{-- Input styling with light border, green focus indicator, padding, and transition --}}
            <input type="text" name="ukuran" id="ukuran" value="{{ old('ukuran', $product->ukuran) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md" required> {{-- Light gray border, green focus ring, increased padding, added transition and focus shadow --}}
        </div>

        {{-- Input field for Stock --}}
        <div>
            {{-- Label text color updated for better contrast on light background --}}
            <label for="stok" class="block text-sm font-semibold text-gray-700 mb-2">Stok</label> {{-- Label text color (slightly darker gray for contrast) --}}
            {{-- Input styling with light border, green focus indicator, padding, and transition --}}
            <input type="number" name="stok" id="stok" value="{{ old('stok', $product->stok) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md" required> {{-- Light gray border, green focus ring, increased padding, added transition and focus shadow --}}
        </div>

        {{-- Input field for Image Upload --}}
        <div>
            {{-- Label text color updated for better contrast on light background --}}
            <label for="gambar" class="block text-sm font-semibold text-gray-700 mb-2">Gambar</label> {{-- Label text color (slightly darker gray for contrast) --}}
            {{-- File input styling with bright green accent and transition --}}
            <input type="file" name="gambar" id="gambar" class="mt-1 block w-full text-sm text-gray-600
                file:mr-4 file:py-3 file:px-6 {{-- Increased padding for file button --}}
                file:rounded-full file:border-0
                file:text-sm file:font-bold {{-- Changed font weight to bold --}}
                file:bg-teal-200 file:text-teal-800 {{-- Bright teal background and text for file button --}}
                hover:file:bg-teal-300 {{-- Lighter teal on hover --}}
                transition duration-300 ease-in-out {{-- Added transition for hover effect --}}
                cursor-pointer
            ">
            @if($product->gambar)
                {{-- Display current image preview with styling and link --}}
                <p class="text-sm text-gray-600 mt-3">Gambar saat ini: <a href="{{ asset('storage/' . $product->gambar) }}" target="_blank" class="text-green-600 hover:underline font-medium">Lihat Gambar</a></p> {{-- Link color remains green-600 for readability, added font-medium --}}
            @endif
        </div>

        {{-- Submit Button for updating the product --}}
        <div>
            {{-- Button styling with vibrant green background, deeper shadow, and refined hover effect --}}
            <button type="submit" class="w-full inline-flex justify-center py-3 px-6 border border-transparent shadow-xl text-lg font-bold rounded-xl text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-2xl"> {{-- Vibrant green background, white text, deeper shadow, larger rounded corners, hover scale and deeper shadow effect --}}
                Update Produk
            </button>
        </div>
    </form>
</div>

@endsection
