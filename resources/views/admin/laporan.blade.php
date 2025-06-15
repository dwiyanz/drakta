@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 py-8">
    {{-- Main container with bright background, rounded corners, and shadow --}}
    <div class="bg-gray p-8 rounded-xl shadow-lg mb-8">

        {{-- Report Title --}}
        <h1 class="text-center text-3xl font-extrabold text-gray-800 mb-6 border-b border-gray-200">Laporan Penjualan</h1>

        {{-- Print Button Section --}}
        <div class="mb-6 text-right">
            @php
                $query = http_build_query(request()->only(['start_date', 'end_date', 'status', 'transaction_id']));
            @endphp

            {{-- Print button with modern styling --}}
            <a
                href="{{ route('admin.laporan.print') . ($query ? '?' . $query : '') }}"
                target="_blank"
                class="inline-block bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-semibold py-2 px-6 rounded-full shadow-md transition duration-300 ease-in-out no-print">
                Cetak Laporan
            </a>
        </div>

        {{-- Filter Form Section --}}
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Filter Laporan</h2>
            <form action="{{ route('transaction.search.admin') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {{-- Date Start Input --}}
                <div>
                    <label for="startDate" class="block text-gray-700 font-medium mb-2">Tanggal Mulai</label>
                    <input type="date" id="startDate" name="start_date" value="{{ request('start_date') }}" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                {{-- Date End Input --}}
                <div>
                    <label for="endDate" class="block text-gray-700 font-medium mb-2">Tanggal Selesai</label>
                    <input type="date" id="endDate" name="end_date" value="{{ request('end_date') }}" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                {{-- Status Select --}}
                <div>
                    <label for="status" class="block text-gray-700 font-medium mb-2">Status</label>
                    <select id="status" name="status" class="form-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">-- Pilih Status --</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="process" {{ request('status') == 'process' ? 'selected' : '' }}>Proses</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="return" {{ request('status') == 'return' ? 'selected' : '' }}>Return</option>
                    </select>
                </div>
                {{-- Transaction ID Search Input --}}
                <div class="md:col-span-2">
                    <label for="transactionId" class="block text-gray-700 font-medium mb-2">Cari ID Transaksi</label>
                    <input type="text" id="transactionId" name="transaction_id" value="{{ request('transaction_id') }}" placeholder="Masukkan ID Transaksi..." class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                {{-- Search Button --}}
                <div class="flex items-end">
                    <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-green-400 to-teal-500 hover:from-green-500 hover:to-teal-600 text-white font-semibold py-2 px-6 rounded-md shadow-md transition duration-300 ease-in-out">
                        Cari
                    </button>
                </div>
            </form>
        </div>

        {{-- Report Table Section --}}
        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-green-200">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">ID Transaksi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">ID Produk</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">ID Pelanggan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">ID Pesanan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Jumlah Barang</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Tanggal Transaksi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Total Bayar</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Status Pembayaran</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Status Pesanan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if ($transactions->isEmpty())
                        <tr>
                            <td colspan="9" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">Tidak ada data ditemukan</td>
                        </tr>
                    @else
                        @foreach ($transactions as $transaction)
                            <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $transaction->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @foreach($transaction->detailTransactions as $detail)
                                        <p>{{ $detail->order->id_produk }} </p>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->id_user }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @foreach($transaction->detailTransactions as $detail)
                                        <p>{{ $detail->order->id }} </p>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    @foreach($transaction->detailTransactions as $detail)
                                        <p>{{ $detail->order->kuantitas }} </p>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($transaction->total_pembayaran, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">LUNAS</td> {{-- Assuming status is always LUNAS based on original code --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->status }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Total Revenue Section --}}
        @if (!$transactions->isEmpty())
            <div class="mt-6 text-right text-xl font-bold text-gray-800">
                Total Pendapatan: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </div>
        @endif
    </div>
</div>

@endsection
