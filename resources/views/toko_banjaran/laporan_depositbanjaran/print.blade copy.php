<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Deposit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            font-size: 10px;
        }
        th, td {
            padding: 4px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

    <h2 style="text-align: center; font-size: 28px;">LAPORAN DEPOSIT</h2>
    <div class="text-center">
        <p>
            @if ($startDate && $endDate)
                Periode: {{ $startDate }} s/d {{ $endDate }}<br>
                Cabang: {{ $branchName }}
            @else
                Periode: Tidak ada tanggal awal dan akhir yang diteruskan.<br>
                Cabang: {{ $branchName }}
            @endif
        </p>
        <p style="text-align: right; margin-top: -20px;">{{ $currentDateTime }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Deposit</th>
                <th>Tanggal Deposit</th>
                <th>Pelanggan</th>
                <th>Kasir</th>
                <th>Metode Bayar</th>
                <th>Fee Deposit</th>
                <th>Nominal Deposit</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inquery as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->kode_dppemesanan }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->pemesananproduk->tanggal_pemesanan)->format('d-m-Y') }}</td>
                    <td>
                        @if($item->pemesananproduk->kode_pelanggan)
                            {{ $item->pemesananproduk->kode_pelanggan }} / {{ $item->pemesananproduk->nama_pelanggan }}
                        @else
                            {{ $item->pemesananproduk->nama_pelanggan }}
                        @endif
                    </td>
                    <td>{{ $item->pemesananproduk->toko->nama_toko }}</td>
                    <td>
                        @if($item->pemesananproduk->metodePembayaran)
                            {{ $item->pemesananproduk->metodePembayaran->nama_metode }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $item->pemesananproduk->total_fee == 'Rp0' ? '-' : $item->pemesananproduk->total_fee }}</td>
                    <td style="text-align: right">{{number_format($item->dp_pemesanan, 0, ',', '.') }}</td>

                    <td>
                        @if($item->pelunasan)
                            <span style="color: green; ">Diambil</span>
                        @else
                            <span style="color: red;">Belum Diambil</span>
                        @endif
                    </td>  
                </tr>
            @endforeach
        </tbody>
    </table><br>

    <table style="width: 60%; margin-left: auto; margin-right: 0; background-color: rgb(248, 248, 6);" border="0">
        <tbody>
            <!-- Baris tanpa garis tabel -->
            <tr style="border: none;">
                <td style="text-align: right; border: none;">Total Deposit :</td>
                <td style="text-align: right">{{ $totalDeposit }}</td>

            </tr>
            <tr style="border: none;">
                <td style="text-align: right; width: 60%; border: none;">Fee Deposit :</td>
                <td style="text-align: right">{{ $totalFee }}</td>

            </tr>
            <!-- Baris dengan garis pembatas -->
            <tr style="border-top: 2px solid black;">
                <td style="text-align: right; border: none;">Sub Total  :</td>
                <td style="text-align: right">{{ $subTotal }}</td>

            </tr>
           
        </tbody>
    </table>
    
</body>

</html>
