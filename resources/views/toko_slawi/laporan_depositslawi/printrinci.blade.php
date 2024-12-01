<!DOCTYPE html>
<html>
<head>
    <title>Laporan Deposit Rinci</title>
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
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
            padding: 4px;
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
        .header .address, .header .contact {
            font-size: 12px;
        }
        .header .period {
            font-size: 12px;
            margin-top: 10px;
        }
        .divider {
            border: 0.5px solid;
            margin-top: 3px;
            margin-bottom: 1px;
        }
        .divider1 {
            border: 1px dashed #000; /* Garis putus-putus dengan warna hitam */
            margin-top: 3px;
            margin-bottom: 1px;
        }

        .admin-info {
            text-align: right;
            margin-top: 10px;
            font-size: 12px;
        }
        .table-container {
            margin-bottom: 15px;
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

        .no-border {
        border: none;
    }
   
    .logo img {
            width: 100px;
            height: 60px;
        }
    /* .no-border-row td {
        border: none !important;
    } */
</style>

    </style>
</head>
<body>
   

    <div class="header">
        <div class="logo">
            <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
        </div>
        <h1 class="title">PT JAVA BAKERY FACTORY</h1>
        <p class="title1">BANJARAN</p>
        <div class="divider"></div>
    
        @if ($status_pelunasan == 'diambil')
            <h1 class="title2">LAPORAN PENGAMBILAN DEPOSIT</h1>
        @else
            <h1 class="title2">LAPORAN DEPOSIT</h1>
        @endif

        @php
        \Carbon\Carbon::setLocale('id'); // Set locale ke bahasa Indonesia

        // Gunakan variabel yang dikirim dari controller
        $formattedStartDate = isset($formattedStartDate) ? \Carbon\Carbon::parse($formattedStartDate)->translatedFormat('d F Y') : null;
        $formattedEndDate = isset($formattedEndDate) ? \Carbon\Carbon::parse($formattedEndDate)->translatedFormat('d F Y') : null;
        $currentDateTime = \Carbon\Carbon::now()->translatedFormat('d F Y H:i');
    @endphp
    
        <p class="period">
            @if ($formattedStartDate && $formattedEndDate)
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

    @foreach ($inquery->groupBy('kode_dppemesanan') as $kodeDppemesanan => $items)
        <div class="table-container">
            @php
                $firstItem = $items->first();
                $formattedDate = \Carbon\Carbon::parse($firstItem->pemesananproduk->tanggal_pemesanan)->format('d/m/Y');
                $formatambil = \Carbon\Carbon::parse($firstItem->pemesananproduk->tanggal_kirim)->format('d/m/Y');
            @endphp

        <table class="no-border mb-1">
                    <tr>
                        <td class="text-left"><strong>Pelanggan</strong></td>
                        <td>: {{ $firstItem->pemesananproduk->nama_pelanggan }}</td>
                        <td class="text-right" style="color: white"><strong>Alamat</strong></td>
                        <td class="text-right"><strong>Alamat</strong></td>
                        <td>: {{ $firstItem->pemesananproduk->alamat }}</td>
                    </tr>
                    <tr>
                        <td class="text-left"><strong>Telepon</strong></td>
                        <td>: 0{{ $firstItem->pemesananproduk->telp }}</td>
                        <td class="text-right" style="color: white"><strong>ID Pelanggan</strong></td>
                        <td class="text-right"><strong>ID Pelanggan</strong></td>
                        <td>: {{ $firstItem->pemesananproduk->kode_pelanggan }}</td>
                    </tr>
        
        </table>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 0;">
            <tr>
                <td colspan="3" class="text-left"><strong>No Deposit : {{ $kodeDppemesanan }}</strong></td>
                <td colspan="2" class="text-right"><strong>Tanggal Deposit : {{ $formattedDate}}</strong></td>
                <td colspan="2" class="text-right"><strong>Tanggal Ambil : {{ $formatambil}}</strong></td>
            </tr>
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    {{-- <th>Divisi</th> --}}
                    <th>Kode Produk</th>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Diskon</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subTotal = 0;
                    $totalDiskon = 0;
                    $grandTotal = 0;
                @endphp
                @foreach ($items as $item)
                    @foreach ($item->detailpemesananproduk as $detail)
                        @php
                            $totalForDetail = $detail->jumlah * $detail->harga;
                            $diskon = floatval($detail->diskon);
                            $diskonAmount = ($totalForDetail * $diskon) / 100;
                            $totalAfterDiskon = $totalForDetail - $diskonAmount;
                            $subTotal += $totalForDetail;
                            $totalDiskon += $diskonAmount;
                            $grandTotal += $totalAfterDiskon;
                          
                        @endphp
                        <tr>
                            <td class="text-center">{{ $globalCounter++ }}</td>
                            {{-- <td>
                                @if ($item->detailpemesananproduk->isNotEmpty())
                                    {{ $item->detailpemesananproduk->pluck('produk.klasifikasi.nama')->implode(', ') }}
                                @else
                                    tidak ada
                                @endif
                            </td> --}}
                            <td>{{ $detail->kode_lama }}</td>
                            <td>{{ $detail->nama_produk }}</td>
                            <td style="text-align: right">{{ $detail->jumlah }}</td>
                            <td style="text-align: right">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td style="text-align: right">{{ $detail->diskon }}%</td>
                            <td style="text-align: right">{{ number_format($totalForDetail, 0, ',', '.') }}</td>
                            
                        </tr>
                    @endforeach
                @endforeach
   
            </tbody>
        </table>

        {{-- <table style="width: 40%; margin-left: auto; margin-right: 0; margin-top: 0px;">
            <tbody>
                <tr>
                    <td style="text-align: left ; width: 60%;"><strong>Sub Total</strong></td>
                    <td style="text-align: right ; width: 40%;">{{ number_format($subTotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="text-align: left ; width: 60%;"><strong>Diskon</strong></td>
                    <td style="text-align: right ; width: 40%;">{{ number_format($totalDiskon, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="text-align: left ; width: 60%;"><strong>Total Pesanan</strong></td>
                    <td style="text-align: right ; width: 40%;">{{ number_format($grandTotal, 0, ',', '.') }}</td>
                </tr>
                
                <tr>
                    <td style="text-align: left ; width: 60%;"><strong>DP (Min.50%)</strong></td>
                    <td style="text-align: right ; width: 40%;">{{ number_format($item->dp_pemesanan, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table> --}}

        <table style="width: 40%; margin-left: auto; margin-right: 0; margin-top: 0px; margin-bottom: 2px;">
            <tbody>
                <tr>
                    <td style="text-align: left; width: 60%;"><strong>Sub Total</strong></td>
                    <td style="text-align: right; width: 40%;">{{ number_format($subTotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="text-align: left; width: 60%;"><strong>Diskon</strong></td>
                    <td style="text-align: right; width: 40%;">{{ number_format($totalDiskon, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="text-align: left; width: 60%;"><strong>Total Pesanan</strong></td>
                    <td style="text-align: right; width: 40%;">{{ number_format($grandTotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="text-align: left; width: 60%;"><strong>DP (Min.50%)</strong></td>
                    <td style="text-align: right; width: 40%;">{{ number_format($item->dp_pemesanan, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
        
        @if (!is_null($firstItem->pemesananproduk->catatan))
        <table style="width: 40%; margin-left: auto; margin-right: 0;">
            <tbody>
                <tr>
                    <td style="text-align: left; width: 100%;"><strong>Catatan</strong></td>
                </tr>
                <tr>
                    <td style="text-align: left; width: 40%;">{!! nl2br(e($firstItem->pemesananproduk->catatan)) ?? '-' !!}
                        
                    </td>
                </tr>
            </tbody>
        </table>
        @endif
        
        <div class="divider1"></div>
   
        </div>
    @endforeach


</body>
</html>
