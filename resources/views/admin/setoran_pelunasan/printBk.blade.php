<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang Keluar</title>
    <style>
        /* Tambahkan style sesuai kebutuhan */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .text {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
            font-size: 10px;
        }
        th, td {
            padding: 4px;
            text-align: left;
        }
        tfoot th {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .header {
            text-align: center;
            margin-top: 3px;
        }
        .header span {
            display: block;
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
        .header .address, .header .contact {
            font-size: 12px;
        }
        .divider {
            border: 0.5px solid;
            margin-top: 3px;
            margin-bottom: 1px;
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
        <p class="title1">Cabang: {{ strtoupper($branchName) }}</p>
        <div class="divider"></div>
    
        <h1 class="title2">LAPORAN BARANG KELUAR</h1>
    
        {{-- @php
            \Carbon\Carbon::setLocale('id'); // Set locale ke bahasa Indonesia
            $formattedStartDate = $startDate ? \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') : 'Tidak ada';
            $formattedEndDate = $endDate ? \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') : 'Tidak ada';
            $currentDateTime = \Carbon\Carbon::now()->translatedFormat('d F Y H:i');
        @endphp
    
        <p class="period">
            @if ($startDate && $endDate)
                Periode: {{ $formattedStartDate }} s/d {{ $formattedEndDate }}
            @else
                Periode: Tidak ada tanggal awal dan akhir yang diteruskan.
            @endif
        </p>
    
        <p class="period right-align" style="font-size: 10px; position: absolute; top: 0; right: 0; margin: 10px;">
            {{ $currentDateTime }}
        </p> --}}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                {{-- <th>Tanggal Penjualan</th> --}}
                <th>Kode</th>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Penjualan Kotor</th>
                <th>Diskon</th>
                <th>Penjualan Bersih</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($finalResults as $produk)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    {{-- <td>{{ \Carbon\Carbon::parse($produk['tanggal_penjualan'])->translatedFormat('d F Y') }}</td> --}}
                    <td>{{ $produk['kode_lama'] }}</td>
                    <td>{{ $produk['nama_produk'] }}</td>
                    <td style="text-align: right">{{ $produk['jumlah'] }}</td>
                    <td style="text-align: right">{{ number_format($produk['harga'], 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($produk['penjualan_kotor'], 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($produk['diskon'], 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($produk['penjualan_bersih'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            @php
                $totalJumlah = collect($finalResults)->sum('jumlah');
                $grandTotal = collect($finalResults)->sum('penjualan_bersih');
                $totalDiskon = collect($finalResults)->sum('diskon');
                $totalKotor = collect($finalResults)->sum('penjualan_kotor');
            @endphp
            <tr>
                <th colspan="3">Total</th>
                <th>{{ $totalJumlah }}</th>
                <th></th>
                <th>{{ number_format($totalKotor, 0, ',', '.') }}</th>
                <th>{{ number_format($totalDiskon, 0, ',', '.') }}</th>
                <th>{{ number_format($grandTotal, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
