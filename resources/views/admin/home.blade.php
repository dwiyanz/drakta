@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 py-8">
    {{-- Main Grid container for the stats cards --}}
    {{-- Uses a 2-column grid for all screen sizes to create 2 rows --}}
    <div class="grid grid-cols-2 gap-6">

        {{-- Card for Jumlah Pelanggan --}}
        {{-- Added flex and justify-center to center content if needed, though text-center is key for the number --}}
        {{-- Changed background to a bright blue-purple gradient and adjusted padding --}}
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-10 rounded-xl shadow-lg transition-all duration-300 ease-in-out transform hover:-translate-y-2 hover:shadow-xl border-l-4 border-blue-800 cursor-pointer flex flex-col justify-center">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    {{-- Icon color adjusted for better contrast on gradient --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-blue-200">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.125h15.002c.398 0 .779-.185 1.05-4.583A9.524 9.524 0 0016.5 16.5H7.5c0-3.042-.296-5.749-.651-8.583-3.251.23-6.759.691-9.52 2.076-2.128 1.66-3.534 4.081-3.534 6.583 0 4.416 3.584 8 8 8 0 .668-.075 1.305-.223 1.908-.089.355-.23.692-.412 1.001h0" />
                    </svg>
                    {{-- Text color adjusted for better contrast on gradient --}}
                    <span class="text-md font-semibold text-gray-100">Jumlah Pelanggan</span>
                </div>
            </div>
            {{-- Centering the number text and adjusting color --}}
            <div class="text-5xl font-extrabold text-white mb-2 text-center">{{ $jumlahPelanggan }}</div>
        </div>

        {{-- Card for Transaksi Pending --}}
        {{-- Changed background to a bright yellow-orange gradient and adjusted padding --}}
        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 px-6 py-10 rounded-xl shadow-lg transition-all duration-300 ease-in-out transform hover:-translate-y-2 hover:shadow-xl border-l-4 border-yellow-700 cursor-pointer flex flex-col justify-center">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    {{-- Icon color adjusted for better contrast on gradient --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-yellow-900">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{-- Text color adjusted for better contrast on gradient --}}
                    <span class="text-md font-semibold text-gray-800">Transaksi Pending</span>
                </div>
            </div>
            {{-- Centering the number text and adjusting color --}}
            <div class="text-5xl font-extrabold text-white mb-2 text-center">{{ $statusPending }}</div>
        </div>

        {{-- Card for Transaksi Process --}}
        {{-- Changed background to a bright purple-pink gradient and adjusted padding --}}
        <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-10 rounded-xl shadow-lg transition-all duration-300 ease-in-out transform hover:-translate-y-2 hover:shadow-xl border-l-4 border-purple-800 cursor-pointer flex flex-col justify-center">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    {{-- Icon color adjusted for better contrast on gradient --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-purple-200">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.185 3.185A1.65 1.65 0 0012.493 21l-4.152-4.152m4.152 4.152L21 12.493c-.476-.113-.963-.228-1.45-.345m-1.059-6.355L18.111 3.273a1.65 1.65 0 00-2.333-2.333L2.985 15.313m14.235-5.83L21 2.988" />
                    </svg>
                    {{-- Text color adjusted for better contrast on gradient --}}
                    <span class="text-md font-semibold text-gray-100">Transaksi Process</span>
                </div>
            </div>
            {{-- Centering the number text and adjusting color --}}
            <div class="text-5xl font-extrabold text-white mb-2 text-center">{{ $statusProses }}</div>
        </div>

        {{-- Card for Transaksi Selesai --}}
        {{-- Changed background to a bright green-teal gradient and adjusted padding --}}
        <div class="bg-gradient-to-r from-green-400 to-teal-500 px-6 py-10 rounded-xl shadow-lg transition-all duration-300 ease-in-out transform hover:-translate-y-2 hover:shadow-xl border-l-4 border-green-700 cursor-pointer flex flex-col justify-center">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    {{-- Icon color adjusted for better contrast on gradient --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-green-900">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{-- Text color adjusted for better contrast on gradient --}}
                    <span class="text-md font-semibold text-gray-800">Transaksi Selesai</span>
                </div>
            </div>
            {{-- Centering the number text and adjusting color --}}
            <div class="text-5xl font-extrabold text-white mb-2 text-center">{{ $statusSelesai }}</div>
        </div>

    </div>
</div>

@endsection
