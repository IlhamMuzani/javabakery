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
        <p class="title1">Cabang: {{ $selectedCabang }}</p> 
        <div class="divider"></div>
        <h1 class="title2">LAPORAN PESANAN PRODUK RINCI</h1>

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

    @foreach ($finalGrouped as $klasifikasi => $groupedByProduk)
    <div class="table-container">
        <h2>{{ $klasifikasi }}</h2>

        @foreach ($groupedByProduk as $produkId => $produkItems)
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;" class="text-center">No</th>
                        <th style="width: 20%;">No Pemesanan</th>
                        <th style="width: 20%;">Pemesan</th>
                        <th style="width: 50%;">Catatan</th>
                        <th style="width: 10%;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandTotalJumlah = 0;
                    @endphp
                    @foreach ($produkItems as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item['kode_pemesanan'] }}</td>
                            <td>{{ $item['nama_pelanggan'] }} / {{ $item['kode_pelanggan'] }}</td>
                            <td>{{ $item['catatan'] }}</td>

                            <td style="text-align: right">{{ number_format((int)$item['jumlah'], 0, ',', '.') }}</td>
                        </tr>
                        @php
                            $grandTotalJumlah += (int)$item['jumlah'];
                        @endphp
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-right" style="background-color: rgb(245, 245, 144);"><strong>{{ $produkItems->first()['produk']->kode_lama }} - {{ $produkItems->first()['produk']->nama_produk }}</strong></td>
                        <td style="text-align: right; background-color: rgb(245, 245, 144);">{{ number_format($grandTotalJumlah, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
            <br> <!-- Spasi antara tabel produk -->
        @endforeach
    </div>
    <hr style="border: 1px dashed #000; margin: 20px 0;"> <!-- Divider putus-putus -->
@endforeach




  <!-- Page Number -->
  {{-- <div class="page-number"></div> --}}
    
</body>

</html>
