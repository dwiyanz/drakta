@extends('layouts.app')

@section('title', 'Profil Perusahaan')

@section('content')
<div class="font-inter bg-gray-50 min-h-screen">
  {{-- Bagian Konten Utama --}}
  <div class="max-w-6xl mx-auto py-8 px-6 lg:px-8">
    {{-- Bagian Hero dengan Gambar Latar Belakang Percetakan --}}
    <div
      class="relative bg-cover bg-center min-h-[400px] flex items-center justify-center text-center p-8 rounded-3xl overflow-hidden shadow-2xl"
      style="background-image: url('{{ asset('images/coverperusahaan.jpg') }}')"
    >
      {{-- Overlay untuk keterbacaan teks yang lebih baik --}}
      <div class="absolute inset-0 bg-black bg-opacity-60"></div>
      <div class="relative z-10">
        {{-- Logo Perusahaan --}}
        <img
          alt="Logo DR AKTA PERCETAKAN"
          class="mx-auto h-32 w-auto rounded-3xl shadow-xl transition-transform duration-300 hover:scale-105"
          src="{{ asset('images/logo.png') }}"
        />
        {{-- Nama Perusahaan --}}
        <h1 class="text-5xl font-extrabold text-white mt-6 tracking-tight drop-shadow-lg leading-tight md:text-6xl lg:text-7xl">
          DR AKTA PERCETAKAN
        </h1>
        <h1 class="text-5xl font-extrabold text-white mt-6 tracking-tight drop-shadow-lg leading-tight md:text-6xl lg:text-7xl">
          CV. UTAMI JAYA
        </h1>
        {{-- Slogan --}}
        <p class="text-xl text-blue-100 mt-4 drop-shadow-md font-medium md:text-2xl lg:text-3xl">
          Mencetak Kualitas, Membangun Relasi Sejak Dulu.
        </p>
      </div>
    </div>

    {{-- Grid Bagian untuk Pendahuluan, Visi, Misi, dan Kontak --}}
    <div class="grid grid-cols-1 gap-8 mt-10">
      {{-- Bagian 1: Pendahuluan --}}
      <div class="bg-white rounded-2xl p-8 shadow-xl transition-transform duration-300 hover:-translate-y-2">
        <h2 class="text-3xl font-bold text-gray-800 mb-5 border-b-2 border-gray-200 pb-3">
          {{ $profil->judul_p1 }}
        </h2>
        <p class="text-gray-700 leading-relaxed text-lg">
          {{ $profil->isi_p1 }}
        </p>
      </div>

      {{-- Bagian Visi & Misi - Dua kolom di layar sedang dan lebih besar --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- Kartu Visi --}}
        <div class="bg-white rounded-2xl p-8 shadow-xl transition-transform duration-300 hover:-translate-y-2">
          <h2 class="text-3xl font-bold text-gray-800 mb-5 border-b-2 border-gray-200 pb-3">
            {{ $profil->visi }}
          </h2>
          <p class="text-gray-700 leading-relaxed whitespace-pre-line text-lg">
            {!! $profil->isi_visi !!}
          </p>
        </div>
        {{-- Kartu Misi --}}
        <div class="bg-white rounded-2xl p-8 shadow-xl transition-transform duration-300 hover:-translate-y-2">
          <h2 class="text-3xl font-bold text-gray-800 mb-5 border-b-2 border-gray-200 pb-3">
            {{ $profil->misi }}
          </h2>
          {{-- Menggunakan div dan whitespace-pre-line untuk menghormati baris baru asli --}}
          <div class="text-gray-700 leading-relaxed whitespace-pre-line text-lg">
            {!! $profil->isi_misi !!}
          </div>
        </div>
      </div>

      {{-- Bagian Kontak --}}
      <div class="bg-white rounded-2xl p-8 shadow-xl transition-transform duration-300 hover:-translate-y-2">
        <h2 class="text-3xl font-bold text-gray-800 mb-5 border-b-2 border-gray-200 pb-3">
          {{ $profil->kontak }}
        </h2>
        {{-- Menggunakan div dan whitespace-pre-line untuk menghormati baris baru asli --}}
        <div class="text-gray-700 leading-relaxed whitespace-pre-line text-lg">
          {!! $profil->isi_kontak !!}
        </div>
      </div>
    </div>
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
</script>
@endsection
