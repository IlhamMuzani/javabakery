<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Produk</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Sesuaikan dengan gaya CSS Anda -->
    <style>
        .container {
            display: flex;
            padding-left: 5%;
            align-items: center; /* Vertically center the items */
        }
        .product-name {
            margin-right: 20px; /* Memberi jarak antara nama dan QR code */
        }
    </style>
</head>
<body>

    <div class="container">
        <p>
            {!! DNS2D::getBarcodeHTML("$produk->qrcode_produk", 'QRCODE', 13, 13) !!}
        </p>
    </div>
</body>
</html>
