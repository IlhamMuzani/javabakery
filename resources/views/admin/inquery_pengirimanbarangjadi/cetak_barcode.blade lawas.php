<!DOCTYPE html>
<html lang="en">

<head>
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
        <div class="text-container1">
            <div class="text">
                <p class="bold-text">{{ $produk->kode_lama }}</p>
        
                @php
                    // Membagi nama produk menjadi blok-blok yang berisi maksimal 12 karakter
                    $chunks = str_split($produk->nama_produk, 15);
                @endphp
        
                <!-- Menampilkan setiap bagian nama produk dengan batasan 12 karakter per baris -->
                @foreach ($chunks as $chunk)
                    <p style="font-size: 6px;" class="bold-text truncate">{{ $chunk }}</p>
                @endforeach
        
                <p style="font-size: 7px;" class="bold-text">Rp. {{ number_format($produk->harga, 0, ',', '.') }}</p>
                <p class="bold-text">B6</p>
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
        
                @php
                    // Membagi nama produk menjadi blok-blok yang berisi maksimal 12 karakter
                    $chunks = str_split($produk->nama_produk, 15);
                @endphp
        
                <!-- Menampilkan setiap bagian nama produk dengan batasan 12 karakter per baris -->
                @foreach ($chunks as $chunk)
                    <p style="font-size: 6px;" class="bold-text truncate">{{ $chunk }}</p>
                @endforeach
        
                <p style="font-size: 7px;" class="bold-text">Rp. {{ number_format($produk->harga, 0, ',', '.') }}</p>
                <p class="bold-text">B6</p>
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
        <div class="text-container1">
            <div class="text">
                <p class="bold-text">{{ $produk->kode_lama }}</p>
        
                @php
                    // Membagi nama produk menjadi blok-blok yang berisi maksimal 12 karakter
                    $chunks = str_split($produk->nama_produk, 15);
                @endphp
        
                <!-- Menampilkan setiap bagian nama produk dengan batasan 12 karakter per baris -->
                @foreach ($chunks as $chunk)
                    <p style="font-size: 6px;" class="bold-text truncate">{{ $chunk }}</p>
                @endforeach
        
                <p style="font-size: 7px;" class="bold-text">Rp. {{ number_format($produk->harga, 0, ',', '.') }}</p>
                <p class="bold-text">B6</p>
            </div>
        </div>

      
        
    </div>
</body>

{{-- <body>
    @foreach (range(1, $jumlah) as $i)
    <div>
        <div class="box">
            <!-- Menampilkan gambar QR code -->
            <img src="data:image/png;base64,{{ $qrcodeData }}" />
        </div>
        
        <div class="text-container1">
            <div class="text">
                <p class="bold-text">{{ $produk->kode_lama }}</p>

                @php
                    // Membagi nama produk menjadi blok-blok yang berisi maksimal 15 karakter
                    $chunks = str_split($produk->nama_produk, 15);
                @endphp

                @foreach ($chunks as $chunk)
                    <p style="font-size: 6px;" class="bold-text truncate">{{ $chunk }}</p>
                @endforeach

                <p style="font-size: 7px;" class="bold-text">Rp. {{ number_format($produk->harga, 0, ',', '.') }}</p>
                <p class="bold-text">B5</p>
            </div>
        </div>
    </div>
    @endforeach
</body> --}}



</html>



