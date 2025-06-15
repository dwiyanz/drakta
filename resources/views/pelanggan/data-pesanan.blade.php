@extends('layouts.app')

@section('title', 'Data Pesanan')

@section('content')
<div style="font-family: 'Inter', sans-serif; background-color: #f8fafc; padding: 2rem;">
    <h1 style="font-size: 2.25rem; font-weight: 700; color: #1f2937; text-align: center; margin-bottom: 2.5rem;">Data Pesanan Anda</h1>

    @if($orders->isEmpty())
    <div style="text-align: center; padding: 1.5rem; background-color: #f0f4f8; border-radius: 0.75rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
        <p style="color: #6b7280; font-size: 1.125rem;">Belum ada pesanan yang tersedia.</p>
    </div>
    @else
    @foreach($orders as $order)
    <div style="background-color: #ffffff; padding: 1.75rem; border-radius: 1rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); margin-bottom: 1.5rem; border: 1px solid #e2e8f0; transition: all 0.3s ease-in-out;"
        onmouseover="this.style.transform='translateY(-5px)';"
        onmouseout="this.style.transform='translateY(0)';">
        <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; padding-bottom: 1rem; border-bottom: 1px solid #f3f4f6;"
            onclick="toggleDetails('{{ $order->id }}')">
            <div style="flex-grow: 1;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: #334155; margin-bottom: 0.25rem;">Pesanan #{{ $order->id }}</h2>
                <span style="font-size: 0.875rem; color: #64748b;">Klik untuk melihat detail pesanan</span>
            </div>
            <svg id="arrow-{{ $order->id }}" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>

        <div id="details-{{ $order->id }}" style="margin-top: 1.5rem; display: none; overflow: hidden; transition: max-height 0.5s ease-in-out;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569; font-size: 0.875rem;">Nama Lengkap</label>
                    <input type="text" value="{{ basename (Auth::user()->name) }}" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; background-color: #f8fafc; color: #334155; font-size: 1rem;" readonly>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569; font-size: 0.875rem;">Alamat</label>
                    <input type="text" value="{{ basename (Auth::user()->alamat) }}" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; background-color: #f8fafc; color: #334155; font-size: 1rem;" readonly>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569; font-size: 0.875rem;">Nomor Telepon/WA</label>
                    <input type="text" value="{{ basename (Auth::user()->telepon) }}" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; background-color: #f8fafc; color: #334155; font-size: 1rem;" readonly>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569; font-size: 0.875rem;">Total Pembayaran</label>
                    <input value="Rp {{ number_format($order->total_pembayaran, 0, ',', '.') }}" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; background-color: #f8fafc; color: #334155; font-size: 1rem;" readonly>
                </div>
            </div>

            <div style="margin-top: 2rem;">
                <h3 style="font-size: 1.25rem; font-weight: 600; color: #334155; margin-bottom: 1rem;">Produk yang Dibeli:</h3>
                <div style="display: grid; gap: 1.5rem;">
                    @foreach($order->detailTransactions as $detail)
                    <div style="background-color: #f8fafc; padding: 1.25rem; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0;">
                        <h4 style="font-weight: 700; font-size: 1.125rem; color: #2c3e50; margin-bottom: 1rem;">
                            Nama Produk: {{ $detail->order->product->nama_produk ?? 'Produk Tidak Ditemukan' }}
                        </h4>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; align-items: start;">
                            <div style="width: 100%; max-width: 200px; height: 250px; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); position: relative; cursor: pointer; flex-shrink: 0;"
                                onclick="openImageModal('{{ $detail->order->product->gambar ? asset('storage/' . $detail->order->product->gambar) : 'https://via.placeholder.com/300' }}')">
                                <img src="{{ $detail->order->product->gambar ? asset('storage/' . $detail->order->product->gambar) : 'https://via.placeholder.com/300' }}"
                                    alt="{{ $detail->order->product->nama_produk }}"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                <div>
                                    <label style="display: block; font-weight: 600; margin-bottom: 0.25rem; color: #475569; font-size: 0.875rem;">Total Harga :</label>
                                    <p style="border: 1px solid #cbd5e1; border-radius: 0.5rem; padding: 0.75rem; background-color: #f8fafc; color: #334155; font-size: 1rem;">Rp {{ number_format($detail->order->total_pembayaran, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <label style="display: block; font-weight: 600; margin-bottom: 0.25rem; color: #475569; font-size: 0.875rem;">Jumlah Pesan :</label>
                                    <p style="border: 1px solid #cbd5e1; border-radius: 0.5rem; padding: 0.75rem; background-color: #f8fafc; color: #334155; font-size: 1rem;">{{ $detail->order->kuantitas }} Buah</p>
                                </div>
                                <div>
                                    <label style="display: block; font-weight: 600; margin-bottom: 0.25rem; color: #475569; font-size: 0.875rem;">Dokumen Tambahan :</label>
                                    @if($detail->dokumen_tambahan)
                                    <a href="{{ asset('storage/' . $detail->dokumen_tambahan) }}" target="_blank" style="border: 1px solid #cbd5e1; border-radius: 0.5rem; padding: 0.75rem; background-color: #f8fafc; color: #334155; font-size: 1rem; text-decoration: none; display: inline-block;">Nama file: {{ basename($detail->dokumen_tambahan) }}</a>
                                    @else
                                    <p style="color: #6b7280; font-size: 0.9rem;">Belum Ada Dokumen Tambahan yang diUpload.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <hr style="border-top: 1px solid #e2e8f0; margin: 2rem 0;">
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; margin-top: 1.5rem; gap: 1rem;">
                <button type="button" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.75rem 1.5rem; border-radius: 9999px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); font-size: 1rem; font-weight: 600; color: #ffffff;
                       background: linear-gradient(to right, #3b82f6, #2563eb); border: none; cursor: default; transition: background 0.3s ease-in-out;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ ucfirst($order->status) }}</span>
                </button>

                @if ($order->status === 'pending' && !$order->cancellation_requested)
                <form action="{{ route('orders.request-cancellation', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');">
                    @csrf
                    <button type="submit" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.75rem 1.5rem; border-radius: 9999px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); font-size: 1rem; font-weight: 600; color: #ffffff;
                           background: linear-gradient(to right, #ef4444, #dc2626); border: none; cursor: pointer; transition: all 0.3s ease-in-out;"
                        onmouseover="this.style.background='linear-gradient(to right, #dc2626, #ef4444)'; this.style.transform='scale(1.05)';"
                        onmouseout="this.style.background='linear-gradient(to right, #ef4444, #dc2626)'; this.style.transform='scale(1)';">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm6 0a1 1 0 11-2 0v6a1 1 0 112 0V8z" clip-rule="evenodd" />
                        </svg>
                        Batalkan Pesanan
                    </button>
                </form>
                @elseif ($order->cancellation_requested)
                <span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.2rem; border-radius: 9999px; background-color: #fefce8; color: #a16207; font-size: 0.875rem; font-weight: 500; border: 1px solid #fde047;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.487 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    Menunggu konfirmasi admin
                </span>
                @endif
            </div>
        </div>
    </div>
    @endforeach
    @endif

    <!-- Modal pop-up gambar -->
    <div id="imageModal" style="position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.85); display: none; align-items: center; justify-content: center; z-index: 9999; backdrop-filter: blur(5px);">
        <div style="position: relative; max-width: 900px; width: 95%; max-height: 90vh; margin: 0 1rem;">
            <!-- Tombol close -->
            <button onclick="closeImageModal()" style="position: absolute; top: -1.5rem; right: -1.5rem; background-color: white; color: #1f2937; border-radius: 9999px; padding: 0.5rem 0.75rem; font-size: 1.25rem; font-weight: 700; border: none; cursor: pointer; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); transition: all 0.2s ease-in-out;"
                onmouseover="this.style.backgroundColor='#ef4444'; this.style.color='white';"
                onmouseout="this.style.backgroundColor='white'; this.style.color='#1f2937';">
                ✕
            </button>
            <!-- Gambar full -->
            <img id="modalImage" src="" style="width: 100%; height: auto; max-height: 80vh; object-fit: contain; border-radius: 0.75rem; box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);">
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function toggleDetails(id) {
        const el = document.getElementById(`details-${id}`);
        const arrow = document.getElementById(`arrow-${id}`);
        if (el) {
            if (el.style.display === 'none' || el.style.display === '') {
                el.style.display = 'block';
                el.style.maxHeight = el.scrollHeight + 'px';
                arrow.style.transform = 'rotate(180deg)';
            } else {
                el.style.maxHeight = '0';
                arrow.style.transform = 'rotate(0deg)';
                el.addEventListener('transitionend', function handler() {
                    el.style.display = 'none';
                    el.removeEventListener('transitionend', handler);
                }, {
                    once: true
                });
            }
        }
    }

    function openImageModal(src) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        modalImage.src = src;
        modal.style.display = 'flex';
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.style.display = 'none';
        document.getElementById('modalImage').src = '';
    }
</script>
@endsection