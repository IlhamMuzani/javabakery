<!DOCTYPE html>
<html>
<head>
    <title>Laporan Depoit</title>
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
            margin: 0;
            padding: 0;
        }
        table, th, td {
            border: 1px solid black;
            padding: 5px;
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
        .admin-info {
            text-align: right;
            margin-top: 10px;
            font-size: 12px;
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
    
        <h1 class="title2">LAPORAN PEMESANAN PRODUK</h1>
    
        @php
            \Carbon\Carbon::setLocale('id'); // Set locale ke Bahasa Indonesia
            $formattedStartDate = $startDate ? \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') : 'Tidak ada';
            $formattedEndDate = $endDate ? \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') : 'Tidak ada';
            $currentDateTime = \Carbon\Carbon::now()->translatedFormat('d F Y H:i');
        @endphp
    
        <p class="period">
            @if ($startDate && !$endDate)
                Periode: {{ $formattedStartDate }}
            @elseif ($startDate && $endDate)
                Periode: {{ $formattedStartDate }} s/d {{ $formattedEndDate }}
            @else
                Periode: Tidak ada tanggal yang diteruskan.
            @endif
        </p>
    
        <p class="period right-align" style="font-size: 10px; position: absolute; top: 0; right: 0; margin: 10px;">
            {{ $currentDateTime }}
        </p>
    </div>
    
    <!-- Tabel utama -->
    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th style="text-align: center">No Pemesanan</th>
                <th style="text-align: center">Nama Kasir</th>
                <th style="text-align: center">Nama Pelanggan</th>
                <th style="text-align: center">Metode Pembayaran</th>
                <th style="text-align: center">Fee Pemesanan</th>
                <th style="text-align: center">Total Pemesanan</th>
            </tr>
        </thead>
        <tbody style="font-size: 10px;">
            @php
                $grandTotal = 0;
                $grandTotalFee = 0;
                $totalDeposit = 0;
            @endphp
            @foreach ($inquery as $item)
                @php
                    // Konversi sub_total ke angka murni
                    $sub_total = preg_replace('/[^\d]/', '', $item->sub_total); 
                    $sub_total = (float) $sub_total; // Pastikan nilai float
                    $grandTotal += $sub_total;
        
                    // Konversi total_fee ke angka murni
                    $total_fee = preg_replace('/[^\d]/', '', $item->total_fee ?? 0);
                    $total_fee = (float) $total_fee;
                    $grandTotalFee += $total_fee;
        
                    // Mengambil nilai deposit jika ada
                    $deposit = $item->dppemesanan->dp_pemesanan ?? 0;
                    $deposit = (float) preg_replace('/[^\d]/', '', $deposit);
                    $totalDeposit += $deposit;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->kode_pemesanan }}</td>
                    <td>{{ $item->kasir ?? '-' }}</td>
                    <td>
                        @if($item->kode_pelanggan)
                            {{ $item->kode_pelanggan }} / {{ $item->nama_pelanggan }}
                        @else
                            Non Member
                        @endif
                    </td>
                   
                    <td>{{ $item->metodepembayaran->nama_metode ?? 'Tunai' }}</td>
                    <td style="text-align: right">
                        @if ($total_fee == 0)
                            -
                        @else
                            {{ number_format($total_fee, 0, ',', '.') }}
                        @endif
                    </td>
                    <td style="text-align: right">
                        {{ number_format($sub_total, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        
    </table>
    
    <!-- Tabel total penjualan fee, total deposit, dan grand total -->
    {{-- <table style="width: 60%; margin-left: auto; margin-right: 0; background-color: rgb(248, 248, 6);" border="0">
        <tbody>
            <!-- Baris tanpa garis tabel -->
            <tr style="border: none;">
                <td style="text-align: right; border: none;">Total Penjualan :</td>
                <td style="text-align: right; font-weight: bold; border: none;">{{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
            <tr style="border: none;">
                <td style="text-align: right; width: 60%; border: none;">Fee Penjualan :</td>
                <td style="text-align: right; font-weight: bold; width: 40%; border: none;">{{ number_format($grandTotalFee, 0, ',', '.') }}</td>
            </tr>
            <!-- Baris dengan garis pembatas -->
            <tr style="border-top: 2px solid black;">
                <td style="text-align: right; border: none;">Sub Total Penjualan :</td>
                <td style="text-align: right; font-weight: bold; border: none;">{{ number_format($grandTotal - $grandTotalFee, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="text-align: right; border: none;">Pengambilan Deposit :</td>
                <td style="text-align: right; font-weight: bold; border: none; border-bottom: 1px solid black;">{{ number_format($totalDeposit, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="text-align: right; border: none;">Total :</td>
                <td style="text-align: right; font-weight: bold; border: none;" >{{ 'Rp. ' . number_format($grandTotal - $grandTotalFee - $totalDeposit, 0, ',', '.') }}</td>
            </tr>
            
        </tbody>
    </table> --}}
    
     <!-- Informasi Admin Toko -->
     <div class="admin-info">
        <p><strong>Admin</strong></p><br><br>
        <p>{{ ucfirst(auth()->user()->karyawan->nama_lengkap) }}</p>

    </div>
    
</body>
</html>
