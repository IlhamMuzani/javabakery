<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pemesanan Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            text-align: center;
            margin-bottom: 20px;
        }
        h1 {
            margin: 0;
            font-size: 24px;
        }
        .text {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12px;
        }
        .period {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 4px;
            text-align: left;
        }
        th {
            background-color: white;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .signature {
            margin-top: 40px;
            text-align: center;
        }
        .signature table {
            width: 100%;
        }
        .signature .separator {
            padding-top: 20px;
        }

        p {
        font-size: 12px;
        margin: 0 0 10px 0;
        }

        p span {
            font-size: 12px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>LAPORAN PEMESANAN PRODUK</h1>
    </div>
    <div class="text">
        @php
            \Carbon\Carbon::setLocale('id'); // Set locale ke bahasa Indonesia
    
            $formattedStartDate = \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y');
            $formattedEndDate = \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y');
            $currentDateTime = \Carbon\Carbon::now()->translatedFormat('d F Y H:i');
        @endphp
    
        @if ($startDate && $endDate)
            <p class="period">
                Periode: {{ $formattedStartDate }} s/d {{ $formattedEndDate }}
            </p>
            <p style="text-align: right;">{{ $currentDateTime }}</p>
        @else
            <p class="period">
                Periode: Tidak ada tanggal awal dan akhir yang diteruskan.
            </p>
            <p style="text-align: right;">{{ $currentDateTime }}</p>
        @endif
    </div>

    @if ($toko_id == '0')
        <!-- Tabel Global -->
        <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 10px">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th>Divisi</th>
                    <th>Tanggal Kirim/Ambil</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
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
                    $no = 1;
                    $totalSubtotal = 0;
                @endphp
                @foreach ($groupedData as $detail)
                @php
                    $benjaran = $detail['benjaran'] ?? 0;
                    $tegal = $detail['tegal'] ?? 0;
                    $slawi = $detail['slawi'] ?? 0;
                    $pemalang = $detail['pemalang'] ?? 0;
                    $bumiayu = $detail['bumiayu'] ?? 0;
                    $cilacap = $detail['cilacap'] ?? 0;
                    $subtotal = $benjaran + $tegal + $slawi + $pemalang + $bumiayu + $cilacap;
                    $totalSubtotal += $subtotal;
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $detail['klasifikasi'] ?? 'Tidak ada' }}</td>
                    <td>{{ $detail['tanggal_pemesanan'] ?? '-' }}</td>
                    <td>{{ $detail['kode_produk'] ?? 'Tidak ada' }}</td>
                    <td>{{ $detail['nama_produk'] ?? 'Tidak ada' }}</td>
                    <td>{{ number_format($benjaran, 0, ',', '.') }}</td>
                    <td>{{ number_format($tegal, 0, ',', '.') }}</td>
                    <td>{{ number_format($slawi, 0, ',', '.') }}</td>
                    <td>{{ number_format($pemalang, 0, ',', '.') }}</td>
                    <td>{{ number_format($bumiayu, 0, ',', '.') }}</td>
                    <td>{{ number_format($cilacap, 0, ',', '.') }}</td>
                    <td>{{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            
            

            </tbody>
        </table>


    @elseif ($toko_id == '1')
    <!-- Tabel Toko Benjaran -->
    @foreach ($groupedData as $klasifikasi => $data)
    <p>Divisi: {{ $klasifikasi }}
        @php
            $tanggalKirim = isset($data[0]['tanggal_kirim']) ? $data[0]['tanggal_kirim'] : 'Tanggal tidak tersedia';
            $formattedTanggalKirim = ($tanggalKirim != 'Tanggal tidak tersedia') 
                ? \Carbon\Carbon::parse($tanggalKirim)->translatedFormat('d F Y') 
                : $tanggalKirim;
        @endphp
        <span style="float: right;">Tanggal Kirim: {{ $formattedTanggalKirim }}</span>
        </p>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Tanggal Pemesanan</th>
                <th>Kode Pemesanan</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Qty</th>
                {{-- <th>Catatan</th> --}}
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $currentKodeProduk = null;
                $totalPerProduk = 0;
            @endphp
            @foreach ($data as $detail)
                @if ($currentKodeProduk && $currentKodeProduk != $detail['kode_produk'])
                    <tr>
                        <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                        <td>{{ $totalPerProduk }}</td>
                        <td colspan="2"></td>
                    </tr>
                    @php
                        $totalPerProduk = 0;
                    @endphp
                @endif
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $detail['tanggal_pemesanan'] ?? '-' }}</td>
                    <td>{{ $detail['kode_pemesanan'] ?? '-' }}</td>
                    <td>{{ $detail['kode_produk'] }}</td>
                    <td>{{ $detail['nama_produk'] }}</td>
                    <td>{{ $detail['benjaran'] ?? '0' }}</td>
                    {{-- <td>{{ $detail['catatanproduk'] ?? '-' }}</td> --}}
                </tr>
                @php
                    $currentKodeProduk = $detail['kode_produk'];
                    $totalPerProduk += $detail['benjaran'];
                @endphp
            @endforeach
            @if ($currentKodeProduk)
                <tr>
                    <td colspan="5" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                    <td>{{ $totalPerProduk }}</td>
                </tr>
            @endif
        </tbody>
    </table>
    <hr class="divider">
@endforeach

@endif



    @if ($toko_id == '2')
    <!-- Tabel Toko Tegal -->
    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 12px">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Tanggal Pemesanan</th>
                <th>Divisi</th>
                <th>Kode Pemesanan</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Toko Tegal</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $currentKodeProduk = null;
                $totalPerProduk = 0;
            @endphp
            @foreach ($groupedData as $detail)
                @if ($currentKodeProduk && $currentKodeProduk != $detail['kode_produk'])
                    <tr>
                        <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                        <td>{{ $totalPerProduk }}</td>
                        <td colspan="2"></td>
                    </tr>
                    @php
                        $totalPerProduk = 0;
                    @endphp
                @endif
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $detail['tanggal_pemesanan'] ?? '-' }}</td>
                    <td>{{ $detail['klasifikasi'] }}</td>
                    <td>{{ $detail['kode_pemesanan'] ?? '-' }}</td>
                    <td>{{ $detail['kode_produk'] }}</td>
                    <td>{{ $detail['nama_produk'] }}</td>
                    <td>{{ $detail['tegal'] }}</td>
                    <td>{{ $detail['catatanproduk'] ?? '-' }}</td>
                </tr>
                @php
                    $currentKodeProduk = $detail['kode_produk'];
                    $totalPerProduk += $detail['tegal'];
                @endphp
            @endforeach
            @if ($currentKodeProduk)
                <tr>
                    <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                    <td>{{ $totalPerProduk }}</td>
                    <td colspan="2"></td>
                </tr>
            @endif
        </tbody>
    </table>
    @endif

    @if ($toko_id == '3')
    <!-- Tabel Toko Slawi -->
    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 12px">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Tanggal Pemesanan</th>
                <th>Divisi</th>
                <th>Kode Pemesanan</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Toko Slawi</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $currentKodeProduk = null;
                $totalPerProduk = 0;
            @endphp
            @foreach ($groupedData as $detail)
                @if ($currentKodeProduk && $currentKodeProduk != $detail['kode_produk'])
                    <tr>
                        <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                        <td>{{ $totalPerProduk }}</td>
                        <td colspan="2"></td>
                    </tr>
                    @php
                        $totalPerProduk = 0;
                    @endphp
                @endif
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $detail['tanggal_pemesanan'] ?? '-' }}</td>
                    <td>{{ $detail['klasifikasi'] }}</td>
                    <td>{{ $detail['kode_pemesanan'] ?? '-' }}</td>
                    <td>{{ $detail['kode_produk'] }}</td>
                    <td>{{ $detail['nama_produk'] }}</td>
                    <td>{{ $detail['slawi'] }}</td>
                    <td>{{ $detail['catatanproduk'] ?? '-' }}</td>
                </tr>
                @php
                    $currentKodeProduk = $detail['kode_produk'];
                    $totalPerProduk += $detail['slawi'];
                @endphp
            @endforeach
            @if ($currentKodeProduk)
                <tr>
                    <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                    <td>{{ $totalPerProduk }}</td>
                    <td colspan="2"></td>
                </tr>
            @endif
        </tbody>
    </table>
    @endif

    @if ($toko_id == '4')
    <!-- Tabel Toko Bumiayu -->
    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 12px">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Tanggal Pemesanan</th>
                <th>Divisi</th>
                <th>Kode Pemesanan</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Toko Bumiayu</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $currentKodeProduk = null;
                $totalPerProduk = 0;
            @endphp
            @foreach ($groupedData as $detail)
                @if ($currentKodeProduk && $currentKodeProduk != $detail['kode_produk'])
                    <tr>
                        <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                        <td>{{ $totalPerProduk }}</td>
                        <td colspan="2"></td>
                    </tr>
                    @php
                        $totalPerProduk = 0;
                    @endphp
                @endif
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $detail['tanggal_pemesanan'] ?? '-' }}</td>
                    <td>{{ $detail['klasifikasi'] }}</td>
                    <td>{{ $detail['kode_pemesanan'] ?? '-' }}</td>
                    <td>{{ $detail['kode_produk'] }}</td>
                    <td>{{ $detail['nama_produk'] }}</td>
                    <td>{{ $detail['bumiayu'] }}</td>
                    <td>{{ $detail['catatanproduk'] ?? '-' }}</td>
                </tr>
                @php
                    $currentKodeProduk = $detail['kode_produk'];
                    $totalPerProduk += $detail['bumiayu'];
                @endphp
            @endforeach
            @if ($currentKodeProduk)
                <tr>
                    <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                    <td>{{ $totalPerProduk }}</td>
                    <td colspan="2"></td>
                </tr>
            @endif
        </tbody>
    </table>
    @endif

    @if ($toko_id == '5')
    <!-- Tabel Toko Pemalang -->
    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 12px">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Tanggal Pemesanan</th>
                <th>Divisi</th>
                <th>Kode Pemesanan</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Toko Pemalang</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $currentKodeProduk = null;
                $totalPerProduk = 0;
            @endphp
            @foreach ($groupedData as $detail)
                @if ($currentKodeProduk && $currentKodeProduk != $detail['kode_produk'])
                    <tr>
                        <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                        <td>{{ $totalPerProduk }}</td>
                        <td colspan="2"></td>
                    </tr>
                    @php
                        $totalPerProduk = 0;
                    @endphp
                @endif
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $detail['tanggal_pemesanan'] ?? '-' }}</td>
                    <td>{{ $detail['klasifikasi'] }}</td>
                    <td>{{ $detail['kode_pemesanan'] ?? '-' }}</td>
                    <td>{{ $detail['kode_produk'] }}</td>
                    <td>{{ $detail['nama_produk'] }}</td>
                    <td>{{ $detail['pemalang'] }}</td>
                    <td>{{ $detail['catatanproduk'] ?? '-' }}</td>
                </tr>
                @php
                    $currentKodeProduk = $detail['kode_produk'];
                    $totalPerProduk += $detail['pemalang'];
                @endphp
            @endforeach
            @if ($currentKodeProduk)
                <tr>
                    <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                    <td>{{ $totalPerProduk }}</td>
                    <td colspan="2"></td>
                </tr>
            @endif
        </tbody>
    </table>
    @endif

    @if ($toko_id == '6')
    <!-- Tabel Toko Cilacap -->
    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 12px">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Tanggal Pemesanan</th>
                <th>Divisi</th>
                <th>Kode Pemesanan</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Toko Cilacap</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $currentKodeProduk = null;
                $totalPerProduk = 0;
            @endphp
            @foreach ($groupedData as $detail)
                @if ($currentKodeProduk && $currentKodeProduk != $detail['kode_produk'])
                    <tr>
                        <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                        <td>{{ $totalPerProduk }}</td>
                        <td colspan="2"></td>
                    </tr>
                    @php
                        $totalPerProduk = 0;
                    @endphp
                @endif
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $detail['tanggal_pemesanan'] ?? '-' }}</td>
                    <td>{{ $detail['klasifikasi'] }}</td>
                    <td>{{ $detail['kode_pemesanan'] ?? '-' }}</td>
                    <td>{{ $detail['kode_produk'] }}</td>
                    <td>{{ $detail['nama_produk'] }}</td>
                    <td>{{ $detail['cilacap'] }}</td>
                    <td>{{ $detail['catatanproduk'] ?? '-' }}</td>
                </tr>
                @php
                    $currentKodeProduk = $detail['kode_produk'];
                    $totalPerProduk += $detail['cilacap'];
                @endphp
            @endforeach
            @if ($currentKodeProduk)
                <tr>
                    <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                    <td>{{ $totalPerProduk }}</td>
                    <td colspan="2"></td>
                </tr>
            @endif
        </tbody>
    </table>
    @endif


    
</body>
</html>
