<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Barcode Produk</title>

    <style type="text/css">
        /* Reset all margins and padding */
        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            padding: 20px;
        }

        .page-break {
            page-break-after: always;
        }

        .product-box {
            margin: 10px;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            border: 1px solid #ddd; /* Border untuk kotak produk */
            padding: 10px; /* Padding di dalam kotak produk */
            border-radius: 5px; /* Sudut yang membulat */
        }

        .qrcode-box {
            width: 60px;
            height: 60px;
            margin-right: 10px;
        }

        .product-info {
            font-size: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center; /* Vertikal center */
        }

        .bold-text {
            font-weight: bold;
        }

        .truncate {
            width: 70px; /* Atur lebar sesuai kebutuhan */
            overflow: hidden; /* Menyembunyikan teks yang melimpah */
            white-space: nowrap; /* Mencegah teks untuk terbungkus */
            text-overflow: ellipsis; /* Menampilkan elipsis jika teks terpotong */
        }

        /* Tambahan styling untuk responsif */
        @media print {
            .product-box {
                page-break-inside: avoid; /* Mencegah pemecahan halaman di dalam kotak produk */
            }
        }
    </style>
</head>

<body>
    @foreach ($outputProdukList as $produk)
    <div class="product-box">
        <div class="qrcode-box">
            <img src="data:image/png;base64,{{ $produk->qrcode_base64 }}" style="width: 100%; height: auto;" />
        </div>

        <div class="product-info">
            <p class="bold-text">{{ $produk->kode_lama }}</p>
            <p class="bold-text truncate">{{ $produk->nama_produk }}</p>
            <p class="bold-text">Rp. {{ number_format($produk->harga, 0, ',', '.') }}</p>
            <p class="bold-text">{{ $produk->kode_produksi ?? 'Kode produksi tidak tersedia' }}</p>
            <p class="bold-text">Jumlah: {{ $produk->jumlah }}</p> <!-- Tampilkan jumlah -->
        </div>
    </div>

    @if ($loop->iteration % 3 == 0 && !$loop->last)
    <div class="page-break"></div>
    @endif

    @endforeach
</body>

</html>
