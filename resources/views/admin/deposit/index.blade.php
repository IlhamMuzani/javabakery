@extends('layouts.app')

@section('title', 'Data Deposit')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Deposit</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Data Deposit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
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
                {{-- <div class="card-header">
                    <h3 class="card-title">Data Deposit</h3>
                    <div class="float-right">
                        <a href="{{ url('admin/karyawan/create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> 
                        </a>
                    </div>
                </div> --}}
                <div class="card-body">
                    <table id="datatables1" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Cabang</th>
                                <th>Kode Deposit</th>
                                <th>Nama Pelanggan</th>
                                <th>No HP</th>
                                <th>Alamat</th>
                                <th>Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inquery as $deposit)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $deposit->pemesananproduk->toko->nama_toko ?? 'Tidak Ada Toko' }}</td> <!-- Akses nama_toko -->
                                    <td>{{ $deposit->kode_dppemesanan }}</td>
                                    <td>{{ $deposit->pemesananproduk->nama_pelanggan ?? 'Tidak Ada Nama' }}</td> 
                                    <td>{{ $deposit->pemesananproduk->telp ?? 'Tidak Ada No HP' }}</td> 
                                    <td>{{ $deposit->pemesananproduk->alamat ?? 'Tidak Ada Alamat' }}</td> 
                                    <td>{{ 'Rp ' . number_format($deposit->dp_pemesanan, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
               
                </div>
            </div>
        </div>
    </section>
@endsection
