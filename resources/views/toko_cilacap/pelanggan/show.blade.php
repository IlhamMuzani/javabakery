@extends('layouts.app')

@section('title', 'Lihat Pelanggan')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Kartu Member</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ url('toko_cilacap/pelanggan') }}">Pelanggan</a>
                        </li>
                        <li class="breadcrumb-item active">Lihat</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

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
            background-image: url('storage/uploads/icon/bakery.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .qr-code {
            position: absolute;
            bottom: 90px;
            right: 20px;
        }
        .qr-code img {
            max-width: 200px;
        }
        .info {
            margin-bottom: 10px;
        }
        .info label {
            position: absolute;
            top: 10px;
            right: 20px;
            font-weight: bold;
            font-size: 200%;
        }
        .info1 {
            position: absolute;
            top: 150px;
            left: 50px;
            font-weight: bold;
            font-size: 200%;
        }
        .info2 {
            position: absolute;
            bottom: 60px;
            right: 20px;
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
    </style>

    <section class="content">
        <div class="container-fluid">
            <div class="card mb-3" id="printCard">
                <div class="card-body">

                    <div class="logo">
                        <img src="{{ asset('storage/uploads/icon/depan.jpeg') }}">
                    </div>

                    <div class="info2">
                        <span>{{ $pelanggan->kode_pelanggan }}</span>
                    </div>
                    <div class="qr-code" data-bs-toggle="modal" data-bs-target="#modal-qrcode-{{ $pelanggan->id }}">
                        {!! DNS2D::getBarcodeHTML($pelanggan->qrcode_pelanggan, 'QRCODE', 2, 2) !!}
                    </div>
                </div>
            </div>  
            <a href="{{ route('pelanggan.cetak_pdf', $pelanggan->id) }}" class="btn btn-primary" target="_blank">CETAK</a>
        </div>
    </section>

    <script>
        function printCard() {
            var printContents = document.getElementById('printCard').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
@endsection
