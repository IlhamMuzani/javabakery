<!DOCTYPE html>
<html lang="en">

<head>
    {{-- <link rel="stylesheet" href="{{ asset('falcon/style.css') }}"> --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Produk</title>

    <style type="text/css">
        /* Reset all margins and padding */
        * {
            margin: 0;
            padding: 0;
        }

        .box {
            margin-left: 0px;
            margin-top: 0px;
        }
        .text-container {
            position: relative;
            margin-right: 760px;
            margin-left: 2px;
            margin-top: 10px;
            transform: rotate(90deg);   
        }
        .box1 {
            margin-left: 0px;
            margin-top: 40px;
        }
        .text-container1 {
            position: relative;
            margin-right: 760px;
            margin-left: 2px;
            margin-top: 10px;
            transform: rotate(90deg);   
        }
        .box2 {
            margin-left: 0px;
            margin-top: 35px;
        }
        .text-container2 {
            position: relative;
            margin-right: 760px;
            margin-left: 2px;
            margin-top: 10px;
            transform: rotate(90deg);   
        }

        .text {
            font-size: 7px
        }

        .bold-text {
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif
        }
        .truncate {
            width: 70px; /* Atur lebar sesuai kebutuhan */
            overflow: hidden; /* Menyembunyikan teks yang melimpah */
            white-space: nowrap; /* Mencegah teks untuk terbungkus */
            text-overflow: ellipsis; /* Menampilkan elipsis jika teks terpotong */
        }

    </style>

</head>

<body>
    <div>
        <div class="box">
            <?php
            use BaconQrCode\Renderer\ImageRenderer;
            use BaconQrCode\Writer;
            
            // Ubah tautan menjadi QR code
            $qrcode = new Writer(new ImageRenderer(new \BaconQrCode\Renderer\RendererStyle\RendererStyle(50), new \BaconQrCode\Renderer\Image\SvgImageBackEnd()));
            $qrcodeData = $qrcode->writeString($produk->qrcode_produk);
            
            // Tampilkan gambar QR code
            echo '<img src="data:image/png;base64,' . base64_encode($qrcodeData) . '" />';
            ?>
        </div>
        <div class="text-container">
            <div class="text">
                <p class="bold-text">{{ $produk->kode_lama }}</p>
                <p class="bold-text truncate">{{ $produk->nama_produk }}</p>
                <p style="font-size: 9px;" class="bold-text">Rp. {{ number_format($produk->harga, 0, ',', '.') }}</p>
                <p class="bold-text" style="color: white">A1</p>
            </div>
        </div>

        <div class="box1">
            <?php
            // Ubah tautan menjadi QR code
            $qrcode = new Writer(new ImageRenderer(new \BaconQrCode\Renderer\RendererStyle\RendererStyle(50), new \BaconQrCode\Renderer\Image\SvgImageBackEnd()));
            $qrcodeData = $qrcode->writeString($produk->qrcode_produk);
            
            // Tampilkan gambar QR code
            echo '<img src="data:image/png;base64,' . base64_encode($qrcodeData) . '" />';
            ?>
        </div>
        <div class="text-container1">
            <div class="text">
                <p class="bold-text">{{ $produk->kode_lama }}</p>
                <p class="bold-text truncate">{{ $produk->nama_produk }}</p>
                <p style="font-size: 9px;" class="bold-text">Rp. {{ number_format($produk->harga, 0, ',', '.') }}</p>
                <p class="bold-text" style="color: white">A1</p>
            </div>
        </div>

        <div class="box2">
            <?php
            // Ubah tautan menjadi QR code
            $qrcode = new Writer(new ImageRenderer(new \BaconQrCode\Renderer\RendererStyle\RendererStyle(50), new \BaconQrCode\Renderer\Image\SvgImageBackEnd()));
            $qrcodeData = $qrcode->writeString($produk->qrcode_produk);
            
            // Tampilkan gambar QR code
            echo '<img src="data:image/png;base64,' . base64_encode($qrcodeData) . '" />';
            ?>
        </div>
        <div class="text-container2">
            <div class="text">
                <p class="bold-text">{{ $produk->kode_lama }}</p>
                <p class="bold-text truncate">{{ $produk->nama_produk }}</p>
                <p style="font-size: 9px;" class="bold-text">Rp. {{ number_format($produk->harga, 0, ',', '.') }}</p>
                <p class="bold-text" style="color: white">A1</p>
            </div>
        </div>

      
        
    </div>
</body>

</html>
