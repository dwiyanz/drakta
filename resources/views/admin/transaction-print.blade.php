<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title> {{-- Menambahkan judul --}}

    {{-- Memuat Tailwind CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Gaya khusus untuk cetak */
        @media print {
            /* Set halaman cetak menjadi portrait dengan margin kecil */
            @page {
                size: A4 portrait;
                margin: 15mm;  /* Mengurangi margin agar data muat dengan baik */
            }

            /* Pastikan body tidak ada margin dan font size sesuai untuk cetak */
            body {
                margin: 0;
                padding: 0;
                font-size: 10pt; /* Ukuran font yang lebih standar untuk cetak */
                color: #000; /* Pastikan teks hitam saat dicetak */
                background-color: #fff; /* Pastikan latar belakang putih saat dicetak */
            }

            /* Menyesuaikan ukuran tabel dan border untuk cetak */
            table {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
                margin-bottom: 20px; /* Jarak bawah tabel saat cetak */
            }

            th, td {
                padding: 8px; /* Padding cell lebih baik */
                text-align: left;
                border: 1px solid #ccc; /* Border abu-abu terang */
                word-wrap: break-word;
                font-size: 10pt; /* Ukuran font cell */
            }

            th {
                background-color: #f0f0f0; /* Latar belakang header abu-abu sangat terang */
                font-weight: bold;
                color: #333; /* Warna teks header abu-abu gelap */
            }

            /* Menghindari pemotongan teks */
            td {
                 white-space: normal; /* Memungkinkan wrapping */
                 /* text-overflow: ellipsis; */ /* Hapus ellipsis jika ingin semua teks terlihat */
                 /* overflow: hidden; */ /* Hapus hidden jika ingin semua teks terlihat */
            }


            /* Menyembunyikan elemen-elemen yang tidak diperlukan dalam print */
            .no-print {
                display: none;
            }

            /* Untuk header, membuat lebih besar dan jelas */
            h1 {
                font-size: 20pt; /* Ukuran font judul saat cetak */
                margin-bottom: 20px;
                text-align: center;
                color: #333; /* Warna judul abu-abu gelap saat cetak */
            }

             h2 { /* Jika ada sub judul */
                font-size: 16pt;
                margin-bottom: 15px;
                color: #555;
             }


            /* Menyesuaikan tampilan pada header dan footer */
            .container {
                padding: 0;
                margin: 0 auto; /* Auto margin untuk centering jika perlu */
                width: 100%;
                max-width: none; /* Hapus max-width saat cetak */
                box-shadow: none; /* Hapus shadow saat cetak */
                border-radius: 0; /* Hapus border-radius saat cetak */
                background-color: #fff; /* Pastikan latar belakang putih */
            }

            /* Gaya untuk total pendapatan */
            .total-pendapatan {
                font-size: 12pt; /* Ukuran font total pendapatan saat cetak */
                font-weight: bold;
                text-align: right;
                margin-top: 15px;
                color: #333; /* Warna teks abu-abu gelap */
            }


            /* Menambahkan halaman baru jika tabel terlalu panjang */
            .page-break {
                page-break-before: always;
            }

            /* Styling untuk baris ganjil/genap di cetak */
            tbody tr:nth-child(even) {
                background-color: #f9f9f9; /* Warna latar baris genap sangat terang */
            }
             tbody tr:nth-child(odd) {
                background-color: #fff; /* Warna latar baris ganjil putih */
            }
        }

        /* Gaya untuk tampilan di browser (non-print) */
        body {
            font-family: sans-serif;
            line-height: 1.6;
            color: #333; /* Warna teks default abu-abu gelap */
            background-color: #f4f7f6; /* Latar belakang body abu-abu sangat terang */
        }

        .container {
            max-width: 960px; /* Lebar maksimum container di browser */
            margin: 20px auto; /* Centering container */
            padding: 20px;
            background-color: #fff; /* Latar belakang container putih */
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Shadow lembut */
            border-radius: 8px; /* Sudut membulat */
        }

         h1 {
            color: #2c3e50; /* Warna judul biru keabu-abuan */
            font-weight: bold;
            margin-bottom: 25px;
         }

         h2 {
            color: #34495e; /* Warna sub judul */
            font-weight: 600;
            margin-bottom: 15px;
         }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05); /* Shadow lembut untuk tabel */
            border-radius: 8px;
            overflow: hidden; /* Penting untuk rounded corners pada tabel */
        }

        thead th {
            background-color: #e0f2f7; /* Latar belakang header tabel hijau/biru muda */
            color: #00796b; /* Warna teks header hijau gelap */
            padding: 12px 15px;
            text-align: left;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        tbody td {
            padding: 10px 15px;
            border-bottom: 1px solid #eee; /* Border bawah yang sangat terang */
            color: #555; /* Warna teks data abu-abu */
            font-size: 0.9rem;
        }

        tbody tr:last-child td {
            border-bottom: none; /* Hapus border bawah pada baris terakhir */
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9; /* Warna latar baris genap sangat terang */
        }

        /* Gaya untuk total pendapatan */
        .total-pendapatan {
            font-size: 1.1rem;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
            color: #2c3e50; /* Warna teks total pendapatan */
        }

        .total-pendapatan span {
            color: #00796b; /* Warna nilai total pendapatan hijau gelap */
        }

        /* Styling tombol print */
        .print-button {
             display: inline-flex;
             align-items: center;
             padding: 10px 20px;
             background-color: #4db6ac; /* Warna hijau/teal cerah */
             color: white;
             font-weight: 600;
             border-radius: 5px;
             box-shadow: 0 2px 5px rgba(0,0,0,0.1);
             transition: background-color 0.3s ease;
             text-decoration: none; /* Pastikan tidak ada underline */
             cursor: pointer;
        }

        .print-button:hover {
            background-color: #26a69a; /* Warna hijau/teal sedikit lebih gelap saat hover */
        }

         .print-button svg {
            margin-right: 8px;
         }


    </style>
</head>
<body>
    {{-- Container utama dengan gaya untuk browser dan print --}}
    <div class="container">
        <h1>Laporan Penjualan</h1>

        <div class="mb-6 text-right no-print"> {{-- Margin bawah dan rata kanan --}}
            <button onclick="window.print()" class="print-button"> {{-- Menggunakan kelas custom untuk styling --}}
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 4V2H15V4H17C17.5523 4 18 4.44772 18 5V11C18 11.5523 17.5523 12 17 12H16V16C16 16.5523 15.5523 17 15 17H5C4.44772 17 4 16.5523 4 16V12H3C2.44772 12 2 11.5523 2 11V5C2 4.44772 2.44772 4 3 4H5ZM6 6H14V10H6V6ZM6 12H14V15H6V12Z" clip-rule="evenodd" />
                </svg>
                Cetak Laporan
            </button>
        </div>

        <table class="table-auto"> {{-- Kelas table-auto untuk lebar kolom otomatis --}}
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>ID Produk</th>
                    <th>ID Pelanggan</th>
                    <th>ID Pesanan</th>
                    <th>Jumlah Barang</th>
                    <th>Tanggal Transaksi</th>
                    <th>Total Bayar</th>
                    <th>Status Pesanan</th> {{-- Menghapus kolom Status Pembayaran karena di kode lama selalu LUNAS --}}
                </tr>
            </thead>
            <tbody>
                @if ($transactions->isEmpty())
                    <tr>
                        <td colspan="8" class="text-center text-gray-500">Tidak ada data ditemukan</td> {{-- colspan disesuaikan --}}
                    </tr>
                @else
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->id }}</td>
                            <td>
                                @foreach($transaction->detailTransactions as $detail)
                                    <p>{{ $detail->order->id_produk }}</p>
                                @endforeach
                            </td>
                            <td>{{ $transaction->id_user }}</td>
                            <td>
                                @foreach($transaction->detailTransactions as $detail)
                                    <p>{{ $detail->order->id }}</p>
                                @endforeach
                            </td>
                            <td>
                                @foreach($transaction->detailTransactions as $detail)
                                    <p>{{ $detail->order->kuantitas }}</p>
                                @endforeach
                            </td>
                            <td>{{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->format('d/m/Y') }}</td>
                            <td>Rp {{ number_format($transaction->total_pembayaran, 0, ',', '.') }}</td> {{-- Format mata uang --}}
                             {{-- Status Pembayaran (diabaikan karena selalu LUNAS di kode lama) --}}
                             <td>{{ ucfirst($transaction->status) }}</td> {{-- Status Pesanan --}}
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        @if (!$transactions->isEmpty())
            <div class="total-pendapatan"> {{-- Menggunakan kelas custom --}}
                Total Pendapatan: <span>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
            </div>
        @endif
    </div>

    {{-- Script untuk langsung mencetak saat halaman dimuat --}}
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
