<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Deposit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }
        .header .col {
            width: 48%;
        }
        .header .title {
            font-weight: bold;
            font-size: 16px;
        }
        .header .details {
            margin-bottom: 10px;
        }
        .text-center {
            text-align: center;
        }
        .receipt {
            border: 1px solid black;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 20px;
        }
        .receipt .content {
            margin-bottom: 20px;
        }
        .receipt .nominal {
            font-size: 20px;
            font-weight: bold;
        }
        .receipt .terbilang {
            margin-top: 10px;
            font-style: italic;
        }
        .tab td {
            border: none;
        }
        .tab .col {
            padding: 0 10px;
        }
        .tab .title {
            font-weight: bold;
            font-size: 18px;
        }
        .tab .address, .tab .contact {
            font-size: 12px;
        }
        /* New CSS for adjusting column width */
        .tab td.date {
            width: 150px; /* Adjust width as needed */
        }
    </style>
</head>
<body>

    <table class="tab">
        <tr>
            <td class="col">
                <div class="title">JAVA BAKERY - {{ $branchName }}</div>
                <p>{{ $branchAddress ?? 'Alamat tidak tersedia' }}</p>
            </td>
            <td class="col text-center">
                <div>
                    <p style="color: white">Jl. HOS. Cokro Aminoto No.5, Kagok, Kec. Slawi, Kabupaten Tegal, Jawa Tengah 52411</p><br>
                </div> 
            </td>
            <td class="col date text-right">
                <div class="logo">
                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}</p>
                </div>
            </td>
        </tr>
    </table>
    <hr class="divider" style="margin-bottom: 2px;">


    <h2 class="text-center">SALDO DEPOSIT {{ $branchName }}</h2>

    @foreach ($saldoPerToko as $tokoId => $saldo)
        @php
            $toko = $tokos->find($tokoId);
        @endphp
        <div class="receipt">
            <table>
                <tr>
                    <td class="label"><strong>Nominal</strong> </td>
                    <td class="value">:  {{'Rp. '. number_format($saldo, 0, ',', '.') }} </td>
                </tr><br>
                <tr>
                    <td class="label"><strong>Terbilang</strong> </td>
                    <td class="value" style="font-style: italic">:  {{ ucwords(terbilang($saldo)) }} Rupiah</td>
                </tr>
            </table>
        </div>
    @endforeach
</body>
</html>
