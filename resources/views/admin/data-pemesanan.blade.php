@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 p-8 bg-white rounded-xl shadow-2xl">
    <h1 class="text-center text-3xl font-extrabold text-gray-800 mb-8 drop-shadow-sm">DATA PEMESANAN</h1>

    {{-- Success/Error Messages --}}
    <div id="flash-messages" class="mb-4">
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif
    </div>

    {{-- Controls Section --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
        <div class="flex items-center">
            <label for="show-entries" class="mr-2 text-gray-700 font-medium">Show</label>
            <input
                type="number"
                id="show-entries"
                min="1"
                value="{{ request('perPage', 10) }}"
                class="p-2 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 w-20 text-center"
                onchange="changeEntries()">
            <span class="ml-2 text-gray-700 font-medium">entries</span>
        </div>

        {{-- Search --}}
        <form action="{{ route('admin.data-pemesanan.index') }}" method="GET" class="flex items-center space-x-2">
            <input
                type="text"
                name="search"
                placeholder="Cari ID Transaksi..."
                value="{{ request('search') }}"
                class="p-2 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Search
            </button>
        </form>
    </div>

    {{-- Transactions Table --}}
    <form action="{{ route('transaction.update-status') }}" method="POST">
        @csrf
        <div class="overflow-x-auto shadow-lg rounded-lg">
            <table class="min-w-full bg-white border-collapse">
                <thead>
                    <tr class="bg-gray-200 text-gray-700 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left border-b">ID Transaksi</th>
                        <th class="py-3 px-6 text-left border-b">ID Pelanggan</th>
                        <th class="py-3 px-6 text-left border-b">ID Produk</th>
                        <th class="py-3 px-6 text-left border-b">Quantity</th>
                        <th class="py-3 px-6 text-left border-b">Total Pembayaran</th>
                        <th class="py-3 px-6 text-left border-b">Status</th>
                        <th class="py-3 px-6 text-left border-b">Dokumen Tambahan</th>
                        <th class="py-3 px-6 text-left border-b">Pembatalan</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @forelse ($transactions as $transaction)
                    <tr class="border-b hover:bg-gray-100 transition duration-200" id="transaction-row-{{ $transaction->id }}">
                        <td class="py-3 px-6">{{ $transaction->id }}</td>
                        <td class="py-3 px-6">{{ $transaction->id_user }}</td>
                        <td class="py-3 px-6">
                            @foreach($transaction->detailTransactions as $detail)
                            <p>{{ $detail->order->id_produk }}</p>
                            @endforeach
                        </td>
                        <td class="py-3 px-6">
                            @foreach($transaction->detailTransactions as $detail)
                            <p>{{ $detail->order->kuantitas }}</p>
                            @endforeach
                        </td>
                        <td class="py-3 px-6">Rp. {{ number_format($transaction->total_pembayaran, 0, ',', '.') }}</td>
                        <td class="py-3 px-6">
                            <select name="status[{{ $transaction->id }}]" class="w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500">
                                <option value="pending" {{ $transaction->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="process" {{ $transaction->status === 'process' ? 'selected' : '' }}>Proses</option>
                                <option value="completed" {{ $transaction->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="return" {{ $transaction->status === 'return' ? 'selected' : '' }}>Return</option>
                            </select>
                        </td>
                        <td class="py-3 px-6">
                            <div class="flex flex-col gap-2">
                                @foreach($transaction->detailTransactions as $detail)
                                @if($detail->dokumen_tambahan)
                                <a href="{{ Storage::url($detail->dokumen_tambahan) }}" target="_blank" class="bg-green-600 hover:bg-green-700 text-white text-sm text-center px-4 py-2 rounded-md shadow-md">
                                    Lihat Dokumen
                                </a>
                                @else
                                <span class="text-gray-500 text-sm">Tidak ada dokumen</span>
                                @endif
                                @endforeach
                            </div>
                        </td>
                        <td class="py-3 px-6">
                            @if ($transaction->cancellation_requested)
                            <button
                                type="button"
                                onclick="approveCancellation('{{ $transaction->id }}')"
                                class="approve-btn
           relative flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg shadow-md text-base font-semibold text-white
           bg-gradient-to-r from-red-600 to-red-700
           hover:from-red-700 hover:to-red-800
           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500
           transition-all duration-300 ease-in-out
           disabled:opacity-60 disabled:cursor-not-allowed"
                                id="approve-btn-{{ $transaction->id }}"
                                data-transaction-id="{{ $transaction->id }}">
                                <span class="btn-text flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Setujui Pembatalan
                                </span>

                                <span class="loading-text absolute inset-0 flex items-center justify-center bg-gradient-to-r from-red-700 to-red-800 rounded-lg hidden">
                                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Memproses...
                                </span>
                            </button>
                            @else
                            <span class="text-gray-500">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-gray-500 py-4">Tidak ada data pemesanan ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $transactions->appends(request()->except('page'))->links() }}
        </div>

        {{-- Save Button --}}
        <div class="flex justify-end mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md shadow-md focus:ring-2 focus:ring-blue-500">
                Simpan
            </button>
        </div>
    </form>
</div>

{{-- CSRF Token for AJAX --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    function changeEntries() {
        const perPage = document.getElementById('show-entries').value;
        if (parseInt(perPage) > 0) {
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('perPage', perPage);
            urlParams.delete('page');
            window.location.search = urlParams.toString();
        } else {
            alert("Jumlah entry harus lebih dari 0.");
        }
    }

    function approveCancellation(transactionId) {
        console.log('approveCancellation called with ID:', transactionId);

        // Validasi transactionId
        if (!transactionId) {
            alert('ID transaksi tidak valid');
            return;
        }

        // Konfirmasi dari user
        if (!confirm('Setujui pembatalan ini dan kembalikan dana ke saldo pengguna?')) {
            return;
        }

        const button = document.getElementById(`approve-btn-${transactionId}`);
        if (!button) {
            console.error('Button not found for transaction ID:', transactionId);
            return;
        }

        const btnText = button.querySelector('.btn-text');
        const loadingText = button.querySelector('.loading-text');

        // Show loading state
        if (btnText && loadingText) {
            btnText.classList.add('hidden');
            loadingText.classList.remove('hidden');
        }
        button.disabled = true;

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token not found');
            showFlashMessage('error', 'CSRF token tidak ditemukan');
            resetButtonState(button, btnText, loadingText);
            return;
        }

        // Perform AJAX request
        fetch(`/approve-cancellation/${transactionId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                },
            })
            .then(response => {
                console.log('Response status:', response.status);

                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);

                if (data.success) {
                    // Show success message
                    showFlashMessage('success', data.message);

                    // Update the UI
                    updateTransactionRow(transactionId, data.transaction);
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan saat memproses pembatalan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showFlashMessage('error', error.message || 'Terjadi kesalahan saat memproses pembatalan');

                // Reset button state on error
                resetButtonState(button, btnText, loadingText);
            });
    }

    function resetButtonState(button, btnText, loadingText) {
        if (btnText && loadingText) {
            btnText.classList.remove('hidden');
            loadingText.classList.add('hidden');
        }
        button.disabled = false;
    }

    function showFlashMessage(type, message) {
        const flashContainer = document.getElementById('flash-messages');
        const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';

        // Remove existing messages
        flashContainer.innerHTML = '';

        // Add new message
        const messageDiv = document.createElement('div');
        messageDiv.className = `${alertClass} px-4 py-3 rounded relative mb-4 transition-opacity duration-500`;
        messageDiv.setAttribute('role', 'alert');
        messageDiv.innerHTML = `
            <span class="block sm:inline">${message}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.remove()">
                <svg class="fill-current h-6 w-6" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </span>
        `;

        flashContainer.appendChild(messageDiv);

        // Auto hide after 5 seconds
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.style.opacity = '0';
                setTimeout(() => messageDiv.remove(), 500);
            }
        }, 5000);
    }

    function updateTransactionRow(transactionId, transactionData) {
        const row = document.getElementById(`transaction-row-${transactionId}`);
        if (row) {
            // Update status dropdown
            const statusSelect = row.querySelector(`select[name="status[${transactionId}]"]`);
            if (statusSelect) {
                statusSelect.value = transactionData.status;
                // Optionally disable the select since it's already processed
                statusSelect.disabled = true;
                statusSelect.classList.add('bg-gray-100');
            }

            // Update cancellation button column
            const cancellationCell = row.querySelector('td:last-child');
            if (cancellationCell) {
                cancellationCell.innerHTML = '<span class="text-green-600 font-medium">Disetujui</span>';
            }

            // Add visual feedback - highlight the row briefly
            row.classList.add('bg-green-50');
            setTimeout(() => {
                row.classList.remove('bg-green-50');
                row.classList.add('transition-colors', 'duration-1000');
            }, 2000);
        }
    }
</script>
@endsection