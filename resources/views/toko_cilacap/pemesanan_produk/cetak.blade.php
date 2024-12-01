<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pemesanan Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

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
            background-color: #f2f2f2;
        }
            .container {
            width: 80mm; /* Adjusted width */
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
            margin-top: 15px;
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
            font-size: 7px;
            margin-top: 5px;
            margin-left: 40px;
            text-align: center;

        }
        .signature3 {
            text-align: right;
            font-size: 6px;
            margin-top: 5px;
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
            font-size: 8px;
        }
        .detail-info p span {
            flex: 1;
            text-align: left;
            font-size: 8px;
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

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="text">
                <h1>PT JAVA BAKERY FACTORY</h1>
                <p>Cabang : {{ $tokos->nama_toko ?? 'Nama toko tidak tersedia' }}</p>
                <p>{{ $tokos->alamat }}</p>
            </div>
        </div>
        <hr class="divider">
        <hr class="divider">
        <div class="section">
            <h2>Struk Pemesanan Produk</h2>
            <p style="text-align: right; font-size: 8px;">
                {{ \Carbon\Carbon::parse($pemesanan->tanggal_pemesanan)->locale('id')->translatedFormat('l, d F Y H:i') }}
            </p><br>
            <div class="detail-info">
                <div class="pemesanan">
                    <p><span style="min-width: 100px; display: inline-flex; align-items: center;">No Pemesanan</span><span style="min-width: 100px; display: inline-flex; align-items: center;">: {{ $pemesanan->kode_pemesanan }}</span></p>
                </div>
                <div class="kasir">
                    <p><span style="min-width: 100px; display: inline-flex; align-items: center;">Kasir</span><span style="min-width: 100px; display: inline-flex; align-items: center;">: {{ ucfirst(auth()->user()->karyawan->nama_lengkap) }}</span></p>
                </div>
                <div class="pelanggan">
                    <p><span style="min-width: 100px; display: inline-flex; align-items: center;">Pelanggan</span><span style="min-width: 100px; display: inline-flex; align-items: center;">: {{ $pemesanan->nama_pelanggan }}</span></p>
                </div>
<hr style="margin-bottom: 2px; margin-top: 2px;">
                <h3 class="pengiriman" style="text-decoration: underline;"></h3>
                    <div class="pelanggan">
                        <p><span style="min-width: 100px; display: inline-flex; align-items: center;">Penerima</span><span style="min-width: 100px; display: inline-flex; align-items: center;">: {{ $pemesanan->nama_penerima ?? $pemesanan->nama_pelanggan }}</span></p>
                    </div>
                    <div class="telepon">
                        <p><span style="min-width: 100px; display: inline-flex; align-items: center;">No Telp Pnerima</span><span style="min-width: 100px; display: inline-flex; align-items: center;">: 0{{ $pemesanan->telp_penerima ?? $pemesanan->telp }}</span></p>
                    </div>
                    {{-- <div class="alamat">
                        <p><span style="min-width: 100px; display: inline-flex; align-items: center;">Alamat Pengiriman</span><span style="min-width: 100px; display: inline-flex; align-items: center;"><span>: {{ $pemesanan->alamat_penerima ?? $pemesanan->alamat }}</span></p>
                    </div> --}}
                    <div class="alamat">
                        <p><span style="min-width: 100px; display: inline-flex; align-items: center;">Tanggal Pengiriman</span><span style="min-width: 100px; display: inline-flex; align-items: center;"><span>: {{ $pemesanan->tanggal_kirim }}</span></p>
                    </div>

                <h3 class="pemesanan" style="text-decoration: underline;"></h3>
                @if($pemesanan->detailpemesananproduk->isEmpty())
                    <p>Tidak ada detail pemesanan produk.</p>
                @else
                <table style="font-size: 12px; width: 100%;">
                    <thead>
                        <tr>
                            <th style="font-size: 8px;">Kode Produk</th>
                            <th style="font-size: 8px;">Nama Produk</th>
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
                        @foreach($pemesanan->detailpemesananproduk as $detail)
                            <tr>
                                <td style="font-size: 8px;">{{ $detail->kode_lama }}</td>
                                <td style="font-size: 8px;">{{ $detail->nama_produk }}</td>
                                <td style="font-size: 8px;">{{ $detail->jumlah }}</td>
                                <td style="font-size: 8px;">{{'Rp.'. number_format($detail->harga, 0, ',', '.') }}</td>
                                <td style="font-size: 8px;">
                                    @if ($detail->diskon > 0)
                                        {{ $detail->diskon }}%
                                    @else
                                        -
                                    @endif
                                </td>
                                <td style="font-size: 8px;">{{'Rp.'.  number_format($detail->total , 0, ',', '.')}}</td>
                            </tr>
                            @php
                                // Validasi dan konversi data menjadi numerik
                                $total = is_numeric($detail->total) ? $detail->total : 0;
                                $subtotal += $total;
                            @endphp
                        @endforeach

                        <tr>
                            @if($pemesanan->metode_id !== null)
                                <td colspan="5" style="text-align: right; font-size: 8px;"><strong> Fee {{$pemesanan->metodepembayaran->nama_metode}} {{$pemesanan->metodepembayaran->fee}}%</strong></td>
                                <td style="font-size: 8px; text-align: right;">
                                    @php
                                        // Menghapus semua karakter kecuali angka
                                        $total_fee = preg_replace('/[^\d]/', '', $pemesanan->total_fee);
                                        // Konversi ke tipe float
                                        $total_fee = (float) $total_fee;
                                    @endphp
                                    {{ 'Rp. ' . number_format($total_fee, 0, ',', '.') }}
                                </td>
                            @endif
                        </tr>

                        <tr>
                            <td colspan="5" style="text-align: right; font-size: 8px;"><strong>Total Bayar</strong></td>
                            <td style="font-size: 8px;">{{'Rp.'.  number_format($pemesanan->sub_total, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: right; font-size: 8px;"><strong>DP </strong></td>
                            <td style="font-size: 8px;">
                                @if($dp)
                                    {{'Rp.'.  number_format($dp->dp_pemesanan, 0, ',', '.') }}
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        
                        <tr>
                            <td colspan="5" style="text-align: right; font-size: 8px;"><strong>Kekurangan  </strong></td>
                            <td style="font-size: 8px;">{{'Rp.'.  number_format($dp->kekurangan_pemesanan, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                    
                </table>
                @endif
            </div>
            <div class="catatan">
                <label>Catatan:</label>
                <p>{{$pemesanan->catatan ?? '-'}}</p>
            </div>

            @if(preg_replace('/[^0-9]/', '', $pemesanan->sub_total) < preg_replace('/[^0-9]/', '', $pemesanan->sub_totalasli))
            <div class="hemat">
                <label>Anda telah hemat: </label>
                <span><strong>{{ 'Rp. ' . number_format(preg_replace('/[^0-9]/', '', $pemesanan->sub_totalasli) - preg_replace('/[^0-9]/', '', $pemesanan->sub_total), 0, ',', '.') }}</strong></span>
            </div>
            @endif
            <div class="terimakasih">
                <p>Untuk pemesanan, kritik dan saran Hubungi.082136638003</p>
            </div>
           
            <div class="note" style="text-align: left; margin-top: -5px ; font-size:9px; font-style: italic" >
                <p>Down Payment(DP) yang sudah masuk tidak bisa diambil/ditukar dengan uang tunai/cash</p><br> 
            </div>
            <div class="terimakasihd" style="text-align: center; margin-top: -20px" >
                <p>Terimakasih atas kunjungannya</p><br> 
            </div>
            <div class="qr" style="display: flex; justify-content: center; align-items: center; margin-top: -20px; margin-left: -14px">
                <div style="text-align: center;">
                    {!! DNS2D::getBarcodeHTML($pemesanan->qrcode_pemesanan, 'QRCODE', 1.5, 1.5) !!}
                </div>
            </div> 
            
            <div class="d-flex justify-content-between">
                <div>
                    <a href="{{ url('toko_cilacap/pemesanan_produk') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Kembali
                    </a>
                </div>
                <div>
                    <a href="{{ route('toko_cilacap.pemesanan_produk.cetak-pdf', $pemesanan->id) }}" target="_blank" class="btn btn-primary btn-sm">
                        <i class="fas fa-print"></i> Cetak PDF
                    </a>
                </div>
            </div>
        </div>
    
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    </html>
    
