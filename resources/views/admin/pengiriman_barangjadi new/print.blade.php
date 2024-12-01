<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pengiriman Produk</title>
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
    <div class="change-header">SURAT PENGIRIMAN BARANG JADI - STOK TOKO</div>
    <div class="change-header1">
        <p style="margin-bottom: 2px; font-size: 18px;">{{ $firstItem->toko->nama_toko ?? 'Nama toko tidak tersedia' }}</p>
        <p>{{ $firstItem->toko->alamat ?? 'Alamat tidak tersedia' }}</p>
    </div>

    <!-- Informasi Permintaan -->
    <div>
        <p style="margin-bottom: 2px;">
            <span style="min-width: 100px; display: inline-flex; align-items: center;"><strong>Kode Pengiriman</strong></span>
            <span style="min-width: 50px; display: inline-flex; align-items: center;">: {{ $firstItem->kode_pengiriman }}</span>
        </p>
        <p style="margin-bottom: 2px;">
            <span style="min-width: 100px; display: inline-flex; align-items: center;"><strong>Tanggal Kirim</strong> </span>
            <span style="min-width: 50px; display: inline-flex; align-items: center;">
                : {{ $firstItem->created_at->locale('id')->translatedFormat('d F Y H:i') }}
            </span>
        </p> 
    </div>

    <!-- Detail Produk -->
    @foreach($groupedByKlasifikasi as $klasifikasi => $items)
    <div class="section-title">{{ $klasifikasi }}</div>
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
            @foreach($items as $key => $detail)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $detail->produk->kode_lama }}</td>
                <td>{{ $detail->produk->subklasifikasi->nama }}</td>
                <td>{{ $detail->produk->nama_produk }}</td>
                <td style="text-align: right">{{ $detail->jumlah }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;"><strong>Total</strong></td>
                <td style="text-align: right"><strong>{{ $items->sum('jumlah') }}</strong></td>
            </tr>
        </tfoot>
    </table><br>
    @endforeach

    <!-- Tanda Tangan -->
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td>
                    <div class="signature-title">Pengirim</div>
                    <div class="signature-line"></div>
                    <div class="signature-name">Nama Pengirim</div>
                </td>
                <td>
                    <div class="signature-title">Sopir</div>
                    <div class="signature-line"></div>
                    <div class="signature-name">Nama Sopir</div>
                </td>
                <td>
                    <div class="signature-title">Penerima</div>
                    <div class="signature-line"></div>
                    <div class="signature-name">Nama Penerima</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="print-date" style="position: fixed; bottom: 0; right: 0; text-align: right; margin-top: 20px; width: 100%;">
        <p style="margin: 0; font-size: 10px;">
            {{ now()->locale('id')->translatedFormat('d F Y H:i') }}
        </p>
</body>
</html>
