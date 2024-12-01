@extends('layouts.app')

@section('title', 'Lihat Karyawan')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">produk</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin/produk') }}">produk</a>
                    </li>
                    <li class="breadcrumb-item active">Lihat</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lihat Produk</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">

                    <div class="col-md-6">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Qr Code</strong>
                            </div>
                            <div class="col-md-4">
                                <div data-toggle="modal" data-target="#modal-qrcode-{{ $produk->id }}"
                                    style="display: inline-block;">
                                    {!! DNS2D::getBarcodeHTML("$produk->qrcode_produk", 'QRCODE', 3, 3) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Foto</strong>
                            </div>
                            
                                @if ($produk->gambar)
                                    <img src="{{ asset('storage/uploads/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}" style="width: 100px; height: auto;">
                                @else
                                    <img src="{{ asset('adminlte/dist/img/img-placeholder.jpg') }}" alt="{{ $produk->nama_produk }}" style="width: 100px; height: auto;">
                                @endif
                        
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Produk</strong>
                            </div>
                            <div class="col-md-4">
                                {{ $produk->nama_produk }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Kode</strong>
                            </div>
                            <div class="col-md-4">
                                {{ $produk->kode_lama }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Harga</strong>
                            </div>
                            <div class="col-md-4">
                                {{ $produk->harga }}
                            </div>
                        </div>
                        

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection