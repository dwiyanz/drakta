@extends('layouts.app')

@section('content')
{{-- Kontainer utama dengan padding, margin atas, latar belakang cerah, sudut membulat, dan bayangan --}}
{{-- Menggunakan latar belakang putih bersih dengan border halus dan bayangan yang elegan --}}
<div class="bg-white p-8 mt-8 rounded-xl shadow-xl w-full max-w-lg mx-auto text-gray-800 border border-gray-100">
    {{-- Judul halaman --}}
    <h1 class="text-3xl font-bold mb-8 text-center text-gray-900">Edit Profil Anda</h1>

    {{-- Form untuk update profil --}}
    {{-- Action mengarah ke route update profil, method POST dengan spoofing PUT/PATCH --}}
    <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT') {{-- Menggunakan method PUT untuk update resource --}}

        {{-- Field Nama Lengkap (Editable) --}}
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap:</label>
            {{-- Input sekarang bisa diedit --}}
            <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}"
                   class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 p-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400" placeholder="Masukkan nama lengkap Anda">
            {{-- Menampilkan pesan error validasi jika ada --}}
            @error('name')
                <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Field Username (Editable) --}}
        <div>
            <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">Username:</label>
            {{-- Input sekarang bisa diedit --}}
            <input type="text" id="username" name="username" value="{{ old('username', Auth::user()->username) }}"
                   class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 p-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400" placeholder="Masukkan username Anda">
            {{-- Menampilkan pesan error validasi jika ada --}}
            @error('username')
                <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Field Telepon (Editable) --}}
        <div>
            <label for="telepon" class="block text-sm font-semibold text-gray-700 mb-1">Telepon:</label>
            {{-- Input editable dengan styling standar --}}
            <input type="text" id="telepon" name="telepon" value="{{ old('telepon', Auth::user()->telepon) }}"
                   class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 p-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400" placeholder="Masukkan nomor telepon Anda">
            {{-- Menampilkan pesan error validasi jika ada --}}
            @error('telepon')
                <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Field Alamat (Editable) --}}
        <div>
            <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-1">Alamat:</label>
            {{-- Textarea editable dengan styling standar --}}
            <textarea id="alamat" name="alamat" rows="3"
                      class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 p-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400" placeholder="Masukkan alamat lengkap Anda">{{ old('alamat', Auth::user()->alamat) }}</textarea>
            {{-- Menampilkan pesan error validasi jika ada --}}
            @error('alamat')
                <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Field Jenis Rekening (Editable) --}}
        <div>
            <label for="jenis_rekening" class="block text-sm font-semibold text-gray-700 mb-1">Jenis Rekening:</label>
            {{-- Input editable dengan styling standar --}}
            <input type="text" id="jenis_rekening" name="jenis_rekening" value="{{ old('jenis_rekening', Auth::user()->jenis_rekening) }}"
                   class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 p-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400" placeholder="Contoh: BCA, Mandiri, dll.">
            {{-- Menampilkan pesan error validasi jika ada --}}
            @error('jenis_rekening')
                <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Field No. Rekening (Editable) --}}
        <div>
            <label for="no_rekening" class="block text-sm font-semibold text-gray-700 mb-1">No. Rekening:</label>
            {{-- Input editable dengan styling standar --}}
            <input type="text" id="no_rekening" name="no_rekening" value="{{ old('no_rekening', Auth::user()->no_rekening) }}"
                   class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 p-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400" placeholder="Masukkan nomor rekening Anda">
            {{-- Menampilkan pesan error validasi jika ada --}}
            @error('no_rekening')
                <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tombol Submit --}}
        <div>
            <button type="submit"
                    class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 transform hover:scale-105">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
