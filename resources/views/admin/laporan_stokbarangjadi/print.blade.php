<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Barang Jadi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            padding-bottom: 100px; /* Increased padding-bottom for signatures */
        }
        .logo img {
            width: 150px;
            height: 77px;
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
        .section-title {
            margin-top: 30px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 16px;
            text-align: left;
        }
        .klasifikasi {
            margin-top: 10px;
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
            background-color: #f2f2f2;
        }
        .logo img {
            width: 100px;
            height: 60px;
        }
        .total-row {
            font-weight: bold;
            text-align: right;
        }
        .notes {
            margin-top: 30px;
            font-size: 12px;
        }
        .notes p {
            margin: 0;
        }

        @media print {
            .header {
                position: fixed;
                top: 0;
                width: 100%;
            }
            .table {
                margin-top: 150px;
            }
            .page-break {
                page-break-before: always;
                clear: both; /* Ensures that the page-break is clear of other elements */
            }
        }
    </style>
</head>
<body>


    <div class="header">
        <div class="logo">
            <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
        </div>
        <h1 class="title">PT JAVA BAKERY FACTORY</h1>
        {{-- <p class="title1">Cabang: {{ strtoupper($branchName) }}</p> --}}
        <div class="divider"></div>
    
        <h1 class="title2">LAPORAN STOK BARANG JADI</h1>
    
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
        use Carbon\Carbon;
        $currentKodeInput = null;
        $totalStok = 0; // Initialize total stock counter
    @endphp

@foreach ($stokBarangJadi as $index => $item)
    @if ($currentKodeInput != $item->kode_input)
        @if ($index != 0)
            <tr class="total-row">
                <td colspan="3" class="text-end">Total:</td>
                <td>{{ $totalStok }}</td>
            </tr>
            </tbody></table>
            <div class="page-break"></div> <!-- Page break added here -->
        @endif
        @php
            $totalStok = 0; // Reset total stock counter
            $currentKodeInput = $item->kode_input;
        @endphp
        
        <!-- Section title and klasifikasi displayed together -->
        <div class="section-title" style="margin-top: 2px;">No Input: {{ $currentKodeInput }}</div>
        <div class="klasifikasi" style="font-size: 12px; margin-bottom: 10px;">
        {{ $item->produk->klasifikasi->nama }}
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Kode Produk</th>
                    <th style="width: 50%;">Produk</th>
                    <th style="width: 10%;">Stok</th>
                </tr>
            </thead>
            <tbody>
    @endif
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->produk->kode_lama }}</td>
            <td>{{ $item->produk->nama_produk }}</td>
            <td style="text-align: left">{{ $item->stok }}</td>
        </tr>
        @php
            $totalStok += $item->stok; // Accumulate stock total
        @endphp
@endforeach
        <!-- Display total stock for the last set of data -->
        <tr class="total-row">
            <td colspan="3" class="text-end">Total:</td>
            <td>{{ $totalStok }}</td>
        </tr>
</tbody>
</table>

            
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>
