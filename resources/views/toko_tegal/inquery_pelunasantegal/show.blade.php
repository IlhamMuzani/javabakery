<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak penjualan Produk</title>
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
       
        .detail-info .penjualan{
            display: flex;
            margin-top: 2px;
            margin-bottom: 2px;
            /* flex-direction: column; */
            /* border-bottom: 1px solid #ccc;
            padding-bottom: 5px; */

        }
        .detail-info .pelanggan{
            display: flex;
            margin-top: 2px;
            margin-bottom: 2px;
            /* flex-direction: column; */
            /* border-bottom: 1px solid #ccc;
            padding-bottom: 5px; */

        }
        .detail-info .kasir{
            display: flex;
            margin-top: 2px;
            margin-bottom: 2px;
            /* flex-direction: column; */
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
            margin-top: 1px;
        }
        .pelanggan p span {
            margin-top: 1px;
            
        }
        .kasir p span {
            margin-top: 1px;
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
                                <td style="font-size: 8px; text-align: right;">{{number_format($detail->total , 0, ',', '.')}}</td>
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
                                        {{number_format($total_fee, 0, ',', '.') }}
                                    </td>
                                @endif
                            </tr>
                        
                        
                        <tr>
                            <td colspan="5" style="text-align: right; font-size: 8px;"><strong>Total </strong></td>
                            <td style="font-size: 8px; text-align: right;">{{number_format($penjualan->sub_total, 0, ',', '.') }}</td>
                        </tr>
                        @if($penjualan->metode_id == Null)
                            <tr>
                                <td colspan="5" style="text-align: right; font-size: 8px;"><strong> Bayar</strong></td>
                                <td style="font-size: 8px;">{{ number_format($penjualan->bayar, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" style="text-align: right; font-size: 8px;"><strong>Kembali</strong></td>
                                <td style="font-size: 8px;">{{ number_format($penjualan->kembali, 0, ',', '.') }}</td>
                            </tr>
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

            <div class="terimakasihd" style="text-align: left; margin-top: 2px ; font-size: 10px; font-style: italic" >
                <p>Barang yang sudah dibeli tidak bisa dikembalikan atau ditukar.</p><br> 
            </div>
            <div class="terimakasihd" style="text-align: center; margin-top: -35px" >
                <p>Terimakasih atas kunjungannya</p><br> 
            </div>
            <div class="qr" style="display: flex; justify-content: center; align-items: center; margin-top: 10px; margin-left: 4px">
                <div style="text-align: center;">
                    {!! DNS2D::getBarcodeHTML($penjualan->qrcode_penjualan, 'QRCODE', 1.5, 1.5) !!}
                </div>
            </div> 
            
            <div class="d-flex justify-content-between">
                <div>
                    <a href="{{ url('toko_tegal/inquery_penjualanproduktegal') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Kembali
                    </a>
                </div>
                <div>
                    <a href="{{ route('toko_tegal.inquery_penjualanproduk.cetak-pdf', $penjualan->id) }}"  id="printButton" target="_blank" class="btn btn-primary btn-sm">
                        <i class="fas fa-print"></i> Cetak PDF
                    </a>
                </div>
            </div>
        </div>
    
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.addEventListener("keydown", function(event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    document.getElementById("printButton").click();
                }
            });
        });
    </script>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                document.getElementById("printButton").click();
            }
        });
    });
</script>
    </html>
    
