<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Permintaan Produk</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px; /* Ukuran font kecil agar muat dalam satu halaman */
            margin: 0;
            padding: 0;
            padding-bottom: 80px; /* Tambahkan ruang ekstra untuk informasi admin */
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 10px;
        }
        .logo img {
            width: 150px;
            height: 77px;
        }
        .header {
            text-align: center;
            margin-top: 10px;
        }
        .header .title {
            font-weight: bold;
            font-size: 24px;
        }
        .divider {
            border: 0.5px solid #000;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .change-header {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-top: 4px;
            margin-bottom: 5px;
        }
        .tanggal {
            text-align: left;
            font-size: 12px;
            font-weight: bold;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 9px;
        }
        th, td {
            padding: 5px;
            border: 1px solid black;
            text-align: center; /* Pusatkan teks dalam tabel */
        }
        th {
            background-color: #f2f2f2; /* Warna background header */
        }
        /* Atur ukuran font di dalam tabel */
        table td, table th {
            font-size: 8px; /* Ukuran font tabel lebih kecil */
        }
        table th:nth-child(1),
        table td:nth-child(1) { width: 5%; } /* Lebar untuk kolom No */

        table th:nth-child(2),
        table td:nth-child(2) { width: 25%; } /* Lebar untuk kolom Kode Produk */

        table th:nth-child(3),
        table td:nth-child(3) { width: 25%; } /* Lebar untuk kolom Kategori */

        table th:nth-child(4),
        table td:nth-child(4) { width: 30%; } /* Lebar untuk kolom Produk */

        table th:nth-child(5),
        table td:nth-child(5) { width: 10%; } /* Lebar untuk kolom Jumlah */

        .total-row {
            font-weight: bold;
        }
        .admin-info {
            text-align: right;
            margin-top: 10px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    
    <div class="header">
        <div class="logo">
            <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
        </div>
        <h1 class="title">PT JAVA BAKERY FACTORY</h1>
        <p style="font-size: 12px;">Jl. HOS. Cokro Aminoto No.5, Kagok, Kec. Slawi, Kabupaten Tegal, Jawa Tengah 52411</p>
        <div class="divider"></div>
    
        <h1 class="title2">SURAT PERINTAH PRODUKSI</h1>
    
        <p class="period">
            Periode: {{ $formattedStartDate }} s/d {{ $formattedEndDate }}
        </p>
        <p class="period right-align" style="font-size: 10px; position: absolute; top: 0; right: 0; margin: 10px;">
            {{ $currentDateTime }}
        </p>
    </div>
    

    <!-- Informasi Permintaan -->
    <div style="margin-top: 2px;">
        @foreach ($groupedData as $klasifikasi => $products)
        <h3>{{ $klasifikasi }}</h3> <!-- Menampilkan nama klasifikasi di atas tabel -->
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">Kode Produk</th>
                    <th style="width: 150px;">Nama Produk</th>
                    <th colspan="2" style="width: 80px;">Banjaran</th>
                    <th colspan="2" style="width: 80px;">Tegal</th>
                    <th colspan="2" style="width: 80px;">Slawi</th>
                    <th colspan="2" style="width: 80px;">Bumiayu</th>
                    <th colspan="2" style="width: 80px;">Pemalang</th>
                    <th colspan="2" style="width: 80px;">Cilacap</th>
                    <th style="width: 40px;">Total Stok</th>
                    <th style="width: 40px;">Total Pesanan</th>
                    <th style="width: 40px;">Total</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th>Pes</th>
                    <th>Stok</th>
                    <th>Pes</th>
                    <th>Stok</th>
                    <th>Pes</th>
                    <th>Stok</th>
                    <th>Pes</th>
                    <th>Stok</th>
                    <th>Pes</th>
                    <th>Stok</th>
                    <th>Pes</th>
                    <th>Stok</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            
            <tbody>
                @php
                    $totalStok = 0;
                    $totalPesanan = 0;
                    $grandTotal = 0; // Total keseluruhan
                @endphp
                @foreach ($products as $product)
                    @php
                        $stok = array_sum($product['stok']);
                        $pesanan = array_sum($product['pes']);
                        $total = $stok + $pesanan; // Total untuk setiap produk
                    @endphp
                    <tr>
                        <td>{{ $product['kode_lama'] }}</td>
                        <td>{{ $product['nama_produk'] }}</td>
                        
                        <!-- Banjaran (toko_id 1) -->
                        <td style="text-align: right">{{ $product['pes'][1] }}</td>
                        <td style="text-align: right">{{ $product['stok'][1] }}</td>
                        
                        <!-- Tegal (toko_id 2) -->
                        <td style="text-align: right">{{ $product['pes'][2] }}</td>
                        <td style="text-align: right">{{ $product['stok'][2] }}</td>
    
                        <td style="text-align: right">{{ $product['pes'][3] }}</td>
                        <td style="text-align: right">{{ $product['stok'][3] }}</td>
    
                        <td style="text-align: right">{{ $product['pes'][4] }}</td>
                        <td style="text-align: right">{{ $product['stok'][4] }}</td>
    
                        <td style="text-align: right">{{ $product['pes'][5] }}</td>
                        <td style="text-align: right">{{ $product['stok'][5] }}</td>
    
                        <td style="text-align: right">{{ $product['pes'][6] }}</td>
                        <td style="text-align: right">{{ $product['stok'][6] }}</td>
                        
                        <td style="text-align: right">{{ $stok }}</td>
                        <td style="text-align: right">{{ $pesanan }}</td>
                        <td style="text-align: right">{{ $total }}</td>
                        
                        @php
                            // Menambahkan ke total keseluruhan
                            $totalStok += $stok;
                            $totalPesanan += $pesanan;
                            $grandTotal += $total;
                        @endphp
                    </tr>
                @endforeach
            </tbody>
    
            <tfoot>
                <tr>
                    <th colspan="14" style="text-align: right;">Total:</th>
                    
                    <th style="text-align: right;">{{ $totalStok }}</th>
                    <th style="text-align: right;">{{ $totalPesanan }}</th>
                    <th style="text-align: right;">{{ $grandTotal }}</th>
                </tr>
            </tfoot>
        </table>
        @endforeach
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>
