<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pemesanan Rinci </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
            padding: 4px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: white;
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
        .header .address, .header .contact {
            font-size: 12px;
        }
        .divider {
            border: 0.5px solid;
            margin-top: 3px;
            margin-bottom: 1px;
        }
        .table-container {
            margin-bottom: 10px;
        }
        .summary-table {
            width: 50%;
            margin-left: auto;
            margin-right: auto;
            margin-top: 20px;
            border: none;
        }
        .summary-table td {
            padding: 8px;
            font-size: 12px;
            border: none !important;
        }
        .summary-table .text-right {
            text-align: right;
        }
        .summary-table .text-left {
            text-align: left;
        }
        .summary-table .bold {
            font-weight: bold;
        }
        .page-number {
            position: absolute;
            bottom: -10px;
            right: 0;
            font-size: 10px;
        }
        .page-number:before {
            content: "Halaman " counter(page);
        }
      
    </style>
</head>
<body>

    <div class="header">
        <h1 class="title">JAVA BAKERY</h1>
        <p class="title1">Cabang: BANJARAN</p> 
        <div class="divider"></div>
        <h1 class="title2">LAPORAN PEMESANAN PRODUK RINCI</h1>

        @php
            use Carbon\Carbon;
            Carbon::setLocale('id');
            $formattedStartDate = $startDate ? Carbon::parse($startDate)->translatedFormat('d F Y') : 'Tidak ada';
            $formattedEndDate = $endDate ? Carbon::parse($endDate)->translatedFormat('d F Y') : 'Tidak ada';
            $currentDateTime = Carbon::now()->translatedFormat('d F Y H:i');
        @endphp

        <p class="period">
            Periode: {{ $formattedStartDate }} s/d {{ $formattedEndDate }}
        </p>
        <p class="period right-align" style="font-size: 10px; position: absolute; top: 0; right: 0; margin: 10px;">
            {{ $currentDateTime }}
        </p>
    </div>

    @foreach ($groupedByKlasifikasi as $klasifikasi => $items)
        <div class="table-container">
            <h2>{{ $klasifikasi }}</h2>
            <table>
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode Pemesanan</th>
                        <th>Kategori</th>
                        <th>Produk</th>
                        <th>Catatan</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $grandTotalJumlah = 0;
                @endphp
                @foreach ($items as $item)
                    @foreach ($item->detailpemesananproduk as $detail)
                        @php
                            $subKlasifikasi = $detail->produk->klasifikasi->subklasifikasi->where('klasifikasi_id', $detail->produk->klasifikasi->id)->first();
                            $grandTotalJumlah += (int)$detail->jumlah; // Ensure jumlah is treated as an integer
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->parent->iteration }}</td>
                            <td>{{ $item->kode_pemesanan }}</td>
                            <td>{{ $subKlasifikasi->nama ?? 'Tidak Diketahui' }}</td>
                            <td>{{ $detail->produk->nama_produk }}</td>
                            <td>{{ $item->catatan }}</td>
                            <td style="text-align: right">{{ number_format((int)$detail->jumlah, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @endforeach
                <tr>
                    <td colspan="5" class="text-right"><strong>Total Jumlah</strong></td>
                    <td style="text-align: right">{{ number_format($grandTotalJumlah, 0, ',', '.') }}</td>
                </tr>
                
                </tbody>
            </table>
        </div>
    @endforeach
  <!-- Page Number -->
  {{-- <div class="page-number"></div> --}}
    
</body>

</html>
