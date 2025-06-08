@extends('layouts.app')

@section('content')
{{-- Kontainer utama dengan latar belakang terang, padding, sudut membulat, dan bayangan --}}
<div class="container mx-auto px-8 py-10 bg-gradient-to-r from-blue-100 via-teal-100 to-purple-100 p-8 rounded-2xl shadow-2xl mt-8 mb-8 max-w-3xl">
    {{-- Judul halaman --}}
    <h1 class="text-3xl font-bold mb-8 text-gray-800 text-center">Edit Profil Perusahaan</h1>

    {{-- Menampilkan pesan sukses jika ada --}}
    @if(session('success'))
        {{-- Menggunakan kelas Tailwind untuk styling alert sukses --}}
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
            {{-- Tombol close opsional (dapat diaktifkan dengan JS) --}}
            {{-- <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none'">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15L6.306 7.354a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.15 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </span> --}}
        </div>
    @endif

    {{-- Form untuk mengupdate profil perusahaan --}}
    <form action="{{ route('admin.profil-perusahaan.update') }}" method="POST" enctype="multipart/form-data">
        @csrf {{-- Token CSRF untuk keamanan --}}
        @method('PUT') {{-- Method spoofing untuk request PUT --}}

        {{-- Field input untuk Logo Perusahaan --}}
        <div class="mb-6">
            <label for="logo" class="block text-sm font-semibold text-gray-700 mb-2">Logo Perusahaan</label>
            {{-- Input file dengan styling modern --}}
            <input type="file" name="logo" id="logo" class="block w-full text-sm text-gray-900
                   file:mr-4 file:py-2 file:px-4
                   file:rounded-full file:border-0
                   file:text-sm file:font-semibold
                   file:bg-teal-50 file:text-teal-700
                   hover:file:bg-teal-100 cursor-pointer">
            {{-- Menampilkan logo yang sudah ada jika tersedia --}}
            @if($profil->logo)
                <img src="{{ asset($profil->logo) }}" alt="Logo Perusahaan" class="mt-4 rounded-md shadow-md" width="150">
            @endif
            {{-- Menampilkan pesan error validasi untuk logo --}}
            @error('logo')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Field input untuk Judul Section 1 --}}
        <div class="mb-6">
            <label for="judul_p1" class="block text-sm font-semibold text-gray-700 mb-2">Judul Section 1</label>
            <input type="text" name="judul_p1" id="judul_p1" class="block w-full border-gray-300 rounded-md shadow-sm p-3 focus:border-teal-500 focus:ring focus:ring-teal-500 focus:ring-opacity-50" value="{{ old('judul_p1', $profil->judul_p1) }}">
             {{-- Menampilkan pesan error validasi untuk judul_p1 --}}
            @error('judul_p1')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Field textarea untuk Isi Section 1 --}}
        <div class="mb-6">
            <label for="isi_p1" class="block text-sm font-semibold text-gray-700 mb-2">Isi Section 1</label>
            <textarea name="isi_p1" id="isi_p1" class="block w-full border-gray-300 rounded-md shadow-sm p-3 focus:border-teal-500 focus:ring focus:ring-teal-500 focus:ring-opacity-50" rows="4">{{ old('isi_p1', $profil->isi_p1) }}</textarea>
             {{-- Menampilkan pesan error validasi untuk isi_p1 --}}
            @error('isi_p1')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Field input untuk Visi --}}
        <div class="mb-6">
            <label for="visi" class="block text-sm font-semibold text-gray-700 mb-2">Visi</label>
            <input type="text" name="visi" id="visi" class="block w-full border-gray-300 rounded-md shadow-sm p-3 focus:border-teal-500 focus:ring focus:ring-teal-500 focus:ring-opacity-50" value="{{ old('visi', $profil->visi) }}">
             {{-- Menampilkan pesan error validasi untuk visi --}}
            @error('visi')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Field textarea untuk Isi Visi --}}
        <div class="mb-6">
            <label for="isi_visi" class="block text-sm font-semibold text-gray-700 mb-2">Isi Visi</label>
            <textarea name="isi_visi" id="isi_visi" class="block w-full border-gray-300 rounded-md shadow-sm p-3 focus:border-teal-500 focus:ring focus:ring-teal-500 focus:ring-opacity-50" rows="4">{{ old('isi_visi', $profil->isi_visi) }}</textarea>
             {{-- Menampilkan pesan error validasi untuk isi_visi --}}
            @error('isi_visi')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Field input untuk Misi --}}
        <div class="mb-6">
            <label for="misi" class="block text-sm font-semibold text-gray-700 mb-2">Misi</label>
            <input type="text" name="misi" id="misi" class="block w-full border-gray-300 rounded-md shadow-sm p-3 focus:border-teal-500 focus:ring focus:ring-teal-500 focus:ring-opacity-50" value="{{ old('misi', $profil->misi) }}">
             {{-- Menampilkan pesan error validasi untuk misi --}}
            @error('misi')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Field textarea untuk Isi Misi --}}
        <div class="mb-6">
            <label for="isi_misi" class="block text-sm font-semibold text-gray-700 mb-2">Isi Misi</label>
            <textarea name="isi_misi" id="isi_misi" class="block w-full border-gray-300 rounded-md shadow-sm p-3 focus:border-teal-500 focus:ring focus:ring-teal-500 focus:ring-opacity-50" rows="6" placeholder="Masukkan setiap poin misi di baris baru">{{ old('isi_misi', $profil->isi_misi) }}</textarea>
             {{-- Menampilkan pesan error validasi untuk isi_misi --}}
            @error('isi_misi')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Field input untuk Kontak --}}
        <div class="mb-6">
            <label for="kontak" class="block text-sm font-semibold text-gray-700 mb-2">Kontak</label>
            <input type="text" name="kontak" id="kontak" class="block w-full border-gray-300 rounded-md shadow-sm p-3 focus:border-teal-500 focus:ring focus:ring-teal-500 focus:ring-opacity-50" value="{{ old('kontak', $profil->kontak) }}">
             {{-- Menampilkan pesan error validasi untuk kontak --}}
            @error('kontak')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Field textarea untuk Isi Kontak --}}
        <div class="mb-6">
            <label for="isi_kontak" class="block text-sm font-semibold text-gray-700 mb-2">Isi Kontak</label>
            <textarea name="isi_kontak" id="isi_kontak" class="block w-full border-gray-300 rounded-md shadow-sm p-3 focus:border-teal-500 focus:ring focus:ring-teal-500 focus:ring-opacity-50" rows="4" placeholder="Nomor Telepon&#10;Email">{{ old('isi_kontak', $profil->isi_kontak) }}</textarea>
             {{-- Menampilkan pesan error validasi untuk isi_kontak --}}
            @error('isi_kontak')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol Simpan Perubahan dengan gaya modern --}}
        <div class="flex justify-center">
            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-green-400 to-teal-500 hover:from-green-500 hover:to-teal-600 text-white font-semibold rounded-lg shadow-md transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
