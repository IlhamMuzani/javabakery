<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak penjualan Produk</title>
    <style>
        html,
            body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            /* margin: 0; */
            margin-left: -5;
            margin-top: 0;
            /* padding: 0; */
            padding-right: 450px;
            font-size: 10px;
            background-color: #fff;
        }
            .container {
            width: 70mm; /* Adjusted width */
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 0px 5px rgba(0,0,0,0.1);
        }

        .header {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100px; /* Sesuaikan tinggi header sesuai kebutuhan */
         }

        .header .text {
            display: flex;
            flex-direction: column;
            align-items: center; /* Mengatur konten di dalam .text agar berada di tengah */
            text-align: center; /* Mengatur teks di dalam .text agar berada di tengah */
        }

        .header .text h1 {
            margin-top: 10px;
            margin-bottom: 0px;
            padding: 0;
            font-size: 16px;
            color: #0c0c0c;
            text-transform: uppercase;
        }

        .header .text p {
            margin: 2px ;
            font-size: 8px;
            margin-bottom: 2px;
        }
        .section {
            margin-bottom: 10px;
        }
        .section h2 {
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            text-align: center;
            margin-bottom: 5px;
            font-size: 12px;
            text-transform: uppercase;
        }
        .section table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .section table th, .section table td {
            border: 1px solid #ccc;
            padding: 5px;
            font-size: 8px;
        }
        
        .float-right {
            text-align: right;
            margin-top: 10px;
        }
        .float-right button {
            padding: 5px 10px;
            font-size: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 3px;
            box-shadow: 0px 1px 3px rgba(0,0,0,0.1);
        }
        .float-right button:hover {
            background-color: #0056b3;
        }
        .detail-info {
            display: flex;
            margin-top: -24px;
            flex-direction: column;
        }
        .detail-info .pengiriman{
            display: flex;
            margin-top: 0px;
            margin-bottom: 2px;
            flex-direction: column;
            /* border-bottom: 1px solid #ccc;
            padding-bottom: 5px; */

        }
        .detail-info .penjualan{
            display: flex;
            margin-top: 2px;
            margin-bottom: 2px;
            flex-direction: column;
            /* border-bottom: 1px solid #ccc;
            padding-bottom: 5px; */

        }
        .detail-info p {
            margin: -1px 0;
            display: flex;
            justify-content: space-between;
        }
        .detail-info p strong {
            min-width: 130px; /* Sesuaikan dengan lebar maksimum label */
            font-size: 8px;
        }
        .detail-info p span {
            flex: 1;
            text-align: left;
            font-size: 8px;
            white-space: nowrap; /* Agar teks tidak pindah ke baris baru */
        }
        .penjualan p span {
            margin-top: 3px;
        }
        .pelanggan p span {
            margin-top: 3px;
            
        }
        .telepon p span {
            margin-top: 3px;
        }
        .alamat p span {
            margin-top: 3px;
        }
        .tanggal p span {
            margin-top: 3px;
        }
        .divider {
            border: 0.5px solid;
            margin-top: -10px;
            margin-bottom: 2px;
            border-bottom: 2px solid #0f0e0e;
        }
    .terimakasih p{
        border-top: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
        padding-bottom: 5px;
        text-align: center;
        margin-bottom: 5px;
        margin-top: 10px;
        font-size: 10px;
    }
        @media print {
    body {
        font-size: 10px;
        background-color: #fff;
        margin: 0;
        padding: 0;
    }
    .container {
        width: 70mm; /* Sesuaikan dengan lebar kertas thermal */
        margin: 0 auto;
        border: none;
        padding: 0;
        box-shadow: none;
    }
    .header .logo img {
        max-width: 80px; /* Sesuaikan jika perlu */
        height: auto;
    }
    .section table {
        width: 100%;
        margin-top: 5px;
    }
    .section table th, .section table td {
        border: 1px solid #ccc;
        padding: 5px;
        font-size: 8px;
    }
    .signatures {
        display: flex;
        justify-content: space-between;
    }
    .signature1, .signature2 {
        font-size: 7px;
        text-align: left;
    }
    .signature2 {
        margin-top: -65px; /* Atur posisi jika perlu */
    }
    
    .signature p {
        margin-top: 10px;
        line-height: 1.2;
    }
    .detail-info p strong {
        min-width: 130px; /* Sesuaikan dengan kebutuhan */
        font-size: 8px;
    }
    .float-right button {
        font-size: 10px;
        padding: 5px 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        cursor: pointer;
        border-radius: 3px;
        box-shadow: 0px 1px 3px rgba(0,0,0,0.1);
    }
    .float-right button:hover {
        background-color: #0056b3;
    }
    .detail-info {
        margin-top: -24px;
        flex-direction: column;
    }
    .detail-info p {
        margin: -1px 0;
        display: flex;
        justify-content: space-between;
    }
    .penjualan p span {
        margin-top: 3px;
    }
    .pelanggan p span {
        margin-top: 3px;
    }
    .telepon p span {
        margin-top: 3px;
    }
    .alamat p span {
        margin-top: 3px;
    }
    .tanggal p span {
        margin-top: 3px;
    }
    .divider {
        border: 0.5px solid;
        margin-top: 3px;
        margin-bottom: 1px;
        border-bottom: 1px solid #0f0e0e;
    }
    @page {
        size: 70mm auto; /* Sesuaikan dengan ukuran kertas thermal */
        margin: 0mm; /* Set margin ke 0 untuk semua sisi */
    }
}

    </style>

    
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="text">
                <h1>PT JAVA BAKERY FACTORY</h1>
                <p>Cabang : {{ $tokos->nama_toko }}</p>
                <p>{{ $tokos->alamat }}</p>
            </div>
        </div>
        <hr class="divider">
        <hr class="divider">
        <div class="section">
            <h2>Struk penjualan</h2>
            <p style="text-align: right; font-size: 8px;">
                {{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->locale('id')->translatedFormat('l, d F Y H:i') }}
            </p><br>
            <div class="detail-info">
                <div class="penjualan">
                    <p>
                        <span style="min-width: 100px; display: inline-flex; align-items: center;">No penjualan</span>
                        <span style="min-width: 50px; display: inline-flex; align-items: center;">: {{ $penjualan->kode_penjualan }}</span>
                    </p>
                </div>
                <div class="kasir">
                    <p>
                        <span style="min-width: 100px; display: inline-flex; align-items: center;">Kasir</span>
                        <span style="min-width: 50px; display: inline-flex; align-items: center;">: {{ ucfirst(auth()->user()->karyawan->nama_lengkap) }}</span>
                    </p>
                </div>
                @if(!is_null($penjualan->nama_pelanggan))
                    <div class="pelanggan">
                        <p>
                            <span style="min-width: 100px; display: inline-flex; align-items: center;">Pelanggan</span>
                            <span style="min-width: 50px; display: inline-flex; align-items: center;">
                                : 
                                @if ($penjualan->kode_pelanggan && $penjualan->nama_pelanggan)
                                    {{ $penjualan->kode_pelanggan }} / {{ $penjualan->nama_pelanggan }}
                                @else
                                    non member
                                @endif
                            </span>
                        </p>
                    </div>
                @endif

                @if($penjualan->detailpenjualanproduk->isEmpty())
                    <p>Tidak ada detail penjualan produk.</p>
                @else
                <table style="font-size: 12px; width: 100%;">
                    <thead>
                        <tr>
                            <th style="font-size: 8px;">Kode Produk</th>
                            <th style="font-size: 8px;">Produk</th>
                            <th style="font-size: 8px;">Jumlah</th>
                            <th style="font-size: 8px;">Harga</th>
                            <th style="font-size: 8px;">Diskon</th>
                            <th style="font-size: 8px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $subtotal = 0;
                        @endphp
                        @foreach($penjualan->detailpenjualanproduk as $detail)
                            <tr>
                                <td style="font-size: 8px;">{{ $detail->kode_lama }}</td>
                                <td style="font-size: 8px;">{{ $detail->nama_produk }}</td>
                                <td style="font-size: 8px;">{{ $detail->jumlah }}</td>
                                <td style="font-size: 8px;">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td style="font-size: 8px;">
                                    @if ($detail->diskon > 0)
                                        {{ $detail->diskon }} %
                                    @else
                                        -
                                    @endif
                                </td>
                                <td style="font-size: 8px; text-align: right;">{{'Rp.'. number_format($detail->total , 0, ',', '.')}}</td>
                            </tr>
                            @php
                                $total = is_numeric($detail->total) ? $detail->total : 0;
                                $subtotal += $total;
                            @endphp
                        @endforeach
                        <tr>
                            @if($penjualan->metode_id !== null)
                                <td colspan="5" style="text-align: right; font-size: 8px;"><strong> Fee {{$penjualan->metodepembayaran->nama_metode}} {{$penjualan->metodepembayaran->fee}}%</strong></td>
                                <td style="font-size: 8px; text-align: right;">
                                    @php
                                        // Menghapus semua karakter kecuali angka
                                        $total_fee = preg_replace('/[^\d]/', '', $penjualan->total_fee);
                                        // Konversi ke tipe float
                                        $total_fee = (float) $total_fee;
                                    @endphp
                                    {{ 'Rp. ' . number_format($total_fee, 0, ',', '.') }}
                                </td>
                            @endif
                        </tr>
                        
                        
                        <tr>
                            <td colspan="5" style="text-align: right; font-size: 8px;"><strong>Total </strong></td>
                            <td>
                                {{ Str::startsWith($penjualan->sub_total, 'Rp') ? $penjualan->sub_total : 'Rp ' . number_format((float)$penjualan->sub_total, 0, ',', '.') }}
                            </td>                        </tr>
                        @if($penjualan->metode_id == Null)
                            <tr>
                                <td colspan="5" style="text-align: right; font-size: 8px;"><strong> Bayar</strong></td>
                                <td>
                                    {{ Str::startsWith($penjualan->bayar, 'Rp') ? $penjualan->bayar : 'Rp ' . number_format((float)$penjualan->bayar, 0, ',', '.') }}
                                </td>                            </tr>
                            <tr>
                                <td colspan="5" style="text-align: right; font-size: 8px;"><strong>Kembali</strong></td>
                                <td>
                                    {{ Str::startsWith($penjualan->kembali, 'Rp') ? $penjualan->kembali : 'Rp ' . number_format((float)$penjualan->kembali, 0, ',', '.') }}
                                </td>                            </tr>
                        @elseif($penjualan->metode_bayar == 'mesinedc' || $penjualan->metode_bayar == 'gobiz')
                            
                        @endif
                    </tbody>
                    
                </table>
                @endif
            </div>
           
            @if(!is_null($penjualan->catatan))
                <div class="catatan">
                    <label>Catatan:</label>
                    <p>{{$penjualan->catatan}}</p>
                </div>
            @endif

            @if(preg_replace('/[^0-9]/', '', $penjualan->sub_total) < preg_replace('/[^0-9]/', '', $penjualan->sub_totalasli))
            <div class="hemat">
                <label>Anda telah hemat: </label>
                <span><strong>{{ 'Rp. ' . number_format(preg_replace('/[^0-9]/', '', $penjualan->sub_totalasli) - preg_replace('/[^0-9]/', '', $penjualan->sub_total), 0, ',', '.') }}</strong></span>
            </div>
            @endif
            <div class="terimakasih">
                <p>Untuk pemesanan, kritik dan saran Hubungi.082136638003.</p>
            </div>

            <div class="terimakasihd" style="text-align: left; margin-top: -15px ; font-size: 10px; font-style: italic" >
                <p>Barang yang sudah dibeli tidak bisa dikembalikan atau ditukar.</p><br> 
            </div>
            <div class="terimakasihd" style="text-align: center; margin-top: -35px" >
                <p>Terimakasih atas kunjungannya</p><br> 
            </div>
            <div class="qr" style="display: flex; justify-content: center; align-items: center; margin-top: -10px; margin-left: 120px">
                <div style="text-align: center;">
                    {!! DNS2D::getBarcodeHTML($penjualan->qrcode_penjualan, 'QRCODE', 1.5, 1.5) !!}
                </div>
            </div>  
        </div>
        
        
    </body>
    </html>
    