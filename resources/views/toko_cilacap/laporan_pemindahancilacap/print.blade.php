<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pemindahan Barang Jadi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px; /* Ukuran font lebih kecil untuk tabel */
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .change-header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .section {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="change-header">LAPORAN PEMINDAHAN BARANG JADI</div>
    <div class="text" style="margin-bottom: 1px;">
        @php
            \Carbon\Carbon::setLocale('id'); // Set locale ke bahasa Indonesia
    
            $formattedStartDate = \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y');
            $formattedEndDate = \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y');
            $currentDateTime = \Carbon\Carbon::now()->translatedFormat('d F Y H:i');
        @endphp
    
        @if ($startDate && $endDate)
            <p style="font-size: 10px;">
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

    @if($stokBarangJadi->isEmpty())
        <p>Data tidak ditemukan untuk filter yang diberikan.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Pemindahan</th>
                    <th>Divisi</th>
                    <th>Nama Produk</th>
                    <th>Dari</th>
                    <th>Ke</th>
                    <th>Jumlah</th>
                    {{-- <th>Tanggal Input</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach($stokBarangJadi->flatten() as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->kode_pemindahan }}</td>
                        <td>{{ $item->produk->klasifikasi->nama }}</td>
                        <td>{{ $item->produk->nama_produk }}</td>
                        <td>{{ $item->toko->nama_toko }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td>{{ $item->jumlah }}</td>
                        {{-- <td>{{ \Carbon\Carbon::parse($item->tanggal_input)->format('d/m/Y') }}</td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
