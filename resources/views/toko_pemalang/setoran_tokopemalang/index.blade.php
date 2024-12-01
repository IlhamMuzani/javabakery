@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
    <div id="loadingSpinner" style="display: flex; align-items: center; justify-content: center; height: 100vh;">
        <i class="fas fa-spinner fa-spin" style="font-size: 3rem;"></i>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                document.getElementById("loadingSpinner").style.display = "none";
                document.getElementById("mainContent").style.display = "block";
                document.getElementById("mainContentSection").style.display = "block";
            }, 10); // Adjust the delay time as needed
        });
    </script>

    <!-- Content Header (Page header) -->
    <div class="content-header" style="display: none;" id="mainContent">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Setoran Tunai Penjualan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        {{-- <li class="breadcrumb-item active">Laporan penjualan Produk</li> --}}
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="display: none;" id="mainContentSection">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-check"></i> Success!
                    </h5>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-ban"></i> Error!
                    </h5>
                    {{ session('error') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <div class="float-right">
                        <a href="{{ url('toko_pemalang/setoran_tokopemalang/create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> 
                        </a>
                    </div>
       
                    <h3 class="card-title">Setoran Tunai Penjualan</h3>
                </div>
                 
                <div class="card-body">
                    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 10px">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Tanggal Setoran</th> <!-- Tambahkan kolom tanggal setoran -->
                                <th>Penjualan Kotor</th>
                                <th>Diskon Penjualan</th>
                                <th>Penjualan Bersih</th>
                                <th>Deposit Keluar</th>
                                <th>Deposit Masuk</th>
                                <th>Total Penjualan</th>
                                <th>Mesin EDC</th>
                                <th>Gobiz</th>
                                <th>Transfer</th>
                                <th>Qris</th>
                                <th>Total Setoran</th>
                                <th>Noiminal Setoran</th>
                                <th>Plus Minus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($setoranPenjualans as $index => $setoran)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $setoran->tanggal_setoran ? \Carbon\Carbon::parse($setoran->tanggal_setoran)->format('d-m-Y') : '-' }}</td> <!-- Menampilkan Tanggal Setoran -->
                                <td>{{ number_format($setoran->penjualan_kotor, 0, ',', '.') }}</td>
                                <td>{{ number_format($setoran->diskon_penjualan, 0, ',', '.') }}</td>
                                <td>{{ number_format($setoran->penjualan_bersih, 0, ',', '.') }}</td>
                                <td>{{ number_format($setoran->deposit_keluar, 0, ',', '.') }}</td>
                                <td>{{ number_format($setoran->deposit_masuk, 0, ',', '.') }}</td>
                                <td>{{ number_format($setoran->total_penjualan, 0, ',', '.') }}</td>
                                <td>{{ number_format($setoran->mesin_edc ?? 0, 0, ',', '.') }}</td>
                                <td>{{ number_format($setoran->gobiz ?? 0, 0, ',', '.') }}</td>
                                <td>{{ number_format($setoran->transfer ?? 0, 0, ',', '.') }}</td>
                                <td>{{ number_format($setoran->qris ?? 0, 0, ',', '.') }}</td>
                                <td>{{ number_format($setoran->total_setoran, 0, ',', '.') }}</td>
                                <td>{{ number_format($setoran->nominal_setoran, 0, ',', '.') }}</td>
                                <td>{{ number_format($setoran->plusminus, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    
                    
                    
                    <!-- Modal Loading -->
                    <div class="modal fade" id="modal-loading" tabindex="-1" role="dialog"
                        aria-labelledby="modal-loading-label" aria-hidden="true" data-backdrop="static">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
                                    <h4 class="mt-2">Sedang Menyimpan...</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>


@endsection
