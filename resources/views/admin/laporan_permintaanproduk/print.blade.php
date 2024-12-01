<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Permintaan Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        .logo img {
            width: 150px;
            height: 75px;
            margin-top: 1px;
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
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 9px; /* Menetapkan ukuran font kecil untuk tabel */
        }
        th, td {
            border: 1px solid #000; /* Menetapkan warna border hitam untuk tabel */
            padding: 4px; /* Menyesuaikan padding agar lebih kecil */
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .text-center {
            text-align: center;
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
    
        <h1 class="title2">LAPORAN PERMINTAAN PRODUK</h1>
    
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

    @php
    $tokoTerpilih = Request::get('toko_id'); // Ambil toko yang dipilih dari request
@endphp

<table>
    <thead>
        <tr>
            <th class="text-center">No</th>
            <th>Divisi</th>
            <th>Kode Produk</th>
            <th>Produk</th>
            @foreach ($tokoData as $toko)
                <th>{{ $toko->nama_toko }}</th>
            @endforeach
            @if (!$tokoTerpilih) <!-- Jika tidak ada toko yang dipilih (semua toko) -->
                <th>Total</th> <!-- Kolom total ditampilkan -->
            @endif
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
            $produkData = [];
            $totalPerToko = [];
            $grandTotal = 0;
        @endphp

        @foreach ($permintaanProduk as $permintaan)
            @foreach ($permintaan->detailpermintaanproduks as $detail)
                @php
                    $produk = $detail->produk;
                    $klasifikasi = $produk->klasifikasi;
                    $subklasifikasi = $klasifikasi->subklasifikasi;

                    $totalPerToko[$detail->toko_id] = ($totalPerToko[$detail->toko_id] ?? 0) + $detail->jumlah;

                    $produkData[$detail->produk_id]['produk'] = $detail->produk->nama_produk;
                    $produkData[$detail->produk_id]['kode_lama'] = $detail->produk->kode_lama;
                    $produkData[$detail->produk_id]['kode_permintaan'] = $permintaan->kode_permintaan;
                    $produkData[$detail->produk_id]['kategori'] = $klasifikasi->nama ?? '-';
                    $produkData[$detail->produk_id]['total'] = ($produkData[$detail->produk_id]['total'] ?? 0) + $detail->jumlah;
                    $produkData[$detail->produk_id]['detail'][$detail->toko_id] = ($produkData[$detail->produk_id]['detail'][$detail->toko_id] ?? 0) + $detail->jumlah;

                    $grandTotal += $detail->jumlah;
                @endphp
            @endforeach
        @endforeach

        @foreach ($produkData as $produkId => $data)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>{{ $data['kategori'] }}</td>
                <td>{{ $data['kode_lama'] }}</td>
                <td>{{ $data['produk'] }}</td>
                @foreach ($tokoData as $toko)
                    <td class="text-center">{{ $data['detail'][$toko->id] ?? 0 }}</td>
                @endforeach
                @if (!$tokoTerpilih) <!-- Jika tidak ada toko yang dipilih (semua toko) -->
                    <td class="text-center">{{ $data['total'] }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" class="text-center">Total</th>
            @foreach ($tokoData as $toko)
                <th class="text-center">{{ $totalPerToko[$toko->id] ?? 0 }}</th>
            @endforeach
            @if (!$tokoTerpilih) <!-- Jika tidak ada toko yang dipilih (semua toko) -->
                <th class="text-center">{{ $grandTotal }}</th>
            @endif
        </tr>
    </tfoot>
</table>

</body>
</html>


