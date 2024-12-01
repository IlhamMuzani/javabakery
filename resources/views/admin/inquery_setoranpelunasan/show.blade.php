<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Pelunasan Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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

        .header .address,
        .header .contact {
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
            margin-bottom: 1px;
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

        th,
        td {
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
        {{-- <hr class="divider"> --}}

        <!-- Judul Surat -->
        <div class="change-header">FAKTUR PELUNASAN PENJUALAN</div>
        <div class="change-header1">
            <p style="margin-bottom: 2px; font-size: 18px;">
                {{ $cetakpdf->toko->nama_toko ?? 'Nama toko tidak tersedia' }}</p>
            <p>{{ $cetakpdf->toko->alamat ?? 'Alamat tidak tersedia' }}</p>
        </div>
        <!-- Informasi Permintaan -->
        <div>
            <p style="margin-bottom: 2px;">
                <strong>No Faktur :</strong> {{ $cetakpdf->faktur_pelunasanpenjualan }}
            </p>
            <p style="margin-bottom: 2px;">
                <strong>Tanggal Setoran :</strong>
                {{ \Carbon\Carbon::parse($cetakpdf->tanggal_setoran)->format('d-m-Y H:i') }}
            </p>

        </div>

        <table class="table table-bordered table-striped" style="margin-top: 20px;">
            <thead class="table-dark">
                <tr>
                    <th style="width: 50%; text-align: left;">Keterangan</th>
                    <th style="width: 30%; text-align: right;">Nilai</th>
                    <th style="width: 20%; text-align: right;">Selisih</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Penjualan Kotor</td>
                    <td style="text-align: right;">
                        {{ number_format($cetakpdf->setoran_penjualan->penjualan_kotor, 0, ',', '.') ?? 0 }}</td>
                    <td style="text-align: right;">{{ number_format($cetakpdf->penjualan_selisih, 0, ',', '.') ?? 0 }}
                    </td>
                </tr>
                <tr>
                    <td>Diskon Penjualan</td>
                    <td style="text-align: right;">
                        {{ number_format($cetakpdf->setoran_penjualan->diskon_penjualan, 0, ',', '.') ?? 0 }}</td>
                    <td style="text-align: right;">{{ number_format($cetakpdf->diskon_selisih, 0, ',', '.') ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Penjualan Bersih</td>
                    <td style="text-align: right;">
                        {{ number_format($cetakpdf->setoran_penjualan->penjualan_bersih, 0, ',', '.') ?? 0 }}</td>
                    <td style="text-align: right;">
                        {{ number_format($cetakpdf->penjualanbersih_selisih, 0, ',', '.') ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Deposit Masuk</td>
                    <td style="text-align: right;">
                        {{ number_format($cetakpdf->setoran_penjualan->deposit_masuk, 0, ',', '.') ?? 0 }}</td>
                    <td style="text-align: right;">
                        {{ number_format($cetakpdf->depositmasuk_selisih, 0, ',', '.') ?? 0 }}
                    </td>
                </tr>
                <tr>
                    <td>Deposit Keluar</td>
                    <td style="text-align: right;">
                        {{ number_format($cetakpdf->setoran_penjualan->deposit_keluar, 0, ',', '.') ?? 0 }}</td>
                    <td style="text-align: right;">
                        {{ number_format($cetakpdf->depositkeluar_selisih, 0, ',', '.') ?? 0 }}
                    </td>
                </tr>
                <tr>
                    <td>Total Penjualan</td>
                    <td style="text-align: right;">
                        {{ number_format($cetakpdf->setoran_penjualan->total_penjualan, 0, ',', '.') ?? 0 }}</td>
                    <td style="text-align: right;">
                        {{ number_format($cetakpdf->totalpenjualan_selisih, 0, ',', '.') ?? 0 }}
                    </td>
                </tr>
                <tr>
                    <td>Mesin EDC</td>
                    <td style="text-align: right;">
                        {{ number_format($cetakpdf->setoran_penjualan->mesin_edc, 0, ',', '.') ?? 0 }}</td>
                    <td style="text-align: right;">{{ number_format($cetakpdf->mesinedc_selisih, 0, ',', '.') ?? 0 }}
                    </td>
                </tr>
                <tr>
                    <td>QRIS</td>
                    <td style="text-align: right;">
                        {{ number_format($cetakpdf->setoran_penjualan->qris, 0, ',', '.') ?? 0 }}</td>
                    <td style="text-align: right;">{{ number_format($cetakpdf->qris_selisih, 0, ',', '.') ?? 0 }}</td>
                </tr>
                <tr>
                    <td>GOBIZ</td>
                    <td style="text-align: right;">
                        {{ number_format($cetakpdf->setoran_penjualan->gobiz, 0, ',', '.') ?? 0 }}</td>
                    <td style="text-align: right;">{{ number_format($cetakpdf->gobiz_selisih, 0, ',', '.') ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Transfer</td>
                    <td style="text-align: right;">
                        {{ number_format($cetakpdf->setoran_penjualan->transfer, 0, ',', '.') ?? 0 }}</td>
                    <td style="text-align: right;">{{ number_format($cetakpdf->transfer_selisih, 0, ',', '.') ?? 0 }}
                    </td>
                </tr>
                <tr>
                    <td>Total Setoran</td>
                    <td style="text-align: right;">
                        {{ number_format($cetakpdf->setoran_penjualan->total_setoran, 0, ',', '.') ?? 0 }}</td>
                    <td style="text-align: right;">
                        {{ number_format($cetakpdf->totalsetoran_selisih, 0, ',', '.') ?? 0 }}
                    </td>
                </tr>
            </tbody>
        </table>




        <div class="d-flex justify-content-between">
            <div>
                <a href="{{ url('admin/inquery_setoranpelunasan') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Kembali
                </a>
            </div>
            <div>
                <a href="{{ route('setoran_pelunasan.print', $cetakpdf->id) }}" id="printButton" target="_blank"
                    class="btn btn-primary btn-sm">
                    <i class="fas fa-print"></i> Cetak
                </a>
            </div>
        </div>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>

</html>
