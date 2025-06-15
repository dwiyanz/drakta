@extends('layouts.app')

@section('title', 'Halaman Beranda')

@section('content')
<section class="relative w-full mb-8"> <img src="{{ asset(optional($beranda)->gambar_utama ?: 'images/default.jpg') }}" class="w-full h-96 object-cover rounded-xl shadow-lg" alt="Main Banner">
    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent rounded-xl"></div>
    <div class="absolute bottom-8 right-8 text-white p-4">
        <h2 class="text-4xl font-bold drop-shadow-md">Selamat Datang di DR AKTA PERCETAKAN</h2>
        <p class="text-lg mt-2 drop-shadow-md">Solusi percetakan terpercaya untuk kebutuhan Anda.</p>
    </div>
</section>

<!-- Feature Section -->
<section class="p-8 mb-12 text-center bg-white rounded-xl shadow-xl border border-gray-100">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Layanan Yang Kami Berikan</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="flex flex-col items-center space-y-4 p-6 bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-1 border border-gray-100">
            <div class="bg-teal-100 p-4 rounded-full">
                <i class="fas fa-check-circle text-teal-600 text-4xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800">Kualitas Terjamin</h3>
            <span class="text-gray-600">{{ optional($beranda)->sec2_text1 }}</span>
        </div>
        <div class="flex flex-col items-center space-y-4 p-6 bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-1 border border-gray-100">
            <div class="bg-teal-100 p-4 rounded-full">
                <i class="fas fa-shipping-fast text-teal-600 text-4xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800">Pengerjaan Cepat</h3>
            <span class="text-gray-600">{{ optional($beranda)->sec2_text2 }}</span>
        </div>
        <div class="flex flex-col items-center space-y-4 p-6 bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-1 border border-gray-100">
            <div class="bg-teal-100 p-4 rounded-full">
                <i class="fas fa-thumbs-up text-teal-600 text-4xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800">Pelayanan Terbaik</h3>
            <span class="text-gray-600">{{ optional($beranda)->sec2_text3 }}</span>
        </div>
    </div>
    </section>
    <!-- Hero Section -->
    <section id="default-carousel" class="relative w-full mb-12 rounded-xl overflow-hidden shadow-xl" data-carousel="slide">
        <div class="relative h-56 md:h-96">
            <!-- Carousel wrapper -->
            <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
                <!-- Item 1 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                    <img
                        src="{{ asset(optional($beranda)->gambar_carousel1 ?: 'images/default.jpg') }}"
                        class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 cursor-pointer"
                        alt="Carousel 1"
                        onclick="redirectToProductPage('{{ optional($beranda)->link1_g1 }}')">
                </div>
                <!-- Item 2 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                    <img
                        src="{{ asset(optional($beranda)->gambar_carousel2 ?: 'images/default.jpg') }}"
                        class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 cursor-pointer"
                        alt="Carousel 2"
                        onclick="redirectToProductPage('{{ optional($beranda)->link1_g2 }}')">
                </div>
                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                    <img
                        src="{{ asset(optional($beranda)->gambar_carousel3 ?: 'images/default.jpg') }}"
                        class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 cursor-pointer"
                        alt="Carousel 2"
                        onclick="redirectToProductPage('{{ optional($beranda)->link1_g3 }}')">
                </div>
                <!-- Slider indicators -->
                <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
                    <button type="button" class="w-3 h-3 rounded-full" aria-current="true" aria-label="Slide 1" data-carousel-slide-to="0"></button>
                    <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 2" data-carousel-slide-to="1"></button>
                    <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 3" data-carousel-slide-to="2"></button>
                </div>
                <!-- Slider controls -->
                <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                        <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4" />
                        </svg>
                        <span class="sr-only">Previous</span>
                    </span>
                </button>
                <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                        <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                        </svg>
                        <span class="sr-only">Next</span>
                    </span>
                </button>
    </section>

    <!-- Footer Section -->
    <section class="py-16 px-4 sm:px-6 lg:px-8 bg-gray-50 font-inter">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-4xl lg:text-5xl font-extrabold text-center text-gray-900 mb-12 animate-fade-in-down">
            {{ optional($beranda)->sec3_judul }}
        </h2>

        {{-- Perubahan utama di sini: gradien warna dan efek hover --}}
        <div class="bg-white rounded-3xl p-8 md:p-12 shadow-2xl text-gray-800 transition-all duration-500 hover:shadow-gray-300/50">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-16 items-center">
                {{-- Kolom Fitur / Poin Utama --}}
                <div class="flex flex-col justify-center space-y-8">
                    {{-- Item Fitur 1 --}}
                    <div class="flex items-start space-x-5 transform transition-transform duration-300 hover:scale-105 hover:bg-purple-100 p-4 rounded-xl">
                        <div class="bg-gradient-to-br from-purple-500 to-pink-500 p-4 rounded-full shadow-lg flex-shrink-0">
                            {{-- Pastikan Font Awesome dimuat di layout utama --}}
                            <i class="fas fa-print text-white text-3xl"></i>
                        </div>
                        <p class="text-lg md:text-xl leading-relaxed font-normal">{{ optional($beranda)->sec3_text1 }}</p>
                    </div>

                    {{-- Item Fitur 2 --}}
                    <div class="flex items-start space-x-5 transform transition-transform duration-300 hover:scale-105 hover:bg-purple-100 p-4 rounded-xl">
                        <div class="bg-gradient-to-br from-purple-500 to-pink-500 p-4 rounded-full shadow-lg flex-shrink-0">
                            <i class="fas fa-clock text-white text-3xl"></i>
                        </div>
                        <p class="text-lg md:text-xl leading-relaxed font-normal">{{ optional($beranda)->sec3_text2 }}</p>
                    </div>

                    {{-- Item Fitur 3 --}}
                    <div class="flex items-start space-x-5 transform transition-transform duration-300 hover:scale-105 hover:bg-purple-100 p-4 rounded-xl">
                        <div class="bg-gradient-to-br from-purple-500 to-pink-500 p-4 rounded-full shadow-lg flex-shrink-0">
                            <i class="fas fa-paint-brush text-white text-3xl"></i>
                        </div>
                        <p class="text-lg md:text-xl leading-relaxed font-normal">{{ optional($beranda)->sec3_text3 }}</p>
                    </div>
                </div>

                {{-- Kolom Peta Lokasi --}}
                <div class="flex flex-col items-center text-center">
                    <h3 class="font-extrabold text-3xl mb-6 text-gray-800 tracking-wide">LOKASI INDUSTRI</h3>
                    <iframe
                        src="{{ optional($beranda)->sec3_map }}"
                        width="100%"
                        height="450"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        class="rounded-2xl shadow-2xl border-4 border-gray-200 transform transition-transform duration-300 hover:scale-[1.02] aspect-video"
                    ></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- <h2 class="text-4xl font-bold mt-8 mb-6 text-center text-gray-800">{{ optional($beranda)->sec3_judul }}</h2>
    <section class="p-10 mb-8 bg-teal-600 rounded-xl text-white shadow-xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="flex flex-col justify-center space-y-8 text-xl">
                <div class="flex items-start space-x-4">
                    <div class="bg-white p-3 rounded-full shadow-md">
                        <i class="fas fa-print text-teal-600 text-3xl"></i>
                    </div>
                    <p class="leading-relaxed">{{ optional($beranda)->sec3_text1 }}</p>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="bg-white p-3 rounded-full shadow-md">
                        <i class="fas fa-clock text-teal-600 text-3xl"></i>
                    </div>
                    <p class="leading-relaxed">{{ optional($beranda)->sec3_text2 }}</p>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="bg-white p-3 rounded-full shadow-md">
                        <i class="fas fa-paint-brush text-teal-600 text-3xl"></i>
                    </div>
                    <p class="leading-relaxed">{{ optional($beranda)->sec3_text3 }}</p>
                </div>
            </div>
            <div class="flex flex-col items-center text-center">
                <h3 class="font-bold text-2xl mb-4 text-white">LOKASI INDUSTRI</h3>
                <iframe src="{{ optional($beranda)->sec3_map }}" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="rounded-xl shadow-lg"></iframe>
            </div>
        </div>
    </section> -->
    @endsection


    @section('scripts')
    <script>
        function redirectToProductPage(productId) {
            // Pastikan productId valid
            if (!productId) {
                console.error('Product ID is required');
                return;
            }
            
            // Redirect ke halaman product dengan parameter highlight
            window.location.href = "{{ url('/product') }}?highlight=" + productId;
        }

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