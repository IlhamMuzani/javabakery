<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Estimasi Produksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
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
        .change-header {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
        </div>
        <div>
            <span class="title">PT JAVA BAKERY FACTORY</span><br><br>
            <span class="address">JL. HOS COKRO AMINOTO NO 5 SLAWI TEGAL</span><br>
            <span class="contact">Telp / Fax, Email :</span>
        </div>
        <hr class="divider">
    </div>
    <div class="change-header">LAPORAN ESTIMASI PRODUKSI</div>

    <!-- Tabel Permintaan -->
    <h3>Permintaan Produk</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Banjaran</th>
                <th>Tegal</th>
                <th>Slawi</th>
                <th>Pemalang</th>
                <th>Bumiayu</th>
                <th>Cilacap</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permintaanProduks as $produkId => $tokoDetails)
                @php
                    $produk = $tokoDetails->first()['produk'];
                    $totalJumlah = 0;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
                    @foreach(['banjaran', 'tegal', 'slawi', 'pemalang', 'bumiayu', 'cilacap'] as $store)
                        @php
                            $jumlah = $tokoDetails->filter(function ($detail) use ($store) {
                                return strtolower($detail['toko']->nama_toko) === $store;
                            })->sum('jumlah');
                            $totalJumlah += $jumlah;
                        @endphp
                        <td>{{ $jumlah }}</td>
                    @endforeach
                    <td>{{ $totalJumlah }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tabel Pemesanan -->
    <h3>Pemesanan Produk</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Banjaran</th>
                <th>Tegal</th>
                <th>Slawi</th>
                <th>Pemalang</th>
                <th>Bumiayu</th>
                <th>Cilacap</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemesananProduk as $produkId => $tokoDetails)
                @php
                    $produk = $tokoDetails->first()['produk'];
                    $totalJumlah = 0;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
                    @foreach(['banjaran', 'tegal', 'slawi', 'pemalang', 'bumiayu', 'cilacap'] as $store)
                        @php
                            $jumlah = $tokoDetails->filter(function ($detail) use ($store) {
                                return strtolower($detail['toko']->nama_toko) === $store;
                            })->sum('jumlah');
                            $totalJumlah += $jumlah;
                        @endphp
                        <td>{{ $jumlah }}</td>
                    @endforeach
                    <td>{{ $totalJumlah }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
