@extends('layouts.app')

@section('title', 'Data Pesanan')

@section('content')

@if($orders->isEmpty())
<div class="mt-4">
    <p class="text-center text-gray-500">Belum ada pesanan.</p>
</div>
@else
@foreach($orders as $order)
<div class="bg-teal-200 p-4 rounded-lg shadow-lg w-full mt-4 mb-4 hover:bg-teal-300">
    <div class="flex justify-between items-center">
        <div class="flex-1 cursor-pointer" onclick="toggleDetails('{{ $order->id }}')">
            <h1 class="text-lg font-semibold">Pesanan: {{ $order->id }}</h1>
            <span class="text-sm">Klik untuk melihat detail</span>
        </div>
    </div>

    <div class="mt-4 hidden transition-all duration-300 ease-in-out" id="details-{{ $order->id }}">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="{{ basename (Auth::user()->name) }}" class="w-full p-2 border border-gray-300 rounded" readonly>
            </div>
            <div>
                <label class="block mb-2">Alamat</label>
                <input type="text" name="alamat" value="{{ basename (Auth::user()->alamat) }}" class="w-full p-2 border border-gray-300 rounded" readonly>
            </div>
            <div>
                <label class="block mb-2">Nomor Telepon/WA</label>
                <input type="text" name="telepon" value="{{ basename (Auth::user()->telepon) }}" class="w-full p-2 border border-gray-300 rounded" readonly>
            </div>
            <div>
                <label class="block mb-2">Total Pembayaran</label>
                <input value="{{basename($order->total_pembayaran) }}" class="w-full p-2 border border-gray-300 rounded" readonly>
            </div>
        </div>

        <!-- Bagian produk yang dibeli -->
        <div class="mt-4">
            <h2 class="text-lg font-semibold">Produk yang Dibeli:</h2>
            <div class="space-y-4 mt-4">
                @foreach($order->detailTransactions as $detail)
                <div class="bg-teal-600 p-4 text-white rounded-lg shadow-md">
                    <h3 class="font-bold text-lg">
                        <label class="block mb-2">Nama Produk : {{ basename ($detail->order->product->nama_produk) }}</label>
                    </h3>
                    <div class="grid gap-2">
                        <div>
                            <img
                                class="w-full h-48 object-cover rounded-xl cursor-pointer hover:opacity-80 transition-opacity mb-4"
                                src="{{ $detail->order->product->gambar ? asset('storage/' . $detail->order->product->gambar) : 'https://via.placeholder.com/300/CCCCCC/FFFFFF?text=Tidak+Ada+Gambar' }}"
                                alt="{{ $detail->order->product->nama_produk }}"
                                onclick="openImageModal('{{ $detail->order->product->gambar ? asset('storage/' . $detail->order->product->gambar) : 'https://via.placeholder.com/300/CCCCCC/FFFFFF?text=Tidak+Ada+Gambar' }}')">
                        </div>
                        <div>
                            <label class="block font-medium mb-2">Total Harga :</label>
                            <p class="border border-gray-300 rounded-lg p-2 w-full">Rp {{ number_format($detail->order->total_pembayaran, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="block font-medium mb-2">Jumlah Pesan :</label>
                            <p class="border border-gray-300 rounded-lg p-2 w-full">{{ basename($detail->order->kuantitas) }} Buah</p>
                        </div>
                        <div>
                            <label class="block font-medium mb-2">Dokumen Tambahan :</label>
                            @if($detail->dokumen_tambahan)
                            <p class="border border-gray-300 rounded-lg p-2 w-full">Nama file: {{ basename($detail->dokumen_tambahan) }}</p>
                            @else
                            <p>Belum Ada Dokumen Tambahan yang diUpload.</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <hr class="border-black my-4">
        <div class="flex flex-col sm:flex-row items-center justify-center mt-6 space-y-4 sm:space-y-0 sm:space-x-4">
            <button type="button" class="flex items-center justify-center gap-2 px-6 py-3 rounded-full shadow-md text-base font-semibold text-white
           bg-gradient-to-r from-blue-400 to-blue-500
           hover:from-blue-500 hover:to-blue-600 
           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400
           transition duration-300 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>{{ ucfirst($order->status) }}</span>
            </button>

            @if ($order->status === 'pending' && !$order->cancellation_requested)
            <form action="{{ route('orders.request-cancellation', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');">
                @csrf
                <button type="submit" class="flex items-center justify-center gap-2 px-6 py-3 rounded-full shadow-md text-base font-semibold text-white
                       bg-gradient-to-r from-red-500 to-red-600
                       hover:from-red-600 hover:to-red-700 hover:scale-105
                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500
                       transition duration-300 ease-in-out transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm6 0a1 1 0 11-2 0v6a1 1 0 112 0V8z" clip-rule="evenodd" />
                    </svg>
                    Batalkan Pesanan
                </button>
            </form>
            @elseif ($order->cancellation_requested)
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-yellow-100 text-yellow-800 text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.487 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg> Menunggu konfirmasi admin
                @endif
        </div>


    </div>

</div>
@endforeach
@endif

@endsection

@section('scripts')
<script>
    function toggleDetails(id) {
        const el = document.getElementById(`details-${id}`);
        if (el) el.classList.toggle('hidden');
    }
</script>
@endsection