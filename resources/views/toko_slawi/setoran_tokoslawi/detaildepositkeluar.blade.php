<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pelunasan Pemesanan</title>
    <style>
        html,
            body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            /* margin: 0; */
            margin-left: -5;
            margin-top: 0;
            /* padding: 0; */
            padding-right: 430px;
            font-size: 10px;
            background-color: #fff;
        }
            .container {
            width: 65mm; /* Adjusted width */
            margin: 0 auto;
            border: 1px solid white;
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
            font-size: 9px;
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
            border: 1px solid white;
            padding: 5px;
            font-size: 10px;
        }
        .signatures {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
        }
        .signature1 {
            text-align: left;
            font-size: 7px;
        }
        .signature2 {
            text-align: center;
            font-size: 7px;
            margin-top: -65px;
        }
        .signature3 {
            text-align: right;
            font-size: 6px;
            margin-top: -65px;
        }
        .signature p {
            margin-top: 10px;
            line-height: 1.2;
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
        .detail-info .pemesanan{
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
            font-size: 10px;
        }
        .detail-info p span {
            flex: 1;
            text-align: left;
            font-size: 10px;
            white-space: nowrap; /* Agar teks tidak pindah ke baris baru */
        }
        .pemesanan p span {
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
        width: 65mm; /* Sesuaikan dengan lebar kertas thermal */
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
        font-size: 9px;
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
        font-size: 9px;
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
    .pemesanan p span {
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
        size: 65mm auto; /* Sesuaikan dengan ukuran kertas thermal */
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
                <p style="font-size: 10px;">Cabang : {{ $tokos->nama_toko }}</p>
                <p style="font-size: 9px;">{{ $tokos->alamat }}</p>
            </div>
        </div>
        <hr class="divider">
        <hr class="divider">
        <div class="section">
            <h2>Struk Pelunasan Pemesanan</h2>
            <p style="text-align: right; font-size: 11px;">
                {{ \Carbon\Carbon::parse($inquery->tanggal_pemesanan)->locale('id')->translatedFormat('d F Y H:i') }}
            </p><br>
            <div class="detail-info">
                <div class="pemesanan">
                    <p><span style="min-width: 70px; display: inline-flex; align-items: center;">No Penjualan</span>
                       <span style="min-width: 100px; display: inline-flex; align-items: center; font-size: 11px;">: {{ $inquery->kode_penjualan }}</span></p>
                </div>
                {{-- <div class="deposit">
                    <p><span style="min-width: 100px; display: inline-flex; align-items: center;">No Deposit</span>
                    <span style="min-width: 100px; display: inline-flex; align-items: center;">: {{ $kode_dppemesanan }}</span></p>
                </div> --}}
                
                <div class="kasir">
                    <p><span style="min-width: 70px; display: inline-flex; align-items: center;">Kasir</span>
                        <span style="min-width: 100px; display: inline-flex; align-items: center;">: {{ $inquery->kasir }}</span></p>
                </div>
                <div class="pelanggan">
                    <p><span style="min-width: 70px; display: inline-flex; align-items: center;">Pelanggan</span>
                        <span style="min-width: 100px; display: inline-flex; align-items: center;">: {{ $inquery->dppemesanan->pemesananproduk->nama_pelanggan }}</span></p>
                </div>

    

                <table style="font-size: 12px; width: 100%;">
                    <thead>
                        <tr>
                            <th style="font-size: 10px;">Nama Produk</th>
                            <th style="font-size: 10px;">Jumlah</th>
                            <th style="font-size: 10px;">Harga</th>
                            <th style="font-size: 10px;">Diskon</th>
                            <th style="font-size: 10px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $subtotal = 0;
                        @endphp
                        @foreach($inquery->detailpenjualanproduk as $detail)
                            @if($detail->kode_produk) <!-- Pengecekan jika kode_produk tidak null -->
                                <tr>
                                    <td style="font-size: 10px;">{{ $detail->nama_produk }}</td>
                                    <td style="font-size: 11px; text-align: right;">{{ $detail->jumlah }}</td>
                                    <td style="font-size: 11px; text-align: right;">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td style="font-size: 11px; text-align: right;">
                                        @if ($detail->diskon > 0)
                                            {{ $detail->diskon }} %
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td style="font-size: 11px; text-align: right;">{{ number_format($detail->total, 0, ',', '.') }}</td>
                                </tr>
                                @php
                                    // Validasi dan konversi data menjadi numerik
                                    $total = is_numeric($detail->total) ? $detail->total : 0;
                                    $subtotal += $total;
                                @endphp
                            @endif
                        @endforeach
                
                        <tr>
                            <td colspan="4" style="text-align: right; font-size: 11px;"><strong>Total</strong></td>
                            <td style="font-size: 11px;">{{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>  
                        <tr>
                            <td colspan="4" style="text-align: right; font-size: 11px;"><strong>DP</strong></td>
                            <td style="font-size: 11px; text-align: right;">{{ number_format($inquery->dppemesanan->dp_pemesanan, 0, ',', '.') }}</td>
                        </tr>  
                        <tr>
                            <td colspan="4" style="text-align: right; font-size: 11px;"><strong>Kekurangan</strong></td>
                            <td style="font-size: 11px; text-align: right;">
                                {{ in_array($inquery->dppemesanan->kekurangan_pemesanan, [null, 0, 1]) ? '-' : number_format($inquery->dppemesanan->kekurangan_pemesanan, 0, ',', '.') }}
                            </td>
                        </tr> 
                        
                        @if($inquery->metode_id !== null)
                            <tr>
                                <td colspan="4" style="text-align: right; font-size: 11px;"><strong>Fee {{ $inquery->metodepembayaran->nama_metode }} {{ $inquery->metodepembayaran->fee }}%</strong></td>
                                <td style="font-size: 11px; text-align: right;">
                                    @php
                                        // Menghapus semua karakter kecuali angka
                                        $total_fee = preg_replace('/[^\d]/', '', $inquery->total_fee);
                                        // Konversi ke tipe float
                                        $total_fee = (float) $total_fee;
                                    @endphp
                                    {{ number_format($total_fee, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif
                        @if($inquery->pelunasan)
                        <tr>
                            <td colspan="4" style="text-align: right; font-size: 11px;"><strong>Bayar</strong></td>
                            <td style="font-size: 11px; text-align: right;">
                                {{ number_format($inquery->pelunasan->pelunasan, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: right; font-size: 11px;"><strong>Kembali</strong></td>
                            <td style="font-size: 11px; text-align: right;">
                                {{ number_format($inquery->pelunasan->kembali, 0, ',', '.') }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="5" style="text-align: center; font-size: 11px;">Data pelunasan tidak tersedia</td>
                        </tr>
                    @endif

                        
                
                    </tbody>
                </table>   
                <div class="catatan">
                    <label style="font-size: 9px;"><strong>Catatan:</strong></label>
                    <p style="margin-top: 2px; font-size: 10px;">{!! nl2br(e($inquery->dppemesanan->pemesananproduk->catatan)) ?? '-' !!}</p>

                </div>
            </div>
        
            <div class="terimakasih">
                <p>Untuk pemesanan, kritik dan saran Hubungi.082136638004</p>
            </div>
            <div class="terimakasihd" style="text-align: left; margin-top: -15px ; font-size: 10px; font-style: italic" >
                <p>Barang yang sudah dibeli tidak bisa dikembalikan atau ditukar.</p><br> 
            </div>
            <div class="terimakasihd" style="text-align: center; margin-top: -30px" >
                <p>Terimakasih atas kunjungannya</p><br> 
            </div>
            <div class="qr" style="display: flex; justify-content: center; align-items: center; margin-top: -20px; margin-left: 101px">
                <div style="text-align: center;">
                    {!! DNS2D::getBarcodeHTML($inquery->dppemesanan->pemesananproduk->qrcode_pemesanan, 'QRCODE', 1.5, 1.5) !!}
                </div>
            </div>  
        </div>
    
    </body>
    </html>
    