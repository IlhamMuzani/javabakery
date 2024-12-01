<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pemusnahan Barang Jadi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 6px;
        }
        th {
            background-color: white;
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
        {{-- <p class="title1">Cabang: {{ strtoupper($branchName) }}</p> --}}
        <div class="divider"></div>

        <h1 class="title2">LAPORAN PEMUSNAHAN BARANG JADI</h1>

        @php
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
        </p>
    </div>

<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Divisi</th>
            <th>Kode Produk</th>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalJumlah = 0;
            $totalKeseluruhan = 0;
        @endphp
        @foreach ($stokBarangJadi as $kode_retur => $returs)
            @foreach ($returs as $index => $retur)
                @php
                    $total = $retur->jumlah * $retur->produk->harga;
                    $totalJumlah += $retur->jumlah;
                    $totalKeseluruhan += $total;
                @endphp
                <tr>
                    <td>{{ $loop->parent->iteration }}</td>
                    <td>{{ $retur->produk->klasifikasi->nama }}</td>
                    <td>{{ $retur->produk->kode_lama }}</td>
                    <td>{{ $retur->produk->nama_produk }}</td>
                    <td style="text-align: right">{{ $retur->jumlah }}</td>
                    <td style="text-align: right">{{ number_format($retur->produk->harga, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
    <tfoot class="tfoot">
        <tr>
            <td colspan="4"><strong>Total Keseluruhan:</strong></td>
            <td style="text-align: right"><strong>{{ $totalJumlah }}</strong></td>
            <td></td>
            <td style="text-align: right"><strong>{{ 'Rp. '. number_format($totalKeseluruhan, 0, ',', '.') }}</strong></td>
        </tr>
    </tfoot>
</table>

</body>
</html>
