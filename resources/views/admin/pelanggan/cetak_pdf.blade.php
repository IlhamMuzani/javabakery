

<!DOCTYPE html>
<html>
<head>
    <style>
        .card {
            width: 500px;
            height: 270px; 
            background-color: rgb(255, 255, 255);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin: 0 auto;
            position: relative;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .qr-code {
            position: absolute;
            top: 130px;
            right: 70px;
        }

        .qr-code img {
            max-width: 200px;
        }

        .info2 {
            position: absolute;
            top: 190px;
            right: 68px;
            font-weight: bold;
            font-size: 130%;
            font-family: monospace;
        }

        .logo {
            position: absolute;
            top: 0px;
            left: 0px;
        }

        .logo img {
            width: 500px;
            height: 270px;
            margin: 0 auto;
            background-size: cover;
        }

        @media print {
    /* Menghilangkan fitur pecahan halaman */
    @page {
        size: auto; /* atau sesuaikan dengan ukuran kertas yang Anda inginkan */
        margin: 0;
    }
}

    </style>
</head>
<body>
    <div class="card">
        <div class="card-body">

            <div class="logo">
                <img src="{{ asset('storage/uploads/icon/depan.jpeg') }}">
            </div>

            <div class="info2">
                <span>{{ $pelanggan->kode_pelanggan }}</span>
            </div>

            <div class="qr-code">
                {!! DNS2D::getBarcodeHTML($pelanggan->qrcode_pelanggan, 'QRCODE', 2, 2) !!}
            </div>

        </div>
    </div>
</body>


</html>
