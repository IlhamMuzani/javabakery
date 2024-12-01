<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Permintaan Produk</title>
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
        .header {
            text-align: center;
            margin-top: 20px;
        }
        .header .title {
            font-weight: bold;
            font-size: 28px;
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
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
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
    </style>
</head>
<body>
    <!-- Kop Surat -->
    <div class="header">
        <div class="logo">
            <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
        </div>
        <div>
            <span class="title">PT JAVA BAKERY</span><br>
            @if(isset($tokoData) && $tokoData->isNotEmpty())
                <span class="toko-name">Cabang: {{ $tokoData->first()->nama_toko }}</span><br>
                <span class="address">{{ $tokoData->first()->alamat }}</span><br>
            @endif
        </div>
        <hr class="divider">
    </div>

    <!-- Judul Surat -->
    <div class="change-header">LAPORAN PERMINTAAN PRODUK</div>
    <p>Periode: {{ $tanggal_permintaan ? date('d-m-Y', strtotime($tanggal_permintaan)) : 'Tidak ditentukan' }} - {{ $tanggal_akhir ? date('d-m-Y', strtotime($tanggal_akhir)) : 'Tidak ditentukan' }}</p>

    @foreach ($produkByTokoAndDivisi as $toko => $divisiData)
    <!-- Pembatas Cabang -->
    <hr class="branch-divider">
    <div>
        {{-- <p>
            <span style="min-width: 100px; display: inline-flex; align-items: center;"><strong>Kode Permintaan</strong></span>
            <span style="min-width: 50px; display: inline-flex; align-items: center;">: {{ $permintaanProduk->first()->kode_permintaan }}</span>
        </p> --}}
        <p>
            <span style="min-width: 100px; display: inline-flex; align-items: center;"><strong>Cabang</strong></span>
            <span style="min-width: 50px; display: inline-flex; align-items: center;">: {{ $toko }}</span>
        </p>
    </div>
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
                        <td>{{ $detail['kode_produk'] }}</td>
                        <td>{{ $detail['nama_produk'] }}</td>
                        <td>{{ $detail['jumlah'] }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="4">Total</td>
                    <td>{{ collect($produks)->sum('jumlah') }}</td>
                </tr>
            </tfoot>
        </table><br>
    @endforeach
@endforeach

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</html>



