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
            line-height: 1.4;
            /* margin: 0; */
            margin-left: 0;
            margin-top: 0;
            /* padding: 0; */
            padding-right: 465px;
            font-size: 10x;
            background-color: #fff;
        }
            .container {
            width: 65mm; /* Adjusted width */
            margin: 0 auto;
            border: 1px solid white;
            padding: 5px;
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
            margin-top: 0px;
        }
        .section table th, .section table td {
            border: 1px solid white;
            /* padding: 5px; */
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
            font-size: 10px;
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
                <p style="font-size: 9.5px;">{{ $tokos->alamat }}</p>
            </div>
        </div>
        <hr class="divider">
        <hr class="divider">
        <div class="section">
            <h2>Struk pelunasan pemesanan</h2>
            <p style="text-align: right; font-size: 11px; margin-bottom: 10px;">
                {{ \Carbon\Carbon::parse($penjualan->tanggal_pelunasan)->locale('id')->translatedFormat('d F Y H:i') }}
            </p><br>
            <div class="detail-info">
                <div class="penjualan">
                    <p>
                        <span style="min-width: 10px; display: inline-flex; align-items: center; padding-left: 10px;">No penjualan</span>
                        <span style="min-width: 50px; display: inline-flex; align-items: center; font-size: 11px;">: {{ $penjualan->kode_penjualan }}</span>
                    </p>
                </div>
                <div class="kasir">
                    <p>
                        <span style="min-width: 60px; display: inline-flex; align-items: center; padding-left: 10px;">Kasir</span>
                        <span style="min-width: 50px; display: inline-flex; align-items: center;">: {{ $penjualan->kasir }}</span>
                    </p>
                </div>
                @if(!is_null($penjualan->nama_pelanggan))
        <div class="pelanggan">
            <p>
                <span style="min-width: 60px; display: inline-flex; align-items: center; padding-left: 10px;">Pelanggan</span>
                <span style="min-width: 50px; display: inline-flex; align-items: center; font-size: 10px;">
                    : 
                    @if (($penjualan->kode_pelangganlama || $penjualan->kode_pelanggan) && $penjualan->nama_pelanggan)
                        @php
                            // Memecah nama_pelanggan menjadi array suku kata
                            $namaArray = explode(' ', $penjualan->nama_pelanggan);
                            // Mengambil dua suku kata pertama
                            $namaSingkat = implode(' ', array_slice($namaArray, 0, 2));
                            // Menggunakan kode_pelangganlama jika ada, jika tidak gunakan kode_pelanggan
                            $kodePelanggan = $penjualan->kode_pelangganlama ?? $penjualan->kode_pelanggan;
                        @endphp
                        {{ $kodePelanggan }} / {{ $namaSingkat }}
                    @else
                        non member
                    @endif
                </span>
            </p>
        </div>
    @endif


        
                
                <table style="font-size: 12px; width: 100%; padding-left: 10px;">
                    <thead>
                        <tr>
                            <th style="font-size: 10px; width: 35%; text-align: left">Nama Produk</th>
                            <th style="font-size: 10px; width: 20%; text-align: left">Jml</th>
                            <th style="font-size: 10px; width: 25%; text-align: left">Harga</th>
                            <th style="font-size: 10px; width: 10%;">Disk</th>
                            <th style="font-size: 10px; width: 15%; padding-left: 10px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $subtotal = 0;
                        @endphp
                        @foreach($penjualan->penjualanproduk->detailpenjualanproduk as $detail)
                        
                        <tr>
                            <!-- Baris pertama untuk Nama Produk dengan pembungkusan teks -->
                            <td style="font-size: 10px; white-space: normal; overflow: hidden; text-overflow: ellipsis;" colspan="5">
                                {{ $detail->nama_produk }}
                            </td>
                        </tr>
                        <tr>
                            <!-- Baris kedua untuk Kode Produk dan tanda panah dengan padding-top untuk jarak -->
                            <td style="font-size: 9px; color: black; padding-top: 2px;">
                                {{ $detail->kode_lama }} ->
                            </td>
                            <!-- Baris kedua untuk detail kolom lainnya dengan padding-top untuk jarak -->
                            <td style="font-size: 10px; text-align: left; padding-top: 2px;">{{ $detail->jumlah }}</td>
                            <td style="font-size: 10px; text-align: left; padding-top: 2px;">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td style="font-size: 10px; text-align: right; padding-top: 2px;">
                                @if ($detail->diskon > 0)
                                    {{ $detail->diskon }}%
                                @else
                                    -
                                @endif
                            </td>
                            <td style="font-size: 10px; text-align: right; padding-top: 2px;">
                                {{ number_format($detail->total, 0, ',', '.') }}
                            </td>
                        </tr>
                
                        @php
                            $total = is_numeric($detail->total) ? $detail->total : 0;
                            $subtotal += $total;
                        @endphp
                        @endforeach
                
    
                         <tr>
                            @if($penjualan->metode_id !== null)
                                <td colspan="4" style="text-align: right; font-size: 10px; padding: 5px;">
                                    <strong>
                                        Fee {{$penjualan->metodepembayaran->nama_metode}}</strong>
                                    @if($penjualan->total_fee != 0)
                                        {{$penjualan->metodepembayaran->fee}}% 
                                    @endif <span style="color: white">llllllllllllllllll</span>
                                </td>
                                <td style="font-size: 10px; text-align: right; padding: 5px;">
                                    @if($penjualan->total_fee != 0)
                                        @php
                                            $total_fee = preg_replace('/[^\d]/', '', $penjualan->total_fee);
                                            $total_fee = (float) $total_fee;
                                        @endphp
                                        {{ number_format($total_fee, 0, ',', '.') }}
                                    @endif
                                </td>
                            @endif
                        </tr>
                       
                        <tr>
                            <td colspan="4" style="text-align: right; font-size: 11px; padding: 5px;">
                                <strong>
                                    <span style="color: black;">Total</span><span style="color: white;">lllllllllllllllllllllllll</span>
                                </strong>
                            </td>
                            <td style="font-size: 10px; text-align: right; padding: 5px;">
                                @php
                                    // Mengambil nilai sub_total dan menghapus karakter "Rp" serta spasi
                                    $numericValue = str_replace(['Rp',  ' '], '', $subtotal);
                                    // Menformat angka dengan pemisah ribuan
                                    $formattedValue = number_format($numericValue, 0, ',', '.');
                                @endphp
                                {{ $formattedValue }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: right; font-size: 11px; padding: 5px;">
                                <strong>
                                    <span style="color: black;">DP</span><span style="color: white;">lllllllllllllllllllllllll</span>
                                </strong>
                            </td>
                            <td style="font-size: 10px; text-align: right; padding: 5px;">
                                @php
                                    // Mengambil nilai DP dan menghapus karakter "Rp" serta spasi
                                    $numericValue = str_replace(['Rp',  ' '], '', $penjualan->dppemesanan->dp_pemesanan);
                                    // Menformat angka dengan pemisah ribuan
                                    $formattedValue = number_format($numericValue, 0, ',', '.');
                                @endphp
                                {{ $formattedValue }}
                            </td>
                        </tr>
                        
                   
                        <tr>
                            <td colspan="4" style="text-align: right; font-size: 11px; padding: 5px;">
                                <strong>
                                    <span style="color: black;">Kekurangan</span><span style="color: white;">lllllllllll</span>
                                </strong>
                            </td>
                            <td style="font-size: 10px; text-align: right; padding: 5px;">
                                {{ in_array($penjualan->dppemesanan->kekurangan_pemesanan, [null, 0, 1]) ? '-' : number_format($penjualan->dppemesanan->kekurangan_pemesanan, 0, ',', '.') }}

                            </td>
                        </tr> 
                        {{-- <tr>
                            <td colspan="4" style="text-align: right; font-size: 11px;"><strong>Kekurangan</strong></td>
                            <td style="font-size: 11px; text-align: right;">
                                {{ in_array($penjualan->dppemesanan->kekurangan_pemesanan, [null, 0, 1]) ? '-' : number_format($penjualan->dppemesanan->kekurangan_pemesanan, 0, ',', '.') }}
                            </td>
                        </tr>  --}}
                        
                        
                        @if($penjualan->metode_id == Null)
                        <tr>
                            <td colspan="4" style="text-align: right; font-size: 10px; padding: 5px;"> 
                                <strong>
                                    <span style="color: black;">Bayar</span><span style="color: white;">lllllllllllllllllllllllll</span>
                                </strong>
                            </td>
                            <td style="font-size: 10px; text-align: right; padding: 5px;">
                                @php
                                    // Mengambil nilai pelunasan
                                    $Bayar = $penjualan->pelunasan;
                        
                                    // Mengecek apakah nilai pelunasan adalah null, 0, atau 1
                                    if (in_array($Bayar, [null, 0, 1])) {
                                        $formattedValue = '-';
                                    } else {
                                        // Menghapus karakter "Rp" dan mengonversi string menjadi angka
                                        $numericValue = str_replace(['Rp', ' '], '', $Bayar);
                                        // Menformat angka dengan pemisah ribuan
                                        $formattedValue = number_format($numericValue, 0, ',', '.');
                                    }
                                @endphp
                                {{ $formattedValue }}
                            </td>
                        </tr>
                        tr>
                            <td colspan="4" style="text-align: right; font-size: 10px; padding: 5px;"> 
                                <strong>
                                    <span style="color: black;">Kembali</span><span style="color: white;">lllllllllllllllllllllllll</span>
                                </strong>
                            </td>
                            <td style="font-size: 10px; text-align: right; padding: 5px;">
                                @php
                                    // Mengambil nilai pelunasan
                                    $Kembali = $penjualan->kembali;
                        
                                    // Mengecek apakah nilai pelunasan adalah null, 0, atau 1
                                    if (in_array($Kembali, [null, 0, 1])) {
                                        $formattedValue = '-';
                                    } else {
                                        // Menghapus karakter "Rp" dan mengonversi string menjadi angka
                                        $numericValue = str_replace(['Rp', ' '], '', $Kembali);
                                        // Menformat angka dengan pemisah ribuan
                                        $formattedValue = number_format($numericValue, 0, ',', '.');
                                    }
                                @endphp
                                {{ $formattedValue }}
                            </td>
                        </tr>
                           
                        @elseif($penjualan->metode_bayar == 'mesinedc' || $penjualan->metode_bayar == 'gobiz')
                            <!-- Logic tambahan jika diperlukan -->
                        @endif
                    </tbody>
                </table>
                
                <table style="width: 100%; font-size: 12px; text-align: right;">
                    @if($penjualan->metode_id !== NULL)
                    <tr>
                        <td style="font-size: 10px; word-wrap: break-word; text-align: right;">
                         <strong> No.<span style="color: white">llllllllllllllllll</span> </strong> {{ $penjualan->keterangan }}
                        </td>
                    </tr>
                    @endif
                </table>
                
                
            </div>
        
            @if(!is_null($penjualan->catatan))
                <div class="catatan">
                    <label>Catatan:</label>
                    <p>{{$penjualan->catatan}}</p>
                </div>
            @endif
        
            @if(preg_replace('/[^0-9]/', '', $penjualan->sub_total) < preg_replace('/[^0-9]/', '', $penjualan->sub_totalasli))
                <div class="hemat" style="margin-top: 4;">
                    <label style="font-size: 11px;">Anda mendapatkan diskon: </label>
                    <span style="font-size: 11px;"><strong>{{'Rp. ' .  number_format(preg_replace('/[^0-9]/', '', $penjualan->sub_totalasli) - preg_replace('/[^0-9]/', '', $penjualan->sub_total), 0, ',', '.') }}</strong></span>
                </div>
            @endif
            <div class="terimakasih">
                <p>Untuk pemesanan, kritik dan saran hubungi 082136638003.</p>
            </div>
        
            <div class="terimakasihd" style="text-align: left; margin-top: -15px; font-size: 10px; font-style: italic">
                <p>Barang yang sudah dibeli tidak bisa dikembalikan atau ditukar.</p><br> 
            </div>
            <div class="terimakasihd" style="text-align: center; font-size: 12px; margin-top: -35px">
                <p>Terimakasih atas kunjungannya</p><br> 
            </div>
            <div class="qr" style="display: flex; justify-content: center; align-items: center; margin-top: -10px; margin-left: 100px">
                <div style="text-align: center;">
                    {!! DNS2D::getBarcodeHTML($penjualan->dppemesanan->pemesananproduk->qrcode_pemesanan, 'QRCODE', 1.5, 1.5) !!}
                </div>
            </div>  
        </div>
        
        
        
    </body>
    </html>
    