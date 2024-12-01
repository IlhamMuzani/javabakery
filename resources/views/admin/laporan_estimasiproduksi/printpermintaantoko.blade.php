<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Estimasi Permintaan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        .logo img {
            width: 150px;
            height: 75px;
            margin-top: 1px;
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
            font-size: 9px; /* Menetapkan ukuran font kecil untuk tabel */
        }
        th, td {
            border: 1px solid #000; /* Menetapkan warna border hitam untuk tabel */
            padding: 4px; /* Menyesuaikan padding agar lebih kecil */
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        .divider {
            border: 0.5px solid;
            margin-top: 3px;
            margin-bottom: 1px;
        }
        .divider1 {
        border-top: 2px dashed #000; /* Gaya garis putus-putus dengan warna hitam */
        margin: 20px 0; /* Jarak atas dan bawah divider */
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
    
        <h1 class="title2">LAPORAN ESTIMASI PERMINTAAN</h1>
    
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
    $tokoTerpilih = Request::get('toko_id'); // Ambil toko yang dipilih dari request
@endphp



@php
    $no = 1;
    $produkData = [];
    $totalPerToko = []; // Total untuk setiap toko
    $grandTotal = 0; // Total keseluruhan
@endphp

@foreach ($permintaanProduk as $permintaan)
    @foreach ($permintaan->detailpermintaanproduks as $detail)
        @php
            $produk = $detail->produk;
            $klasifikasi = $produk->klasifikasi;
            $subklasifikasi = $klasifikasi->subklasifikasi;

            // Total per toko
            $totalPerToko[$detail->toko_id] = ($totalPerToko[$detail->toko_id] ?? 0) + $detail->jumlah;

            // Data produk
            $produkData[$detail->produk_id]['produk'] = $detail->produk->nama_produk;
            $produkData[$detail->produk_id]['kode_lama'] = $detail->produk->kode_lama;
            $produkData[$detail->produk_id]['kode_permintaan'] = $permintaan->kode_permintaan;
            $produkData[$detail->produk_id]['kategori'] = $klasifikasi->nama ?? '-';
            $produkData[$detail->produk_id]['total'] = ($produkData[$detail->produk_id]['total'] ?? 0) + $detail->jumlah;
            $produkData[$detail->produk_id]['detail'][$detail->toko_id] = ($produkData[$detail->produk_id]['detail'][$detail->toko_id] ?? 0) + $detail->jumlah;

            // Total keseluruhan
            $grandTotal += $detail->jumlah;
        @endphp
    @endforeach
@endforeach

@php
    $kategoriSebelumnya = null;
    $kategoriTotal = []; // Total untuk setiap kategori
@endphp

@foreach ($produkData as $produkId => $data)
    @if ($kategoriSebelumnya !== $data['kategori'])
        @if (!is_null($kategoriSebelumnya))
            <!-- Tampilkan total untuk kategori sebelumnya -->
            <tr>
                <th colspan="3" class="text-center">Total Kategori</th>
                @foreach ($tokoData as $toko)
                    <th class="text-center">{{ $kategoriTotal[$kategoriSebelumnya][$toko->id] ?? 0 }}</th>
                @endforeach
                @if (Request::get('toko_id') == '')
                    <th class="text-center">{{ array_sum($kategoriTotal[$kategoriSebelumnya]) }}</th>
                @endif
            </tr>
            </tbody></table> <br>

            <div class="divider1"></div>
        @endif

        <!-- Judul kategori dan mulai tabel baru -->
        <h2>{{ $data['kategori'] }}</h2>
        <table>
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th>Kode Produk</th>
                    <th>Produk</th>
                    @foreach ($tokoData as $toko)
                        <th>{{ $toko->nama_toko }}</th>
                    @endforeach
                    @if (Request::get('toko_id') == '')
                        <th>Total</th> <!-- Tampilkan total jika semua toko dipilih -->
                    @endif
                </tr>
            </thead>
            <tbody>
        @php
            $no = 1;
            $kategoriTotal[$data['kategori']] = []; // Reset total per kategori
        @endphp
    @endif

    <tr>
        <td class="text-center">{{ $no++ }}</td>
        <td>{{ $data['kode_lama'] }}</td>
        <td>{{ $data['produk'] }}</td>
        @foreach ($tokoData as $toko)
            @php
                $jumlahToko = $data['detail'][$toko->id] ?? 0;
                $kategoriTotal[$data['kategori']][$toko->id] = ($kategoriTotal[$data['kategori']][$toko->id] ?? 0) + $jumlahToko;
            @endphp
            <td class="text-center">{{ $jumlahToko }}</td>
        @endforeach
        @if (Request::get('toko_id') == '')
            <td class="text-center">{{ $data['total'] }}</td> <!-- Tampilkan total hanya jika semua toko dipilih -->
        @endif
    </tr>

    @php
        $kategoriSebelumnya = $data['kategori']; // Simpan kategori saat ini sebagai kategori sebelumnya
    @endphp
@endforeach

@if (!is_null($kategoriSebelumnya))
    <!-- Tampilkan total untuk kategori terakhir -->
    <tr>
        <th colspan="3" class="text-center">Total Kategori</th>
        @foreach ($tokoData as $toko)
            <th class="text-center">{{ $kategoriTotal[$kategoriSebelumnya][$toko->id] ?? 0 }}</th>
        @endforeach
        @if (Request::get('toko_id') == '')
            <th class="text-center">{{ array_sum($kategoriTotal[$kategoriSebelumnya]) }}</th>
        @endif
    </tr>
    </tbody></table> <!-- Tutup tabel terakhir -->

    <!-- Divider garis putus-putus sebagai pemisah antar tabel -->
    <div class="divider"></div>
@endif

<!-- Total keseluruhan di bagian footer jika semua toko dipilih -->
@if (Request::get('toko_id') == '')
    <tfoot>
        <tr>
            <th colspan="3" class="text-center">Total Keseluruhan</th>
            @foreach ($tokoData as $toko)
                <th class="text-center">{{ $totalPerToko[$toko->id] ?? 0 }}</th>
            @endforeach
            <th class="text-center">{{ $grandTotal }}</th> <!-- Total keseluruhan -->
        </tr>
    </tfoot>
@endif




</body>
</html>


