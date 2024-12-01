<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pemesanan Global</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
            padding: 4px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #f2f2f2;
        }
        .logo img {
            width: 100px;
            height: 60px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header .title {
            font-weight: bold;
            font-size: 28px;
            margin-bottom: 5px;
            margin-top: 5PX;
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
            border: 0.5px solid;
            margin-top: 5px;
            margin-bottom: 5px;        
        }
        .right-align {
            text-align: right;
            font-size: 10px;
        }
        .klasifikasi-title {
            font-weight: bold;
            font-size: 16px;
            margin-top: 20px;
        }
        .total-row {
            font-weight: bold;
        }
        .divider1 {
        border-top: 2px dashed #000; /* Gaya garis putus-putus dengan warna hitam */
        margin: 20px 0; /* Jarak atas dan bawah divider */
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo">
            <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
        </div>
        <h1 class="title">PT JAVA BAKERY FACTORY</h1>
        <p class="title1">Cabang: {{ strtoupper($selectedCabang) }}</p>
        <div class="divider"></div>

        <h1 class="title2">LAPORAN PEMESANAN PRODUK GLOBAL</h1>
    
        @php
            use Carbon\Carbon;
            Carbon::setLocale('id');
            $formattedStartDate = $startDate ? Carbon::parse($startDate)->translatedFormat('d F Y') : 'Tidak ada';
            $formattedEndDate = $endDate ? Carbon::parse($endDate)->translatedFormat('d F Y') : 'Tidak ada';
            $currentDateTime = Carbon::now()->translatedFormat('d F Y H:i');
        @endphp
    
        <p class="period">
            Periode: {{ $formattedStartDate }} s/d {{ $formattedEndDate }}
        </p>
        <p class="period right-align" style="font-size: 10px; position: absolute; top: 0; right: 0; margin: 10px;">
            {{ $currentDateTime }}
        </p>
        
    </div>

    @foreach ($groupedData as $klasifikasi => $items)
        <div class="klasifikasi-title">{{ $klasifikasi }}</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th style="width: 100px;">Kode Produk</th>
                    <th style="width: 200px;">Nama Produk</th>
                    @foreach ($tokoFieldMap as $tokoField)
                        <th style="width: 80px;">{{ ucfirst($tokoField) }}</th> <!-- Sesuaikan lebar sesuai kebutuhan -->
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php 
                    $no = 1; 
                    $totalPerToko = array_fill_keys($tokoFieldMap, 0); 
                    $subtotalKlasifikasi = 0;
                @endphp
                @foreach ($items as $data)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $data['kode_lama'] }}</td>
                        <td>{{ $data['nama_produk'] }}</td>
                        @foreach ($tokoFieldMap as $tokoField)
                            <td style="text-align: right">{{ $data[$tokoField] }}</td>
                            @php
                                $totalPerToko[$tokoField] += $data[$tokoField];
                                $subtotalKlasifikasi += $data[$tokoField]; // Menambahkan subtotal untuk klasifikasi
                            @endphp
                        @endforeach
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">Total:</td>
                    @foreach ($tokoFieldMap as $tokoField)
                        <td style="text-align: right">{{ $totalPerToko[$tokoField] }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
        
        <div class="divider1"></div>

    @endforeach

</body>
</html>
