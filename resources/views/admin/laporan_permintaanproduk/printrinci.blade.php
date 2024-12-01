<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Permintaan Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            padding-bottom: 50px; /* Menambahkan jarak di bagian bawah halaman */
        }
        .logo img {
            width: 150px;
            height: 77px;
        } 
        .header .address, .header .contact {
            font-size: 12px;
        }
        .divider {
            border: 0.5px solid #000;
            margin-top: 10px;
            margin-bottom: 10px;
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
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 10px;
        }
        th, td {
            border: 1px solid black;
            padding: 4px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
        p {
            margin: 5px 0;
        }
        .branch-divider {
            border: 1px dashed #000;
            margin: 20px 0;
        }
        .total-row td {
            font-weight: bold;
        }
        .logo img {
            width: 100px;
            height: 60px;
        }
    </style>
</head>
<body>
    <!-- Judul Surat -->
    <div class="header">
        <div class="logo">
            <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
        </div>
        <h1 class="title">PT JAVA BAKERY FACTORY</h1>
        <p class="title1">Cabang: {{ strtoupper($branchName) }}</p>
        <div class="divider"></div>
    
        <h1 class="title2">LAPORAN PERMINTAAN PRODUK RINCI</h1>
    
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

    @foreach ($produkByKodePermintaan as $kodePermintaan => $tokoDivisiData)
    <!-- Pembatas Kode Permintaan -->
    <hr class="branch-divider">
    <div>
        <p>
            <span style="min-width: 100px; display: inline-flex; align-items: center;"><strong>Kode Permintaan</strong></span>
            <span style="min-width: 50px; display: inline-flex; align-items: center;">: {{ $kodePermintaan }}</span>
        </p>
    </div>

    @foreach ($tokoDivisiData as $toko => $divisiData)
        <!-- Pembatas Cabang -->
        {{-- <div>
            <p>
                <span style="min-width: 100px; display: inline-flex; align-items: center;"><strong>Cabang</strong></span>
                <span style="min-width: 50px; display: inline-flex; align-items: center;">: {{ $toko }}</span>
            </p>
        </div> --}}
        @foreach ($divisiData as $divisi => $produks)
            <div>
                <p>
                    <span style="min-width: 100px; display: inline-flex; align-items: center;"><strong>Divisi</strong></span>
                    <span style="min-width: 50px; display: inline-flex; align-items: center;">: {{ $divisi }}</span>
                </p>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Kode Produk</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $no = 1; 
                    @endphp
                    @foreach ($produks as $detail)
                        <tr>
                            <td>{{ $no++ }}</td> 
                            <td>{{ $detail['subklasifikasi'] }}</td>
                            <td>{{ $detail['kode_lama'] }}</td>
                            <td>{{ $detail['nama_produk'] }}</td>
                            <td style="text-align: right">{{ $detail['jumlah'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="4">Total</td>
                        <td style="text-align: right">{{ collect($produks)->sum('jumlah') }}</td>
                    </tr>
                </tfoot>
            </table><br>
        @endforeach
    @endforeach
    @endforeach
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</html>

