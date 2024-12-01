<!DOCTYPE html>
<html>
<head>
    <title>Laporan Stok Toko</title>
    <style>
          body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            padding-bottom: 100px; /* Increased padding-bottom for signatures */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 4px;
            text-align: left;
            font-size: 11px;
        }
        th {
            background-color: #f2f2f2;
        }
        .change-header {
            text-align: center;
            margin-top: 3px;
        }
        .change-header span {
            display: block;
        }
        .change-header .title {
        font-weight: bold;
        font-size: 28px;
        margin-bottom: 5px;
        }
        .change-header .title1 {
        margin-top: 5px;
        font-size: 14px;
        margin-bottom: 5px;
        }
        .change-header .title2 {
            font-weight: bold;
            font-size: 18px;
        }
        .header .period {
            font-size: 12px;
            margin-top: 10px;
        }
        .logo img {
            width: 100px;
            height: 60px;
        }
    </style>
</head>
<body>
    {{-- <div class="change-header">LAPORAN STOK BARANG JADI</div> --}}
    <div class="change-header">
        <div class="logo">
            <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
        </div>
        <h1 class="title">PT JAVA BAKERY FACTORY</h1>
        <p class="title1">Cabang: {{ $tokoCabang }}</p> <!-- Menampilkan nama cabang -->
        <div class="divider"></div>
    
        <h1 class="title2">LAPORAN STOK PESANAN TOKO</h1>
        @php
        \Carbon\Carbon::setLocale('id'); // Set locale ke bahasa Indonesi
        $currentDateTime = \Carbon\Carbon::now()->translatedFormat('d F Y H:i');
        $periodDateTime = \Carbon\Carbon::now()->translatedFormat('d F Y');
    @endphp
      
      <p class="period">
        {{ $periodDateTime }}
    </p>
      <p class="period right-align" style="font-size: 10px; position: absolute; top: 0; right: 0; margin: 10px;">
        {{ $currentDateTime }}
    </p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Stok</th>
                <th>Harga Jual</th>
                <th>Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produkWithStok as $produk)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $produk->kode_lama }}</td>
                    <td>{{ $produk->nama_produk }}</td>
                    <td style="text-align: right">{{ $produk->jumlah }}</td>
                    <td style="text-align: right">{{ number_format($produk->harga, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($produk->subTotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Total</th>
                <th style="text-align: right">{{ $totalStok }}</th>
                <th></th>
                <th style="text-align: right">{{ 'Rp. ' . number_format($totalSubTotal, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
