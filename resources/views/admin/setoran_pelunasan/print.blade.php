<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelunasan Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            padding-bottom: 60px;
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
            width: 100%;
            border-bottom: 1px solid #000;
            padding: 10px;
        }
        .header td {
            border: none;
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
            margin: 10px 0;
        }
        .change-header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }
        .change-header1 {
            text-align: center;
            font-size: 12px;
            margin-top: 5px;
        }
        .tanggal {
            text-align: left;
            font-size: 12px;
            font-weight: bold;
            margin-top: 20px;
        }
        .section-title {
            margin-top: 5px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 14px;
            text-align: left;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 4px;
            border: 1px solid black;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: white;
        }
        .row p {
            margin: 0;
        }
        .total-row {
            font-weight: bold;
        }
        
        /* CSS untuk tampilan cetak */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .container {
                width: 100%;
                margin: 0;
            }
            .header {
                border-bottom: 1px solid #000;
                page-break-inside: avoid;
            }
            .divider {
                border: 0.5px solid #000;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }
            th, td {
                padding: 6px;
                border: 1px solid #000;
            }
            .change-header {
                page-break-before: always;
            }
        }

        .signature-section {
            margin-top: 40px;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }
        .signature-table td {
            padding: 20px;
            vertical-align: top;
            border: none;
        }
        .signature-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .signature-line {
            border-top: 1px solid black;
            margin-top: 40px;
            margin-bottom: 5px;
        }
        .signature-name {
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Kop Surat -->
    <div class="header row">
        <div class="col-2 text-right">
            <div class="logo">
                {{-- <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY"> --}}
            </div>
            {{-- <div>
                <span class="title">PT JAVA BAKERY FACTORY</span><br>
                <p>Jl. HOS. Cokro Aminoto No.5, Kagok, Kec. Slawi, Kabupaten Tegal, Jawa Tengah 52411</p><br>
            </div> --}}
        </div>
        <div class="col-8 text-center">
            <div class="logo">
                <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
            </div>
            <span class="title">PT JAVA BAKERY FACTORY</span><br>
            <p>Jl. HOS. Cokro Aminoto No.5, Kagok, Kec. Slawi, Kabupaten Tegal, Jawa Tengah 52411</p><br>
        </div>
    </div>

    <!-- Judul Surat -->
    <div class="change-header">FAKTUR PELUNASAN PENJUALAN</div>
    <div class="change-header1">
        <p style="margin-bottom: 2px; font-size: 18px;">{{ $setoran->toko->nama_toko ?? 'Nama toko tidak tersedia' }}</p>
        <p>{{ $setoran->toko->alamat ?? 'Alamat tidak tersedia' }}</p>
    </div>

    <!-- Informasi Permintaan -->
    <div>
        <p style="margin-bottom: 2px;">
            <span style="min-width: 100px; display: inline-flex; align-items: center;"><strong>No. Faktur</strong></span>
            <span style="min-width: 50px; display: inline-flex; align-items: center;">: {{ $setoran->faktur_pelunasanpenjualan }}</span>
        </p>
        <p style="margin-bottom: 2px;">
            <span style="min-width: 100px; display: inline-flex; align-items: center;"><strong>Tanggal Setoran</strong> </span>
            <span style="min-width: 50px; display: inline-flex; align-items: center;">
                : {{ $setoran->created_at->locale('id')->translatedFormat('d F Y H:i') }}
            </span>
        </p> 
    </div>

    <table class="table table-bordered table-striped" style="margin-top: 20px;">
            <tr>
                <th style="width: 50%; text-align: left;">Keterangan</th>
                <th style="width: 30%; text-align: left;">Nilai</th>
                <th style="width: 20%; text-align: left;">Selisih</th>
            </tr>
        <tbody>
            <tr>
                <td>Penjualan Kotor</td>
                <td style="text-align: right;">{{ $setoran->penjualan_kotor1 ?? 0 }}</td>
                <td style="text-align: right;">{{ $setoran->penjualan_selisih ?? 0 }}</td>
            </tr>
            <tr>
                <td>Diskon Penjualan</td>
                <td style="text-align: right;">{{ $setoran->diskon_penjualan1 ?? 0 }}</td>
                <td style="text-align: right;">{{ $setoran->diskon_selisih ?? 0 }}</td>
            </tr>
            <tr>
                <td>Penjualan Bersih</td>
                <td style="text-align: right;">{{ $setoran->penjualan_bersih1 ?? 0 }}</td>
                <td style="text-align: right;">{{ $setoran->penjualanbersih_selisih ?? 0 }}</td>
            </tr>
            <tr>
                <td>Deposit Masuk</td>
                <td style="text-align: right;">{{ $setoran->deposit_masuk1 ?? 0 }}</td>
                <td style="text-align: right;">{{ $setoran->depositmasuk_selisih ?? 0 }}</td>
            </tr>
            <tr>
                <td>Deposit Keluar</td>
                <td style="text-align: right;">{{ $setoran->deposit_keluar1 ?? 0 }}</td>
                <td style="text-align: right;">{{ $setoran->depositkeluar_selisih ?? 0 }}</td>
            </tr>
            <tr>
                <td>Total Penjualan</td>
                <td style="text-align: right;">{{ $setoran->total_penjualan1 ?? 0 }}</td>
                <td style="text-align: right;">{{ $setoran->totalpenjualan_selisih ?? 0 }}</td>
            </tr>
            <tr>
                <td>Mesin EDC</td>
                <td style="text-align: right;">{{ $setoran->mesin_edc1 ?? 0 }}</td>
                <td style="text-align: right;">{{ $setoran->mesinedc_selisih ?? 0 }}</td>
            </tr>
            <tr>
                <td>QRIS</td>
                <td style="text-align: right;">{{ $setoran->qris1 ?? 0 }}</td>
                <td style="text-align: right;">{{ $setoran->qris_selisih ?? 0 }}</td>
            </tr>
            <tr>
                <td>GOBIZ</td>
                <td style="text-align: right;">{{ $setoran->gobiz1 ?? 0 }}</td>
                <td style="text-align: right;">{{ $setoran->gobiz_selisih ?? 0 }}</td>
            </tr>
            <tr>
                <td>Transfer</td>
                <td style="text-align: right;">{{ $setoran->transfer1 ?? 0 }}</td>
                <td style="text-align: right;">{{ $setoran->transfer_selisih ?? 0 }}</td>
            </tr>
            <tr>
                <td>Total Setoran</td>
                <td style="text-align: right;">{{ $setoran->total_setoran1 ?? 0 }}</td>
                <td style="text-align: right;">{{ $setoran->totalsetoran_selisih ?? 0 }}</td>
            </tr>
        </tbody>
    </table>
    <!-- Tanda Tangan -->
   

    <div class="print-date" style="position: fixed; bottom: 0; right: 0; text-align: right; margin-top: 20px; width: 100%;">
        <p style="margin: 0; font-size: 10px;">
            {{ now()->locale('id')->translatedFormat('d F Y H:i') }}
        </p>
</body>
</html>
