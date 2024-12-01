<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Permintaan Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        @page {     
            margin: 1cm;
            size: auto; /* Ensures page size is automatically adjusted */
            @bottom-right {
                content: "Page " counter(page) " of " counter(pages);
            }
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .logo img {
            width: 150px;
            height: 77px;
        }

        .header {
            text-align: center;
            margin-top: 10px;
        }

        .header .title {
            font-weight: bold;
            font-size: 24px;
        }

        .divider {
            border: 0.5px solid #000;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .change-header {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-top: 4px;
            margin-bottom: 5px;
        }

        .section-title {
            margin-top: 5px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 16px;
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 9px;
        }

        th, td {
            padding: 4px;
            border: 1px solid black;
            text-align: left;
        }

        table td, table th {
            font-size: 10px;
        }

        /* Prevent page breaks inside tables */
        table, tr, td, th {
            page-break-inside: avoid;
        }

        /* Signature section */
        .signature-container {
            margin-top: 60px;
            text-align: center;
        }

        /* Avoid page breaks in key sections */
        .header, .change-header, .section-title, .table, .signature-container {
            page-break-after: avoid;
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
                <p>Jl. HOS. Cokro Aminoto No.5, Kagok, Kec. Slawi, Kabupaten Tegal, Jawa Tengah 52411</p>

            </div>
            <hr class="divider">
        </div>

        <!-- Judul Surat -->
        <div class="change-header">SURAT PERINTAH PRODUKSI</div>

        @foreach ($produkByDivisi as $divisi => $produks)
        <div class="section-title" style="text-align: center; margin-top: 1px; font-size: 20px;">{{ $divisi }}</div>
        <p class="period" style="text-align: center; margin: 10px 0;">
            Periode: {{ $formattedStartDate }} s/d {{ $formattedEndDate }}
        </p>
        <p class="period right-align" style="font-size: 10px; position: absolute; top: 0; right: 0; margin: 10px;">
            {{ $currentDateTime }}
        </p>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Kode Produk</th>
                    <th style="width: 60%;">Produk</th>
                    <th style="width: 10%;">Jumlah</th>
                    <th style="width: 10%;">Realisasi</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($produks->groupBy(function($item) {
                    return $item->produk->subklasifikasi->nama;
                }) as $subklasifikasi => $produkList)
                    @foreach ($produkList as $detail)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $detail->produk->kode_lama }}</td>
                            <td>{{ $detail->produk->nama_produk }}</td>
                            <td style="text-align: right">{{ $detail->jumlah }}</td>
                            <td style="text-align: right"></td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <th colspan="3" style="text-align: right;">Total:</th>
                    <th style="text-align: right;">{{ $totalPerDivisi[$divisi] }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table><br>
        
        @endforeach

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>
