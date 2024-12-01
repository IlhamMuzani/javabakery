<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Stok Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            padding-bottom: 100px; /* Increased padding-bottom for signatures */
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
            margin-top: 20px;
            margin-bottom: 20px;
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
            font-size: 10px;
        }
        th, td {
            padding: 4px;
            border: 1px solid black;
            text-align: left;
        }
        th {
            background-color: white;
        }
        .signature-container {
            margin-top: 60px;
        }
        .signature-row {
            display: flex;
            justify-content: space-between;
            margin: 0 20px;
        }
        .signaturea {
            flex: 1;
            text-align: left;
            margin: 0 10px; /* Space between signatures */
        }
        .signatureb {
            flex: 1;
            text-align: center;
            margin: 0 10px;
            margin-top: -200px; /* Space between signatures */
        }
        .signaturec {
            flex: 1;
            text-align: right;
            margin: 0 10px; 
            margin-top: -200px; /* Space between signatures */
        }
        .signature p {
            margin: 0;
            margin-top: 10px;
        }
        .total-row {
            font-weight: bold;
        }
        .notes {
            margin-top: 30px;
            font-size: 12px;
        }
        .notes p {
            margin: 0;
        }
        .footer {
            position: absolute;
            bottom: 2;
            width: 100%;
            text-align: left;
            font-size: 12px;
         
        }
        .info-table {
            border: none;
        }
        .info-table td {
            border: none;
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
            <span class="title">PT JAVA BAKERY FACTORY</span><br>
            <span class="address">JL. HOS COKRO AMINOTO NO 5 SLAWI TEGAL</span><br>
            <span class="contact">Telp / Fax, Email :</span>
        </div>
        <br>
        <hr class="divider">
    </div>

    <!-- Judul Surat -->
    <div class="change-header">SURAT STOK BARANG JADI</div>


    <table class="info-table" style="width: 100%; margin-bottom: 10px;">
        <tr>
            <td style="width: 20%;"><strong>Kode Permintaan</strong></td>
            <td style="width: 40%;">: {{ $kodeInput }}</td>
            <td></td>
        </tr>
        <tr>
            <td><strong>Tanggal</strong></td>
            <td>: {{ \Carbon\Carbon::parse($tanggalInput)->format('d-m-Y H:i') }}</td> <!-- Format tanggal_input -->
            <td></td>
        </tr>
        <tr>
            <td><strong></strong></td>
            <td></td>
            <td style="text-align: right; width: 30%; font-style: italic"><strong>Cetak :</strong> {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}</td>
        </tr>
        
    </table>
    

    <!-- Detail Produk -->
    @foreach($detailStokBarangJadi as $klasifikasi => $details)
    <h3>{{ $klasifikasi }}</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Produk</th>
                <th>Kategori</th>
                <th>Produk</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalJumlah = 0;
            @endphp
            @foreach($details as $key => $detail)
                @php
                    $totalJumlah += $detail->stok;
                @endphp
                <tr>
                    <td>{{ $key + 1 }}</td> 
                    <td>{{ $detail->produk->kode_lama }}</td>
                    <td>{{ $detail->produk->subklasifikasi->nama }}</td>
                    <td>{{ $detail->produk->nama_produk }}</td>
                    <td style="text-align: right">{{ $detail->stok }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4">Total</td>
                <td style="text-align: right">{{ $totalJumlah }}</td>
            </tr>
        </tbody>
    </table>
    <br>
@endforeach

    <!-- Tanda Tangan -->
    <div class="signature-container">
        <div class="signature-row">
            <div class="signaturea">
                <p style="margin-left: 10px;"><strong>Gudang Barang Jadi</strong></p><br><br>
                <p>____________________</p>
                {{-- <p>Nama Gudang</p> --}}
            </div>
            <div class="signatureb">
                <p><strong>Produksi</strong></p><br><br>
                <p>____________________</p>
                {{-- <p>Nama Accounting</p> --}}
            </div>
            <div class="signaturec">
                <p style="margin-right: 60px;"><strong>Baker</strong></p><br><br>
                <p>____________________</p>
                {{-- <p>Nama Karyawan</p> --}}
            </div>
        </div>
    </div>
    {{-- <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}
    </div> --}}
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>
