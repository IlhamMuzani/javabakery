<!DOCTYPE html>
<html>
<head>
    <title>Laporan Estimasi Produksi</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px; /* Ukuran font kecil agar muat dalam satu halaman */
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 10px;
        }

        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse; /* Menghilangkan space antara border sel */
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #000; /* Border hitam untuk tabel */
            padding: 5px;
            text-align: center; /* Pusatkan teks dalam tabel */
        }

        th {
            background-color: #f2f2f2; /* Warna background header */
        }

        tr:nth-child(even) {
            background-color: #f9f9f9; /* Warna background untuk baris genap */
        }

        .table-title {
            text-align: center;
            font-weight: bold;
            margin-top: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header .title {
            font-weight: bold;
            font-size: 28px;
            margin-bottom: 5px;
            margin-top: 5px;
        }
        .header .title1 {
            margin-top: 5px;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .header .title2 {
            font-weight: bold;
            font-size: 18px;
        }
        .header .period {
            font-size: 12px;
            margin-top: 10px;
        }
        .divider {
            border: 0.5px solid;
            margin-top: 5px;
            margin-bottom: 5px;        }
        .right-align {
            text-align: right;
            font-size: 10px;
        }
        .logo img {
            width: 100px;
            height: 60px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
        </div>
        <h1 class="title">PT JAVA BAKERY FACTORY</h1>
        
        {{-- <p class="title1">Cabang: {{ strtoupper($selectedCabang) }}</p> --}}
        <div class="divider"></div>

        <h1 class="title2">LAPORAN ESTIMASI GLOBAL</h1>
    
        @php
            use Carbon\Carbon;
            Carbon::setLocale('id');
            $formattedStartDate = $tanggal ? Carbon::parse($tanggal)->translatedFormat('d F Y') : 'Tidak ada';
            $formattedEndDate = $tanggal_akhir ? Carbon::parse($tanggal_akhir)->translatedFormat('d F Y') : 'Tidak ada';
            $currentDateTime = Carbon::now()->translatedFormat('d F Y H:i');
  
        @endphp
    
        <p class="period">
            Periode: {{ $formattedStartDate }} s/d {{ $formattedEndDate }}
        </p>
        <p class="period right-align" style="font-size: 10px; position: absolute; top: 0; right: 0; margin: 10px;">
            {{ $currentDateTime }}
        </p>
        
    </div>
       

    <table>
        <thead>
            <tr>
                <th>Klasifikasi</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th colspan="2">Banjaran</th>
                <th colspan="2">Tegal</th>
                <th colspan="2">Slawi</th>
                <th colspan="2">Bumiayu</th>
                <th colspan="2">Pemalang</th>
                <th colspan="2">Cilacap</th>
                <th>Total Stok</th>
                <th>Total Pesanan</th>
                <th>Total </th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
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
                <th>Pes</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupedData as $klasifikasi => $products)
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product['klasifikasi'] }}</td>
                        <td>{{ $product['kode_lama'] }}</td>
                        <td>{{ $product['nama_produk'] }}</td>
    
                        <!-- Loop toko untuk stok (permintaan) dan pes (pemesanan) -->
                        @foreach($tokoList as $tokoId => $tokoName)
                            <td>{{ $product['stok'][$tokoId] ?? '-' }}</td>
                            <td>{{ $product['pes'][$tokoId] ?? '-' }}</td>
                        @endforeach
                        <td>{{ $product['total_permintaan'] == 0 ? '-' : $product['total_permintaan'] }}</td>                        
                        <td>{{ $product['total_pemesanan'] == 0 ? '-' : $product['total_pemesanan'] }}</td>
                        <td>{{ $product['total_semua'] }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
    

</body>
</html>
