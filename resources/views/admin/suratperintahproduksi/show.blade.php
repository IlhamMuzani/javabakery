<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Permintaan Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            padding-bottom: 40px;
        }
        .container {
            width: 80%;
            margin: 0 auto;
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
            font-size: 24px;
            font-weight: bold;
            margin-top: 40px;
        }
        .tanggal {
            text-align: left;
            font-size: 12px;
            font-weight: bold;
            margin-top: 20px;
        }
        .section-title {
            margin-top: 30px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 16px;
            text-align: left;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 6px;
            border: 1px solid #ccc;
            text-align: left;
            width: 20%; /* Atur lebar kolom ke nilai persentase yang sesuai */
        }
        th {
            background-color: #f2f2f2;
        }
        table th:nth-child(1),
        table td:nth-child(1) { width: 10%; } /* Lebar untuk kolom No */

        table th:nth-child(2),
        table td:nth-child(2) { width: 25%; } /* Lebar untuk kolom Divisi */

        table th:nth-child(3),
        table td:nth-child(3) { width: 25%; } /* Lebar untuk kolom Kode Produk */

        table th:nth-child(4),
        table td:nth-child(4) { width: 30%; } /* Lebar untuk kolom Produk */

        table th:nth-child(5),
        table td:nth-child(5) { width: 10%; } /* Lebar untuk kolom Jumlah */
        .signature-container {
            margin-top: 60px;
            text-align: center;
        }
        .signature {
            display: inline-block;
            margin: 0 30px;
            text-align: center;
        }
        .signature p {
            margin: 0;
        }
        .row p {
            margin: 0;
        }
        .total-row {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Kop Surat -->
        <div class="header">
            <div class="logo">
                <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
            </div>
            <div>
                <span class="title">PT JAVA BAKERY</span><br>
                @if($toko)
                <span class="toko-name">Cabang: {{ $toko->nama_toko }}</span><br>
                <span class="address">{{$toko->alamat}}</span><br>
                @endif

            </div>
            <br>
            <hr class="divider">
        </div>

        <!-- Judul Surat -->
        <div class="change-header">SURAT ESTIMASI PRODUKSI</div>
        <div>
            {{-- <p>
                <span style="min-width: 100px; display: inline-flex; align-items: center;"><strong>No Estimasi</strong></span>
                <span style="min-width: 50px; display: inline-flex; align-items: center;">: {{ $permintaanProduk->kode_estimasi }}</span>
            </p>
            <p>
                <span style="min-width: 100px; display: inline-flex; align-items: center;"><strong>Tanggal</strong> </span>
                <span style="min-width: 50px; display: inline-flex; align-items: center;">: {{ $permintaanProduk->created_at->format('d-m-Y') }}</span>
            </p> --}}
          
        </div>
        @foreach ($produkByDivisi as $divisi => $produks)
            <div class="section-title">{{ $divisi }}</div>
            
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Divisi</th>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
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
                            <td>{{ $detail->produk->klasifikasi->nama }}</td> <!-- Divisi dari Klasifikasi -->
                            <td>{{ $detail->produk->kode_lama }}</td>
                            <td>{{ $detail->produk->nama_produk }}</td>
                            <td style="text-align: right">{{ $detail->jumlah }}</td>
                        </tr>
                    @endforeach
                </tbody>

                {{-- Menampilkan total untuk divisi --}}
                <tfoot>
                    <tr class="total-row">
                        <td colspan="4">Total </td>
                        <td style="text-align: right">{{ $produks->sum('jumlah') }}</td>
                    </tr>
                </tfoot>
            </table><br>
        @endforeach

        <div class="d-flex justify-content-between">
            <div>
                <a href="{{ url('admin/estimasi_produksi') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Kembali
                </a>
            </div>
            <div>
                <a href="{{ route('estimasi_produksi.print', $permintaanProduk->id) }}" id="printButton" target="_blank" class="btn btn-primary btn-sm">
                    <i class="fas fa-print"></i> Cetak 
                </a>
            </div>
            
            <div>
                {{-- <a href="{{ url('toko_banjaran/permintaan-produk/' . $permintaanProduk->id . '/print') }}" id="printButton" target="_blank" class="btn btn-primary btn-sm">
                    <i class="fas fa-print"></i> Cetak 
                </a> --}}
            </div>
            
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</html>
