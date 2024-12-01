<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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
            margin-top: 20px;
        }
        .change-header1 {
            text-align: center;
            font-size: 12px;
            margin-top: 10px;
        }
        .tanggal {
            text-align: left;
            font-size: 12px;
            font-weight: bold;
            margin-top: 20px;
        }
        .section-title {
            margin-top: 30px;
            margin-bottom: 0px;
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
            padding: 4px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: white;
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
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>
    
</head>
<body>
    <div class="container">
        <!-- Kop Surat -->
        <div class="header row">
            <div class="col-2 text-right">
                <div class="logo">
                    {{-- <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY"> --}}
                </div>
            </div>
    
            <div class="col-8 text-center">
                <div class="logo">
                    <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
                </div>
                <span class="title">PT JAVA BAKERY FACTORY</span><br>
                <p>Jl. HOS. Cokro Aminoto No.5, Kagok, Kec. Slawi, Kabupaten Tegal, Jawa Tengah 52411</p><br>
            </div>
        </div>
    
        <!-- Judul -->
        <div class="change-header">SURAT PERUBAHAN HARGA</div>
    
        <!-- Tabel Perubahan Produk -->

        <div id="tabelBanjaran">
            <table id="datatables1" class="table table-sm table-bordered table-striped table-hover" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th rowspan="3" style="text-align: center;">No</th>
                        <th rowspan="3" style="text-align: center;">Tanggal</th>
                        <th rowspan="3" style="text-align: center;">Kode Produk</th>
                        <th rowspan="3" style="text-align: center;">Nama Produk</th>                        
                        <th colspan="8" style="text-align: center;">Toko Banjaran</th>
                    </tr>
                    <tr>
                        <th colspan="4" style="text-align: center;">Member</th>
                        <th colspan="4" style="text-align: center;">Non Member</th>
                    </tr>
                    <tr>
                        <th style="text-align: center;">Harga Lama</th>
                        <th style="text-align: center;">Harga Baru</th>
                        <th style="text-align: center;">Diskon Lama</th>
                        <th style="text-align: center;">Diskon Baru</th>
                        <th style="text-align: center;">Harga Lama</th>
                        <th style="text-align: center;">Harga Baru</th>
                        <th style="text-align: center;">Diskon Lama</th>
                        <th style="text-align: center;">Diskon Baru</th>
                    </tr>
                </thead>
                
                <tbody>
                    @forelse($perubahanProduks as $perubahan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($perubahan->tanggal_perubahan)->format('d-m-Y H:i') }}</td>
                            <td>{{ $perubahan->produk->kode_lama }}</td>
                            <td>{{ $perubahan->produk->nama_produk }}</td>
                            
                            <!-- Member columns -->
                            <td>{{ $perubahan->member_hargaawal == $perubahan->member_harga ? '-' : $perubahan->member_hargaawal }}</td>
                            <td>{{ $perubahan->member_harga == $perubahan->member_hargaawal ? '-' : $perubahan->member_harga }}</td>
                            <td>{{ $perubahan->member_diskonawal == $perubahan->member_diskon ? '-' : $perubahan->member_diskonawal }}</td>
                            <td>{{ $perubahan->member_diskon == $perubahan->member_diskonawal ? '-' : $perubahan->member_diskon }}</td>
                            
                            <!-- Non Member columns -->
                            <td>{{ $perubahan->non_member_hargaawal == $perubahan->non_member_harga ? '-' : $perubahan->non_member_hargaawal }}</td>
                            <td>{{ $perubahan->non_member_harga == $perubahan->non_member_hargaawal ? '-' : $perubahan->non_member_harga }}</td>
                            <td>{{ $perubahan->non_member_diskonawal == $perubahan->non_member_diskon ? '-' : $perubahan->non_member_diskonawal }}</td>
                            <td>{{ $perubahan->non_member_diskon == $perubahan->non_member_diskonawal ? '-' : $perubahan->non_member_diskon }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center">Tidak ada perubahan data produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="signature-container">
            <div class="signature">
                <p>Mengetahui</p>
                <p>Pimpinan</p>
                <br><br><br>
                <p>__________________</p>
            </div>
            <div class="signature">
                <p>Mengetahui</p>
                <p>Finance</p>
                <br><br><br>
                <p>__________________</p>
            </div>
            <div class="signature">
                <p>Mengetahui</p>
                <p>Admin</p>
                <br><br><br>
                {{ ucfirst(auth()->user()->karyawan->nama_lengkap) }}
                <p>__________________</p>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <div>
                <a href="{{ url('admin/hargajual') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div>
                <a href="{{ route('print.reporthargajual') }}" id="printButton" target="_blank" class="btn btn-primary btn-sm">
                    <i class="fas fa-print"></i> Cetak 
                </a>
            </div>
            
        </div>
    </div>
      
</body>
</html>