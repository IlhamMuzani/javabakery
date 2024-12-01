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
            {{-- @if(isset($tokoData) && $tokoData->isNotEmpty())
                <span class="toko-name">Cabang: {{ $tokoData->first()->nama_toko }}</span><br>
                <span class="address">{{ $tokoData->first()->alamat }}</span><br>
            @endif --}}
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
                <th>Kode Produk</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permintaanProduks as $produkId => $tokoDetails)
                @php
                    $firstDetail = $tokoDetails->first();
                    $produk = $firstDetail['produk'] ?? null;
                    $totalJumlah = $tokoDetails->sum('jumlah');
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
                    <td>{{ $produk->kode_produk ?? 'N/A' }}</td>
                    <td>{{ $totalJumlah }}</td>
                </tr>
                <tr>
                    <td colspan="4">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Cabang</th>
                                    <th>Tanggal Permintaan</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tokoDetails as $tokoDetail)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tokoDetail['toko']->nama_toko }}</td>
                                        <td>{{ \Carbon\Carbon::parse($tokoDetail['tanggal_permintaan'])->format('d-m-Y H:i') }}</td>
                                        <td>{{ $tokoDetail['jumlah'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
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
                <th>Kode Produk</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemesananProduk as $produkId => $tokoDetails)
                @php
                    $firstDetail = $tokoDetails->first();
                    $produk = $firstDetail['produk'] ?? null;
                    $totalJumlah = $tokoDetails->sum('jumlah');
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
                    <td>{{ $produk->kode_produk ?? 'N/A' }}</td>
                    <td>{{ $totalJumlah }}</td>
                </tr>
                <tr>
                    <td colspan="4">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Cabang</th>
                                    <th>Kode Pemesanan</th>
                                    <th>Tanggal Kirim</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tokoDetails as $tokoDetail)
                                @foreach ($tokoDetail['detail'] as $detail)
                                        <tr>
                                            <td>{{ $loop->parent->iteration }}</td>
                                            <td>{{ $tokoDetail['toko']->nama_toko }}</td>
                                            <td>{{ $detail->pemesananProduk->kode_pemesanan }}</td>
                                            <td>{{ \Carbon\Carbon::parse($tokoDetail['tanggal_kirim'])->format('d-m-Y H:i') }}</td>
                                            <td>{{ $detail->jumlah }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

