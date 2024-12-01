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
            font-size: 24px;
        }
        .header .address, .header .contact {
            font-size: 12px;
        }
        .change-header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9px; /* Menetapkan ukuran font kecil untuk tabel */
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        .total-row {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="change-header">LAPORAN ESTIMASI PRODUKSI</div>

    @if($tanggalAwal && $tanggalAkhir)
    <div class="date-range">
        Periode Tanggal: {{ \Carbon\Carbon::parse($tanggalAwal)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}
    </div>
    @endif

    <!-- Tabel Pesanan -->
    @if($tableType == 'pemesanan')
    <h3>Atas Pesanan</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Divisi</th>
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
            @php
                $totalBanjaran = $totalTegal = $totalSlawi = $totalPemalang = $totalBumiayu = $totalCilacap = $totalSemua = 0;
            @endphp
            @foreach($pemesananProduk as $produkId => $tokoDetails)
                @php
                    $produk = $tokoDetails->first()['produk'];
                    $klasifikasi = $produk->klasifikasi->nama ?? 'Tidak Ditemukan';
                    $totalJumlah = 0;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $klasifikasi }}</td>
                    <td>{{ $produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
                    @foreach(['banjaran', 'tegal', 'slawi', 'pemalang', 'bumiayu', 'cilacap'] as $store)
                        @php
                            $jumlah = $tokoDetails->filter(function ($detail) use ($store) {
                                return strtolower($detail['toko']->nama_toko) === $store;
                            })->sum('jumlah');
                            $totalJumlah += $jumlah;
                            ${'total' . ucfirst($store)} += $jumlah;
                        @endphp
                        <td style="text-align: right">{{ $jumlah }}</td>
                    @endforeach
                    <td style="text-align: right">{{ $totalJumlah }}</td>
                    @php $totalSemua += $totalJumlah; @endphp
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3">Total</td>
                <td style="text-align: right">{{ $totalBanjaran }}</td>
                <td style="text-align: right">{{ $totalTegal }}</td>
                <td style="text-align: right">{{ $totalSlawi }}</td>
                <td style="text-align: right">{{ $totalPemalang }}</td>
                <td style="text-align: right">{{ $totalBumiayu }}</td>
                <td style="text-align: right">{{ $totalCilacap }}</td>
                <td style="text-align: right">{{ $totalSemua }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Tabel Permintaan -->
    @if($tableType == 'permintaan')
    <h3>Atas Permintaan</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Divisi</th>
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
            @php
                $totalBanjaran = $totalTegal = $totalSlawi = $totalPemalang = $totalBumiayu = $totalCilacap = $totalSemua = 0;
            @endphp
            @foreach($permintaanProduks as $produkId => $tokoDetails)
                @php
                    $produk = $tokoDetails->first()['produk'];
                    $klasifikasi = $produk->klasifikasi->nama ?? 'Tidak Ditemukan';
                    $totalJumlah = 0;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $klasifikasi }}</td>
                    <td>{{ $produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
                    @foreach(['banjaran', 'tegal', 'slawi', 'pemalang', 'bumiayu', 'cilacap'] as $store)
                        @php
                            $jumlah = $tokoDetails->filter(function ($detail) use ($store) {
                                return strtolower($detail['toko']->nama_toko) === $store;
                            })->sum('jumlah');
                            $totalJumlah += $jumlah;
                            ${'total' . ucfirst($store)} += $jumlah;
                        @endphp
                        <td style="text-align: right">{{ $jumlah }}</td>
                    @endforeach
                    <td style="text-align: right">{{ $totalJumlah }}</td>
                    @php $totalSemua += $totalJumlah; @endphp
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3">Total</td>
                <td style="text-align: right">{{ $totalBanjaran }}</td>
                <td style="text-align: right">{{ $totalTegal }}</td>
                <td style="text-align: right">{{ $totalSlawi }}</td>
                <td style="text-align: right">{{ $totalPemalang }}</td>
                <td style="text-align: right">{{ $totalBumiayu }}</td>
                <td style="text-align: right">{{ $totalCilacap }}</td>
                <td style="text-align: right">{{ $totalSemua }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Tabel Gabungan Pesanan dan Permintaan -->
    @if($tableType == 'all')
    <h3>Atas Permintaan dan Atas Pesanan</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Divisi</th>
                <th>Produk</th>
                @foreach(['Banjaran', 'Tegal', 'Slawi', 'Pemalang', 'Bumiayu', 'Cilacap'] as $store)
                    <th colspan="2">{{ $store }}</th>
                @endforeach
                <th>Total</th>
            </tr>
            <tr>
                <th colspan="3"></th>
                @foreach(['Banjaran', 'Tegal', 'Slawi', 'Pemalang', 'Bumiayu', 'Cilacap'] as $store)
                    <th>Pes</th>
                    <th>Stok</th>
                @endforeach
                <th></th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalBanjaranPes = $totalBanjaranStok = $totalTegalPes = $totalTegalStok = $totalSlawiPes = $totalSlawiStok = 0;
                $totalPemalangPes = $totalPemalangStok = $totalBumiayuPes = $totalBumiayuStok = $totalCilacapPes = $totalCilacapStok = 0;
                $totalSemua = 0;
            @endphp
           @foreach($combinedData as $produkId => $tokoDetails)
           @php
               $produk = $tokoDetails->first()['produk'];
               $klasifikasi = $produk->klasifikasi->nama ?? 'Tidak Ditemukan';
               $totalPesanan = 0;
               $totalPermintaan = 0;
           @endphp
           <tr>
               <td>{{ $loop->iteration }}</td>
               <td>{{ $klasifikasi }}</td>
               <td>{{ $produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
               @foreach(['banjaran', 'tegal', 'slawi', 'pemalang', 'bumiayu', 'cilacap'] as $store)
                   @php
                       $pesanan = $tokoDetails->filter(function ($detail) use ($store) {
                           return strtolower($detail['toko']->nama_toko) === $store && isset($detail['pesanan']);
                       })->sum('pesanan');
                       
                       $permintaan = $tokoDetails->filter(function ($detail) use ($store) {
                           return strtolower($detail['toko']->nama_toko) === $store && isset($detail['permintaan']);
                       })->sum('permintaan');
                       
                       $totalPesanan += $pesanan;
                       $totalPermintaan += $permintaan;
       
                       // Update total per toko
                       ${'total' . ucfirst($store) . 'Pes'} += $pesanan;
                       ${'total' . ucfirst($store) . 'Stok'} += $permintaan;
                   @endphp
                   <td style="text-align: right">{{ $pesanan }}</td>
                   <td style="text-align: right">{{ $permintaan }}</td>
               @endforeach
               <td style="text-align: right">{{ $totalPesanan + $totalPermintaan }}</td>
           </tr>
       @endforeach

            <tr class="total-row">
                <td colspan="3">Total</td>
                <td style="text-align: right">{{ $totalBanjaranPes }}</td>
                <td style="text-align: right">{{ $totalBanjaranStok }}</td>
                <td style="text-align: right">{{ $totalTegalPes }}</td>
                <td style="text-align: right">{{ $totalTegalStok }}</td>
                <td style="text-align: right">{{ $totalSlawiPes }}</td>
                <td style="text-align: right">{{ $totalSlawiStok }}</td>
                <td style="text-align: right">{{ $totalPemalangPes }}</td>
                <td style="text-align: right">{{ $totalPemalangStok }}</td>
                <td style="text-align: right">{{ $totalBumiayuPes }}</td>
                <td style="text-align: right">{{ $totalBumiayuStok }}</td>
                <td style="text-align: right">{{ $totalCilacapPes }}</td>
                <td style="text-align: right">{{ $totalCilacapStok }}</td>
                <td style="text-align: right">{{ $totalSemua }}</td>
            </tr>
        </tbody>
    </table>
    @endif

</body>
</html>
