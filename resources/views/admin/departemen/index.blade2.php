<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Pelanggan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .card {
            width: 300px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin: 0 auto;
        }
        .qr-code {
            text-align: center;
            margin-bottom: 20px;
        }
        .qr-code img {
            max-width: 100%;
        }
        .info {
            margin-bottom: 10px;
        }
        .info label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="qr-code">
            <!-- Let's assume $qrCodeUrl is the URL or data for your QR Code image -->
            {{-- <img src="{{ $qrCodeUrl }}" alt="QR Code"> --}}
        </div>
        <div class="info">
            <label for="kode_pelanggan">No. Member:</label>
            {{-- <span>{{ $kodePelanggan }}</span> --}}
        </div>
        <div class="info">
            <label for="nama_pelanggan">Nama Pelanggan:</label>
            {{-- <span>{{ $namaPelanggan }}</span> --}}
        </div>
        <div class="info">
            <label for="email">Email:</label>
            {{-- <span>{{ $email }}</span> --}}
        </div>
        <div class="info">
            <label for="telp">Telepon:</label>
            {{-- <span>{{ $telepon }}</span> --}}
        </div>
        <div class="info">
            <label for="alamat">Alamat:</label>
            {{-- <span>{{ $alamat }}</span> --}}
        </div>
    </div>
</body>
</html>
