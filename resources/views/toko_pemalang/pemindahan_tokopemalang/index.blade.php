@extends('layouts.app')

@section('title', 'Data produk')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pemindahan Produk Toko Pemalang</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Pemindahan Produk Toko Pemalang</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: '{{ session('success') }}',
                            timer: 1000,
                            showConfirmButton: false
                        });
                    });
                </script>
            @endif
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pemindahan Produk Toko Pemalang</h3>
                    <div class="float-right">
                        <a href="{{ url('toko_pemalang/pemindahan_tokopemalang/create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> 
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="datatables1" class="table table-bordered" style="font-size: 13px">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Pemindahan</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pemindahan_tokopemalang as $stok)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $stok->kode_pemindahan }}</td>
                                    <td>{{ $stok->produk->kode_produk }}</td>
                                    <td>{{ $stok->produk->nama_produk }}</td>
                                    <td style="text-transform: uppercase;">
                                        @if ($stok->keterangan === 'oper')
                                            {{ $stok->keterangan }} - {{ $stok->oper ?? '-' }} <!-- Tampilkan keterangan dan oper jika keterangan adalah oper -->
                                        @else
                                            {{ $stok->keterangan }} <!-- Tampilkan hanya keterangan jika bukan oper -->
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>
    <!-- /.card -->
@endsection
