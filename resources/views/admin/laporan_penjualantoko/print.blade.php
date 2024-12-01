<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%; /* Lebar tabel 100% */
            border-collapse: collapse;
            margin: 0; /* Menghapus margin di sekitar tabel */
            padding: 0;
        }
        table, th, td {
            border: 1px solid black;
            padding: 5px; /* Mengurangi padding untuk menghindari kolom yang terlalu lebar */
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        td {
            text-align: center;
        }
        .header {
            text-align: center;
            margin-top: 3px;
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
        .table-container {
            margin: 0; /* Menghapus margin pada container tabel */
            padding: 0; /* Menghapus padding pada container */
            font-size: 7px;
        }
    
        table.no-border {
            border-collapse: collapse;
            width: 100%;
        }
    
        table.no-border, 
        table.no-border tr, 
        table.no-border td {
            border: none !important;
        }
    
        /* Tabel untuk total fee penjualan dan grand total */
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
    
        <h1 class="title2">LAPORAN PENJUALAN TOKO</h1>
    
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
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Tanggal Setoran</th>
                    <th>Penjualan Kotor</th>
                    <th>Diskon Penjualan</th>
                    <th>Penjualan Bersih</th>
                    <th>Deposit Keluar</th>
                    <th>Deposit Masuk</th>
                    <th>Total Penjualan</th>
                    <th>Mesin EDC</th>
                    <th>GoBiz</th>
                    <th>Transfer</th>
                    <th>QRIS</th>
                    <th>Total Setoran</th>
                    <th>Nominal Setoran</th>
                    <th>Plus Minus</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalPenjualanKotor = 0;
                    $totalDiskonPenjualan = 0;
                    $totalPenjualanBersih = 0;
                    $totalDepositKeluar = 0;
                    $totalDepositMasuk = 0;
                    $totalTotalPenjualan = 0;
                    $totalMesinEDC = 0;
                    $totalGoBiz = 0;
                    $totalTransfer = 0;
                    $totalQRIS = 0;
                    $totalTotalSetoran = 0;
                    $totalNominalSetoran = 0;
                    $totalPlusMinus = 0;
                @endphp
    
                @foreach($setoranPenjualans as $setoran)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($setoran->tanggal_setoran)->format('d-m-Y') }}</td>
                        <td style="text-align: right">{{ number_format($setoran->penjualan_kotor, 0, ',', '.') }}</td>
                        <td style="text-align: right">{{ number_format($setoran->diskon_penjualan, 0, ',', '.') }}</td>
                        <td style="text-align: right">{{ number_format($setoran->penjualan_bersih, 0, ',', '.') }}</td>
                        <td style="text-align: right">{{ number_format($setoran->deposit_keluar, 0, ',', '.') }}</td>
                        <td style="text-align: right">{{ number_format($setoran->deposit_masuk, 0, ',', '.') }}</td>
                        <td style="text-align: right">{{ number_format($setoran->total_penjualan, 0, ',', '.') }}</td>
                        <td style="text-align: right">{{ number_format($setoran->mesin_edc, 0, ',', '.') }}</td>
                        <td style="text-align: right">{{ number_format($setoran->gobiz, 0, ',', '.') }}</td>
                        <td style="text-align: right">{{ number_format($setoran->transfer, 0, ',', '.') }}</td>
                        <td style="text-align: right">{{ number_format($setoran->qris, 0, ',', '.') }}</td>
                        <td style="text-align: right">{{ number_format($setoran->total_setoran, 0, ',', '.') }}</td>
                        <td style="text-align: right">{{ number_format($setoran->nominal_setoran, 0, ',', '.') }}</td>
                        <td style="text-align: right">{{ number_format($setoran->plus_minus, 0, ',', '.') }}</td>
                    </tr>
    
                    @php
                        $totalPenjualanKotor += $setoran->penjualan_kotor;
                        $totalDiskonPenjualan += $setoran->diskon_penjualan;
                        $totalPenjualanBersih += $setoran->penjualan_bersih;
                        $totalDepositKeluar += $setoran->deposit_keluar;
                        $totalDepositMasuk += $setoran->deposit_masuk;
                        $totalTotalPenjualan += $setoran->total_penjualan;
                        $totalMesinEDC += $setoran->mesin_edc;
                        $totalGoBiz += $setoran->gobiz;
                        $totalTransfer += $setoran->transfer;
                        $totalQRIS += $setoran->qris;
                        $totalTotalSetoran += $setoran->total_setoran;
                        $totalNominalSetoran += $setoran->nominal_setoran;
                        $totalPlusMinus += $setoran->plus_minus;
                    @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr style="font-weight: bold;">
                    <td colspan="1" class="text-right">Grand Total</td>
                    <td style="text-align: right">{{ number_format($totalPenjualanKotor, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($totalDiskonPenjualan, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($totalPenjualanBersih, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($totalDepositKeluar, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($totalDepositMasuk, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($totalTotalPenjualan, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($totalMesinEDC, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($totalGoBiz, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($totalTransfer, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($totalQRIS, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($totalTotalSetoran, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($totalNominalSetoran, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($totalPlusMinus, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

</body>
</html>
