<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan Produk</title>
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
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
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
        .table-container {
            margin-bottom: 30px;
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
    
        <h1 class="title2">LAPORAN PENJUALAN PRODUK RINCI</h1>
    
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
        $grandTotal = 0;
        $grandTotalFee = 0;
        $globalCounter = 1;
    @endphp

    @foreach ($inquery->groupBy('kode_penjualan') as $kodePenjualan => $items)
        <div class="table-container">
            @php
                $firstItem = $items->first();
                $formattedDate = \Carbon\Carbon::parse($firstItem->tanggal_penjualan)->format('d/m/Y H:i');
            @endphp

<table>
    <tr>
        <td colspan="2" class="text-left"><strong>Kode Penjualan : {{ $kodePenjualan }}</strong></td>
        <td colspan="3" class="text-right"><strong>Tanggal : {{ $formattedDate}}</strong></td>
        <td colspan="2" class="text-right"><strong>{{ $items->first()->toko->nama_toko }}</strong></td>
    </tr>              
    <thead>
        <tr>
            <th class="text-center" style="width: 5%;">No</th>
            <th style="width: 15%;">Kode Produk</th>
            <th style="width: 30%;">Produk</th>
            <th style="width: 10%;">Qty</th>
            <th style="width: 15%;">Harga</th>
            <th style="width: 10%;">Diskon</th>
            <th style="width: 15%;">Total</th>
        </tr>
    </thead>
    <tbody>
        @php
            $subTotal = 0;
            $grandTotalFee = 0;
            $grandTotal = 0;
        @endphp
        @foreach ($items as $item)
            @foreach ($item->detailpenjualanproduk as $detail)
                @php
                    // Pastikan jumlah dan harga bertipe angka
                    $jumlah = (float) $detail->jumlah;
                    $harga = (float) preg_replace('/[^\d]/', '', $detail->harga);
    
                    // Hitung total per detail
                    $totalForDetail = $jumlah * $harga;
                    $subTotal += $totalForDetail;
    
                    // Pastikan diskon bertipe angka
                    $diskon = (float) preg_replace('/[^\d]/', '', $detail->diskon ?? 0);
                @endphp
                <tr>
                    <td class="text-center">{{ $globalCounter++ }}</td>
                    <td>{{ $detail->kode_lama }}</td>
                    <td>{{ $detail->nama_produk }}</td>
                    <td>{{ $jumlah }}</td>
                    <td>{{ number_format($harga, 0, ',', '.') }}</td>
                    <td>{{ number_format($diskon, 0, ',', '.') }}</td>
                    <td>{{ 'Rp. ' . number_format($totalForDetail, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        @endforeach
    
        <tr>
            <td colspan="6" class="text-right"><strong>Sub Total</strong></td>
            <td>{{ 'Rp. ' . number_format($subTotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            @if ($item->metode_id !== null)
            <td colspan="6" class="text-right"><strong>Fee {{ $item->metodepembayaran->fee }}%</strong></td>
            <td>
                @php
                    // Pastikan total_fee bertipe angka
                    $total_fee = (float) preg_replace('/[^\d]/', '', $item->total_fee ?? 0);
                    $grandTotalFee += $total_fee;
                @endphp
                {{ 'Rp. ' . number_format($total_fee, 0, ',', '.') }}
            </td>
            @endif
        </tr>
        <tr>
            <td colspan="6" class="text-right"><strong>Metode Pembayaran</strong></td>
            <td>{{ $item->metodePembayaran ? $item->metodePembayaran->nama_metode : 'Tunai' }}</td>
        </tr>
        <tr>
            <td colspan="6" class="text-right"><strong>Total Bayar</strong></td>
            <td>
                @php
                    // Pastikan sub_total bertipe angka
                    $totalBayar = (float) preg_replace('/[^\d]/', '', $item->sub_total ?? 0);
                    $grandTotal += $totalBayar;
                @endphp
                {{ 'Rp. ' . number_format($totalBayar, 0, ',', '.') }}
            </td>
        </tr>
        @if ($item->metode_id == null)
        <tr>
            <td colspan="6" class="text-right"><strong>Uang Bayar</strong></td>
            <td>{{ 'Rp. ' . number_format((float) preg_replace('/[^\d]/', '', $item->bayar ?? 0), 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="6" class="text-right"><strong>Kembali</strong></td>
            <td>{{ 'Rp. ' . number_format((float) preg_replace('/[^\d]/', '', $item->kembali ?? 0), 0, ',', '.') }}</td>
        </tr>
        @endif
    </tbody>
    
</table>

        </div>
    @endforeach

    <!-- Tabel total penjualan fee dan grand total -->
    <table style="width: 50%; margin-left: auto; margin-right: 0; background-color: yellow">
        <tbody>
            <tr style="border: none;">
                <td style="text-align: right; border: none;">Total Penjualan :</td>
                <td style="text-align: right; font-weight: bold; border: none;">{{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
            <tr style="border: none;">
                <td style="text-align: right; width: 60%; border: none;">Fee Penjualan :</td>
                <td style="text-align: right; font-weight: bold; width: 40%; border: none;">{{ number_format($grandTotalFee, 0, ',', '.') }}</td>
            </tr>
            <tr style="border-top: 2px solid black;">
                <td style="text-align: right; border: none;">Grand Total Penjualan :</td>
                <td style="text-align: right; font-weight: bold; border: none;">{{'Rp. ' .   number_format($grandTotal - $grandTotalFee, 0, ',', '.') }}</td>
            </tr>
          
        </tbody>
    </table>
</body>
</html>
