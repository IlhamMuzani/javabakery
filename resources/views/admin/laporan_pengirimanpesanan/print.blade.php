<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengiriman Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-top: 3px;
        }
        .header .title {
            font-weight: bold;
            font-size: 28px;
        }
        .divider {
            border: 0.5px solid;
            margin-top: 3px;
            margin-bottom: 1px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
        }
        th, td {
            padding: 4px;
            border: 1px solid black;
            text-align: left;
        }
        th {
            background-color: white;
        }
        
        .signature-container {
            margin-top: 60px;
        }
        .logo img {
            width: 100px;
            height: 60px;
        }
        
        .divider1 {
        border-top: 2px dashed #000; /* Gaya garis putus-putus dengan warna hitam */
        margin: 20px 0; /* Jarak atas dan bawah divider */
        }

        .info-group {
    margin-bottom: 5px;
    font-size: 12px;
    display: flex;
    flex-direction: column;
    gap: 2px; /* Mengurangi jarak antar elemen */
}
.info-label {
    min-width: 120px; /* Sesuaikan lebar minimum agar tidak terlalu lebar */
    font-weight: bold;
}
.info-value {
    margin-left: 5px; /* Tambahkan sedikit jarak antar label dan value */
}


        /* CSS untuk tampilan cetak */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .container {
                width: 100%;
                margin: 0;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
                page-break-inside: auto;
            }
            th, td {
                padding: 6px;
                border: 1px solid #000;
            }
            /* Hindari pemutusan halaman di tengah tabel */
            tr {
                page-break-inside: avoid;
            }
            .signature-container {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo">
            <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
        </div>
        <h1 class="title">PT JAVA BAKERY FACTORY</h1>
        <p>Cabang: {{ $selectedCabang }}</p> 
        <div class="divider"></div>
        <h2 style="font-family: Arial, Helvetica, sans-serif">LAPORAN PENGIRIMAN STOK</h2>

        @php
            use Carbon\Carbon;
            Carbon::setLocale('id');
            $formattedStartDate = $startDate ? Carbon::parse($startDate)->translatedFormat('d F Y') : 'Tidak ada';
            $formattedEndDate = $endDate ? Carbon::parse($endDate)->translatedFormat('d F Y') : 'Tidak ada';
            $currentDateTime = Carbon::now()->translatedFormat('d F Y H:i');
        @endphp

        <p>Periode: {{ $formattedStartDate }} s/d {{ $formattedEndDate }}</p>
        <p style="font-size: 10px; position: absolute; top: 0; right: 0; margin: 10px;">
            {{ $currentDateTime }}
        </p>
    </div>

    <!-- Detail Produk -->
    @foreach($groupedData as $groupKey => $items)
    @php
            // Pisahkan kode_pengiriman dan klasifikasi
            list($kode_pengiriman, $klasifikasi) = explode('|', $groupKey);
        @endphp
    <div class="info-group" style="display: flex; flex-direction: column; gap: 2px;">
        <div style="display: flex; align-items: center;">
                <span class="info-label" style="min-width: 100px; display: inline-flex; align-items: center;"><strong>Kode Pengiriman</strong></span>
                <span class="info-value" style="min-width: 50px; display: inline-flex; align-items: center;">: {{ $kode_pengiriman }}</span>
        </div>
        <div style="display: flex; align-items: center;">
                <span class="info-label" style="min-width: 100px; display: inline-flex; align-items: center;"><strong>Klasifikasi</strong></span>
                <span class="info-value" style="min-width: 50px; display: inline-flex; align-items: center;">: {{ $klasifikasi }}</span>
        </div>
    </div>


    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 10%">Kategori</th>
                <th style="width: 10%">Kode Produk</th>
                <th style="width: 30%">Produk</th>
                <th style="text-align: right; width: 10%;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php $totalJumlah = 0; @endphp
            @foreach($items as $key => $detail)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $detail->produk->subklasifikasi->nama }}</td>
                <td>{{ $detail->produk->kode_lama }}</td>
                <td>{{ $detail->produk->nama_produk }}</td>
                <td style="text-align: right;">{{ $detail->jumlah }}</td>
            </tr>
            @php $totalJumlah += $detail->jumlah; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold;">Total</td>
                <td style="text-align: right; font-weight: bold;">{{ $totalJumlah }}</td>
            </tr>
        </tfoot>
    </table>
    <div class="divider1"></div>

    @endforeach
</body>
</html>
