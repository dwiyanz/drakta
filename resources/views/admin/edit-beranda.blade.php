@extends('layouts.app')

@section('content')

{{-- Main container for the form with a clean, bright background and subtle shadow --}}
<div class="container mx-auto px-4 py-8">
    <div class="bg-white p-8 rounded-2xl shadow-xl mt-8 mb-8 max-w-4xl mx-auto border border-gray-100">

        {{-- Title of the form --}}
        <h1 class="text-center text-4xl font-extrabold text-gray-900 mb-10 drop-shadow-sm border-b border-gray-200">Edit Beranda</h1>

        {{-- Success message --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm mb-8" role="alert">
                <p class="font-bold">Sukses!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- The form for editing Beranda details --}}
        <form action="{{ route('admin.beranda.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Bagian Gambar Utama -->
            <div class="form-group bg-gray-50 p-6 rounded-lg shadow-sm border border-gray-100">
                <label for="gambar_utama" class="block text-lg font-semibold text-gray-800 mb-3">Gambar Utama</label>
                <input type="file" name="gambar_utama" id="gambar_utama" class="block w-full text-sm text-gray-700
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-100 file:text-blue-700
                    hover:file:bg-blue-200 cursor-pointer transition duration-300 ease-in-out">
                @if($beranda->gambar_utama)
                    <img src="{{ asset($beranda->gambar_utama) }}" alt="Gambar Utama" class="mt-6 rounded-lg shadow-md border border-gray-200 w-full object-cover" style="max-height: 250px;">
                @endif
                <p class="text-sm text-gray-500 mt-2">Ukuran ideal: 1920x1080px</p>
            </div>

            <!-- Grup Gambar Carousel dan Link Produk -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Gambar Carousel 1 & Link Produk 1 -->
                <div class="form-group bg-gray-50 p-6 rounded-lg shadow-sm border border-gray-100">
                    <label for="gambar_carousel1" class="block text-lg font-semibold text-gray-800 mb-3">Gambar Carousel 1</label>
                    <input type="file" name="gambar_carousel1" id="gambar_carousel1" class="block w-full text-sm text-gray-700
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-100 file:text-blue-700
                        hover:file:bg-blue-200 cursor-pointer transition duration-300 ease-in-out">
                    @if($beranda->gambar_carousel1)
                        <img src="{{ asset($beranda->gambar_carousel1) }}" alt="Carousel 1" class="mt-6 rounded-lg shadow-md border border-gray-200 w-full object-cover" style="max-height: 180px;">
                    @endif
                    <div class="mt-6">
                        <label for="link1_g1" class="block text-sm font-semibold text-gray-700 mb-2">Link Produk 1</label>
                        <input type="text" name="link1_g1" id="link1_g1" value="{{ old('link1_g1', $beranda->link1_g1) }}" class="block w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition duration-300 ease-in-out">
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Ukuran ideal: 800x450px</p>
                </div>

                <!-- Gambar Carousel 2 & Link Produk 2 -->
                <div class="form-group bg-gray-50 p-6 rounded-lg shadow-sm border border-gray-100">
                    <label for="gambar_carousel2" class="block text-lg font-semibold text-gray-800 mb-3">Gambar Carousel 2</label>
                    <input type="file" name="gambar_carousel2" id="gambar_carousel2" class="block w-full text-sm text-gray-700
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-100 file:text-blue-700
                        hover:file:bg-blue-200 cursor-pointer transition duration-300 ease-in-out">
                    @if($beranda->gambar_carousel2)
                        <img src="{{ asset($beranda->gambar_carousel2) }}" alt="Carousel 2" class="mt-6 rounded-lg shadow-md border border-gray-200 w-full object-cover" style="max-height: 180px;">
                    @endif
                    <div class="mt-6">
                        <label for="link1_g2" class="block text-sm font-semibold text-gray-700 mb-2">Link Produk 2</label>
                        <input type="text" name="link1_g2" id="link1_g2" value="{{ old('link1_g2', $beranda->link1_g2) }}" class="block w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition duration-300 ease-in-out">
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Ukuran ideal: 800x450px</p>
                </div>

                <!-- Gambar Carousel 3 & Link Produk 3 -->
                <div class="form-group bg-gray-50 p-6 rounded-lg shadow-sm border border-gray-100">
                    <label for="gambar_carousel3" class="block text-lg font-semibold text-gray-800 mb-3">Gambar Carousel 3</label>
                    <input type="file" name="gambar_carousel3" id="gambar_carousel3" class="block w-full text-sm text-gray-700
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-100 file:text-blue-700
                        hover:file:bg-blue-200 cursor-pointer transition duration-300 ease-in-out">
                    @if($beranda->gambar_carousel3)
                        <img src="{{ asset($beranda->gambar_carousel3) }}" alt="Carousel 3" class="mt-6 rounded-lg shadow-md border border-gray-200 w-full object-cover" style="max-height: 180px;">
                    @endif
                    <div class="mt-6">
                        <label for="link1_g3" class="block text-sm font-semibold text-gray-700 mb-2">Link Produk 3</label>
                        <input type="text" name="link1_g3" id="link1_g3" value="{{ old('link1_g3', $beranda->link1_g3) }}" class="block w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition duration-300 ease-in-out">
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Ukuran ideal: 800x450px</p>
                </div>
            </div>


            <!-- Section 2 Text Fields -->
            <div class="form-group bg-gray-50 p-6 rounded-lg shadow-sm border border-gray-100">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Section 2 Teks</h3>
                <div class="space-y-6">
                    <div>
                        <label for="sec2_text1" class="block text-sm font-semibold text-gray-700 mb-2">Section 2 - Text 1</label>
                        <textarea name="sec2_text1" id="sec2_text1" rows="3" class="block w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition duration-300 ease-in-out">{{ old('sec2_text1', $beranda->sec2_text1) }}</textarea>
                    </div>
                    <div>
                        <label for="sec2_text2" class="block text-sm font-semibold text-gray-700 mb-2">Section 2 - Text 2</label>
                        <textarea name="sec2_text2" id="sec2_text2" rows="3" class="block w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition duration-300 ease-in-out">{{ old('sec2_text2', $beranda->sec2_text2) }}</textarea>
                    </div>
                    <div>
                        <label for="sec2_text3" class="block text-sm font-semibold text-gray-700 mb-2">Section 2 - Text 3</label>
                        <textarea name="sec2_text3" id="sec2_text3" rows="3" class="block w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition duration-300 ease-in-out">{{ old('sec2_text3', $beranda->sec2_text3) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 3 Fields -->
            <div class="form-group bg-gray-50 p-6 rounded-lg shadow-sm border border-gray-100">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Section 3</h3>
                <div class="space-y-6">
                    <div>
                        <label for="sec3_judul" class="block text-sm font-semibold text-gray-700 mb-2">Section 3 - Judul</label>
                        <input type="text" name="sec3_judul" id="sec3_judul" value="{{ old('sec3_judul', $beranda->sec3_judul) }}" class="block w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition duration-300 ease-in-out">
                    </div>
                    <div>
                        <label for="sec3_text1" class="block text-sm font-semibold text-gray-700 mb-2">Section 3 - Text 1</label>
                        <textarea name="sec3_text1" id="sec3_text1" rows="3" class="block w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition duration-300 ease-in-out">{{ old('sec3_text1', $beranda->sec3_text1) }}</textarea>
                    </div>
                    <div>
                        <label for="sec3_text2" class="block text-sm font-semibold text-gray-700 mb-2">Section 3 - Text 2</label>
                        <textarea name="sec3_text2" id="sec3_text2" rows="3" class="block w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition duration-300 ease-in-out">{{ old('sec3_text2', $beranda->sec3_text2) }}</textarea>
                    </div>
                    <div>
                        <label for="sec3_text3" class="block text-sm font-semibold text-gray-700 mb-2">Section 3 - Text 3</label>
                        <textarea name="sec3_text3" id="sec3_text3" rows="3" class="block w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition duration-300 ease-in-out">{{ old('sec3_text3', $beranda->sec3_text3) }}</textarea>
                    </div>
                    <div>
                        <label for="sec3_map" class="block text-sm font-semibold text-gray-700 mb-2">Section 3 - Map</label>
                        <textarea name="sec3_map" id="sec3_map" rows="5" class="block w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition duration-300 ease-in-out">{{ old('sec3_map', $beranda->sec3_map) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Submit Button for saving changes --}}
            <div>
                <button type="submit" class="w-full inline-flex justify-center py-3 px-6 border border-transparent shadow-lg text-lg font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-xl">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
