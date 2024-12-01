@extends('layouts.app')

@section('title', 'Data produk')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data perubahan harga produk</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Data produk</li>
                    </ol>
                </div>
            </div><!-- /.row -->
        </div>
        <div class="container-fluid">
            <div class="row mb-2">
              <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <form action="{{ route('admin.hargajual.filter') }}" method="GET">
                            <div class="form-row align-items-end">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="start_date">Mulai Tanggal:</label>
                                    </div>
                                    <div class="form-group mb-2">
                                        <input type="date" name="start_date" id="start_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="end_date">Sampai Tanggal:</label>
                                    </div>
                                    <div class="form-group mb-2">
                                        <input type="date" name="end_date" id="end_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary mb-2">Filter</button>
                                </div>
                            </div>
                        </form>
                        
                        
                    </div>
                </div><!-- /.row -->
                
        </div>
            </div><!-- /.row -->
        </div>
        
        <!-- /.container-fluid -->
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
                    <h3 class="card-title">Data produk</h3>
               
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="datatables1" class="table table-sm table-bordered table-striped table-hover" style="font-size: 15px;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode produk</th>
                                <th>Nama produk</th>
                                <th>Harga produk awal</th>
                                <th colspan="4" style="text-align: center;">Toko Slawi</th>
                                <th>Updated_at</th>

                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th style="text-align: center;">Member</th>
                                <th style="text-align: center;"></th>
                                <th style="text-align: center;"></th>
                                <th style="text-align: center;">Non Member</th>
                                <th></th>

                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th style="text-align: center;">Harga</th>
                                <th style="text-align: center;">Diskon (%)</th>
                                <th style="text-align: center;">Harga</th>
                                <th style="text-align: center;">Diskon (%)</th>
                                <th></th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detailtokoslawi as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->produk->kode_produk ?? '-'}}</td>
                                    <td>{{ $item->produk->nama_produk ?? '-' }}</td>
                                    <td>{{ 'Rp. ' . number_format($item->harga_awal, 0, ',', '.') }}</td>
                                    <td>{{ 'Rp. ' . number_format($item->member_harga, 0, ',', '.') }}</td>
                                    <td>{{ $item ->member_diskon }}</td>
                                    <td>{{ 'Rp. ' . number_format($item->non_member_harga, 0, ',', '.') }}</td>
                                    <td>{{ $item ->non_member_diskon}}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->updated_at)->format('d-m-Y H:i') }}</td>
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
