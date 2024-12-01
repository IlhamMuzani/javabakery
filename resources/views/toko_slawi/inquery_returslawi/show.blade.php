<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Retur Produk</title>
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
            display: flex;
            margin-top: 20px;
            border-bottom: 1px solid #000;
        }
        .header .col {
            padding: 0 10px;
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
        }
        th {
            background-color: #f2f2f2;
        }
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
        <div class="header row">
            <div class="col-4 text-left">
                <div class="title">JAVA BAKERY</div>
                <p>Cabang : {{ $firstItem->toko->nama_toko ?? 'Nama toko tidak tersedia' }}</p>
                <p>{{ $firstItem->toko->alamat ?? 'Alamat tidak tersedia' }}</p>
            </div>
            <div class="col-4 text-center">
              
            </div>
            <div class="col-4 text-right">
                <div class="logo">
                    {{-- <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY"> --}}
                </div>
                <div>
                    <span class="title">PT JAVA BAKERY</span><br>
                    <p>Jl. HOS. Cokro Aminoto No.5, Kagok, Kec. Slawi, Kabupaten Tegal, Jawa Tengah 52411</p><br>
                
                </div>
            </div>
        </div>
        {{-- <hr class="divider"> --}}

        <!-- Judul Surat -->
        <div class="change-header">SURAT RETUR BARANG JADI</div>

        <!-- Informasi Permintaan -->
        <div>
            <p style="margin-bottom: 2px;">
                <span style="min-width: 100px; display: inline-flex; align-items: center;"><strong>Kode Pengiriman</strong></span>
                <span style="min-width: 50px; display: inline-flex; align-items: center;">: {{ $pengirimanBarangJadi->first()->kode_retur }}</span>
            </p>
            <p>
                <span style="min-width: 100px; display: inline-flex; align-items: center;"><strong>Tanggal</strong> </span>
                <span style="min-width: 50px; display: inline-flex; align-items: center;">: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</span>
            </p>
        </div>

        <!-- Detail Produk -->
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Produk</th>
                    <th>Kategori</th>
                    <th>Produk</th>
                    <th>Keterangan</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengirimanBarangJadi as $key => $detail)
                <tr>
                    <td>{{ $key + 1 }}</td> 
                    <td>{{ $detail->produk->kode_lama }}</td>
                    <td>{{ $detail->produk->subklasifikasi->nama }}</td>
                    <td>{{ $detail->produk->nama_produk }}</td>
                    <td>{{ $detail->keterangan }}</td>
                    <td>{{ $detail->jumlah }}</td>
                </tr>
                @endforeach
            </tbody>
        </table><br>

        <div class="d-flex justify-content-between">
            <div>
                <a href="{{ url('toko_slawi/inquery_returslawi') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Kembali
                </a>
            </div>
            <div>
                <a href="{{ route('inquery_returslawi.print', $pengirimanBarangJadi->first()->id) }}" id="printButton" target="_blank" class="btn btn-primary btn-sm">
                    <i class="fas fa-print"></i> Cetak 
                </a>
            </div>  
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>

