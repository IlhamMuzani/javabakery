<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengiriman Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            padding-bottom: 40px;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .logo img {
            width: 150px;
            height: 77px;
        }
        .header {
            width: 100%;
            border-bottom: 1px solid #000;
            margin-top: 20px;
            padding: 0 10px;
        }
        .header td {
            border: none;
        }
        .header .title {
            font-weight: bold;
            font-size: 30px;
        }
        .header .address, .header .contact {
            font-size: 12px;
        }
        .divider {
            border: 0.5px solid #000;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .change-header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .tanggal {
            text-align: left;
            font-size: 12px;
            font-weight: bold;
            margin-top: 20px;
        }
        .section-title {
            margin-top: 30px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 16px;
            text-align: left;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            page-break-inside: auto;
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
        tr {
    page-break-inside: avoid;
    page-break-after: auto;
}
        .signature-container {
            margin-top: 60px;
        }
        .signature-row {
            display: flex;
            justify-content: space-between;
            margin: 0 20px;
        }
        .signaturea {
            flex: 1;
            text-align: left;
            margin: 0 10px;
        }
        .signatureb {
            flex: 1;
            text-align: center;
            margin: 0 10px;
            margin-top: -200px;
        }
        .signaturec {
            flex: 1;
            text-align: right;
            margin: 0 10px;
            margin-top: -200px;
        }
        .signature p {
            margin: 0;
            margin-top: 10px;
        }
        .row p {
            margin: 0;
        }
        .total-row {
            font-weight: bold;
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
            .header {
                border-bottom: 1px solid #000;
                page-break-inside: avoid;
            }
            .divider {
                border: 0.5px solid #000;
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
            .change-header {
                page-break-before: always;
            }
            .signature-container {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="change-header">LAPORAN PENGIRIMAN BARANG JADI</div>

    <div class="text" style="margin-bottom: 1px;">
        @php
            \Carbon\Carbon::setLocale('id'); // Set locale ke bahasa Indonesia
    
            $formattedStartDate = \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y');
            $formattedEndDate = \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y');
            $currentDateTime = \Carbon\Carbon::now()->translatedFormat('d F Y H:i');
        @endphp
    
        @if ($startDate && $endDate)
            <p>
                Periode: {{ $formattedStartDate }} s/d {{ $formattedEndDate }} &nbsp;&nbsp;&nbsp;
                <span style="float: right; font-style: italic">{{ $currentDateTime }}</span>
            </p>
        @else
            <p>
                Periode: Tidak ada tanggal awal dan akhir yang diteruskan. &nbsp;&nbsp;&nbsp;
                <span style="float: right;">{{ $currentDateTime }}</span>
            </p>
        @endif
    </div>
    
    <!-- Detail Produk -->
    @foreach($groupedData as $kode_pengiriman => $items)
    <div>
        <p style="margin-bottom: 2px;"><strong>Kode Pengiriman:</strong> {{ $kode_pengiriman }}</p>
        <p><strong>Cabang:</strong> {{ $items->first()->toko->nama_toko }}</p> <!-- Menampilkan nama toko -->
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Produk</th>
                <th>Kategori</th>
                <th>Produk</th>
                <th style="text-align: right;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php $totalJumlah = 0; @endphp <!-- Variabel untuk menyimpan total -->
            @foreach($items as $key => $detail)
            <tr>
                <td>{{ $key + 1 }}</td> 
                <td>{{ $detail->produk->kode_lama }}</td>
                <td>{{ $detail->produk->subklasifikasi->nama }}</td>
                <td>{{ $detail->produk->nama_produk }}</td>
                <td style="text-align: right;">{{ $detail->jumlah }}</td>
            </tr>
            @php $totalJumlah += $detail->jumlah; @endphp <!-- Tambahkan jumlah ke total -->
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold;">Total</td>
                <td style="text-align: right; font-weight: bold;">{{ $totalJumlah }}</td>
            </tr>
        </tfoot>
    </table>
    <br>
@endforeach


</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>
