<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pemesanan Produk</title>
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
            font-size: 10px;
            background-color: white;
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
            margin-top: 5px;
        }
        .section table th, .section table td {
            border: 1px solid white;
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
            font-size: 9px;
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
                <p style="font-size: 10px;">{{ $tokos->alamat }}</p>
            </div>
        </div>
        <hr class="divider">
        <hr class="divider">
        <div class="section">
            <h2>Struk Pemesanan</h2>
            <p style="text-align: right; font-size: 11px;">
                {{ \Carbon\Carbon::parse($pemesanan->tanggal_pemesanan)->locale('id')->translatedFormat('d F Y H:i') }}
            </p><br>
            <div class="detail-info">
                <div class="pemesanan">
                    <p><span style="min-width: 50px; display: inline-flex; align-items: center;">No Pemesanan</span>
                       <span style="min-width: 100px; display: inline-flex; align-items: center; font-size: 11px;">: {{ $pemesanan->kode_pemesanan }}</span></p>
                </div>
                <div class="kasir">
                    <p><span style="min-width: 69px; display: inline-flex; align-items: center;">Kasir</span>
                       <span style="min-width: 100px; display: inline-flex; align-items: center;">: {{ ucfirst(auth()->user()->karyawan->nama_lengkap) }}</span></p>
                </div>
                <div class="pelanggan">
                    <p><span style="min-width: 69px; display: inline-flex; align-items: center;">Pelanggan</span>
                       <span style="min-width: 100px; display: inline-flex; align-items: center;">: {{ $pemesanan->nama_pelanggan }}</span></p>
                </div>
<hr>
                <h3 class="pengiriman" style="text-decoration: underline;"></h3>
                    <div class="pelanggan">
                        <p><span style="min-width: 69px; display: inline-flex; align-items: center;">Penerima</span>
                            <span style="min-width: 100px; display: inline-flex; align-items: center;">: {{ $pemesanan->nama_penerima ?? $pemesanan->nama_pelanggan }}</span></p>
                    </div>
                    <div class="telepon">
                        <p><span style="min-width: 69px; display: inline-flex; align-items: center;">No Telp</span>
                            <span style="min-width: 100px; display: inline-flex; align-items: center;">: 0{{ $pemesanan->telp_penerima ?? $pemesanan->telp }}</span></p>
                    </div>
                    <div class="alamat">
                        <p><span style="min-width: 69px; display: inline-flex; align-items: center;">Tanggal Ambil</span>
                            <span style="min-width: 100px; display: inline-flex; align-items: center; font-size: 11px;"><span>: {{ \Carbon\Carbon::parse($pemesanan->tanggal_kirim)->locale('id')->translatedFormat('d F Y H:i') }}
                        </span></p>
                    </div>

                <h3 class="pemesanan" style="text-decoration: underline;"></h3>
                @if($pemesanan->detailpemesananproduk->isEmpty())
                    <p>Tidak ada detail pemesanan produk.</p>
                @else
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
                    {{-- <tbody>
                        @php
                            $subtotal = 0;
                        @endphp
                        @foreach($pemesanan->detailpemesananproduk as $detail)
                        @php
                           // Membagi nama produk menjadi array dengan panjang maksimum 7 karakter
                           $nama_produk = wordwrap($detail->nama_produk, 15, "\n", true);
                       @endphp
                            <tr>
                                <td style="font-size: 10px; word-wrap: break-word; white-space: pre-line;">{{ $nama_produk }}</td>
                                <td style="font-size: 11px; text-align: right">{{ $detail->jumlah }}</td>
                                <td style="font-size: 11px; text-align: right">{{number_format($detail->harga, 0, ',', '.') }}</td>
                                <td style="font-size: 11px; text-align: right">
                                    @if ($detail->diskon > 0)
                                        {{ $detail->diskon }} %
                                    @else
                                        -
                                    @endif
                                </td>
                                <td style="font-size: 11px;">{{number_format($detail->total , 0, ',', '.')}}</td>
                            </tr>
                            @php
                                // Validasi dan konversi data menjadi numerik
                                $total = is_numeric($detail->total) ? $detail->total : 0;
                                $subtotal += $total;
                            @endphp
                        @endforeach

                        <tr>
                            @if($pemesanan->metode_id !== null)
                                <td colspan="4" style="text-align: right; font-size: 11px;"><strong> Fee {{$pemesanan->metodepembayaran->nama_metode}} {{$pemesanan->metodepembayaran->fee}}%</strong></td>
                                <td style="font-size: 11px; text-align: right;">
                                    @php
                                        // Menghapus semua karakter kecuali angka
                                        $total_fee = preg_replace('/[^\d]/', '', $pemesanan->total_fee);
                                        // Konversi ke tipe float
                                        $total_fee = (float) $total_fee;
                                    @endphp
                                    {{ number_format($total_fee, 0, ',', '.') }}
                                </td>
                            @endif
                        </tr>
                        @if($pemesanan->metode_id !== NULL)
                        <tr>
                            <td colspan="4" style="text-align: right; font-size: 11px;"><strong>No. </strong></td>
                            <td style="font-size: 11px;">{{$pemesanan->keterangan}}</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="4" style="text-align: right; font-size: 11px;"><strong>Total </strong></td>
                            <td style="font-size: 11px; text-align: right;">{{number_format($pemesanan->sub_total, 0, ',', '.') }}</td>
                            
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: right; font-size: 11px;"><strong>DP </strong></td>
                            <td style="font-size: 11px; text-align: right;">{{number_format($dp->dp_pemesanan, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: right; font-size: 11px;"><strong>Kekurangan  </strong></td>
                            <td style="font-size: 11px; text-align: right;">{{number_format($dp->kekurangan_pemesanan, 0, ',', '.') }}</td>
                        </tr>
                    </tbody> --}}
                    <tbody>
                        @php
                            $subtotal = 0;
                        @endphp
                        @foreach($pemesanan->detailpemesananproduk as $detail)
                        
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
                                    {{ $detail->diskon }}
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
                            @if($pemesanan->metode_id !== null)
                                <td colspan="4" style="text-align: right; font-size: 11px;"><strong> Fee {{$pemesanan->metodepembayaran->nama_metode}} {{$pemesanan->metodepembayaran->fee}}%</strong></td>
                                <td style="font-size: 11px; text-align: right;">
                                    @php
                                        // Menghapus semua karakter kecuali angka
                                        $total_fee = preg_replace('/[^\d]/', '', $pemesanan->total_fee);
                                        // Konversi ke tipe float
                                        $total_fee = (float) $total_fee;
                                    @endphp
                                    {{ number_format($total_fee, 0, ',', '.') }}
                                </td>
                            @endif
                        </tr>
                        {{-- @if($pemesanan->metode_id !== NULL)
                        <tr>
                            <td colspan="4" style="text-align: right; font-size: 11px;"><strong>No. </strong></td>
                            <td style="font-size: 11px;">{{$pemesanan->keterangan}}</td>
                        </tr>
                        @endif --}}
                        <tr>
                            <td colspan="4" style="text-align: right; font-size: 11px;"><strong>Total </strong></td>
                            <td style="font-size: 11px; text-align: right;">{{number_format($pemesanan->sub_total, 0, ',', '.') }}</td>
                            
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: right; font-size: 11px;"><strong>DP </strong></td>
                            <td style="font-size: 11px; text-align: right;">{{number_format($dp->dp_pemesanan, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: right; font-size: 11px;"><strong>Kekurangan  </strong></td>
                            <td style="font-size: 11px; text-align: right;">{{number_format($dp->kekurangan_pemesanan, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
                @endif
                <table style="width: 100%; font-size: 12px; text-align: right;">
                    @if($pemesanan->metode_id !== NULL)
                    <tr>
                        <td style="font-size: 10px; word-wrap: break-word; text-align: right;">
                         <strong> No.<span style="color: white">llllllllllllllllll</span> </strong> {{ $pemesanan->keterangan }}
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
 
            <div class="catatan">
                <label>Catatan:</label>
                <p style="margin-top: 2px; font-size: 10px;">{!! nl2br(e($pemesanan->catatan)) ?? '-' !!}</p>
            </div>
            @if(preg_replace('/[^0-9]/', '', $pemesanan->sub_total) < preg_replace('/[^0-9]/', '', $pemesanan->sub_totalasli))
            <div class="hemat">
                <label>Anda telah hemat: </label>
                <span><strong>{{ 'Rp. ' . number_format(preg_replace('/[^0-9]/', '', $pemesanan->sub_totalasli) - preg_replace('/[^0-9]/', '', $pemesanan->sub_total), 0, ',', '.') }}</strong></span>
            </div>
            @endif
            <div class="terimakasih">
                <p>Untuk pemesanan, kritik dan saran Hubungi.082136638004</p>
            </div>
           
            <div class="note" style="text-align: left; margin-top: -15px ; font-size:9px; font-style: italic" >
                <p>Down Payment(DP) yang sudah masuk tidak bisa diambil / ditukar dengan uang tunai/cash</p><br> 
            </div>
            <div class="terimakasihd" style="text-align: center; margin-top: -30px" >
                <p>Terimakasih atas kunjungannya</p><br> 
            </div>
            <div class="qr" style="display: flex; justify-content: center; align-items: center; margin-top: -20px; margin-left: 101px">
                <div style="text-align: center;">
                    {!! DNS2D::getBarcodeHTML($pemesanan->qrcode_pemesanan, 'QRCODE', 1.5, 1.5) !!}
                </div>
            </div>  
        </div>
    
    </body>
    </html>
    