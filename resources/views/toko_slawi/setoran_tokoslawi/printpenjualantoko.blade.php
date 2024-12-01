@extends('layouts.app')

@section('title', 'Produks')

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
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
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
                   
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 12px">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 15%;">Kode</th>
                                <th style="width: 25%;">Nama Produk</th>
                                <th style="width: 10%;">Jumlah</th>
                                <th style="width: 15%;">Harga</th>
                                <th style="width: 20%;">Penjualan Kotor</th>
                                <th style="width: 10%;">Diskon</th>
                                <th style="width: 20%;">Penjualan Bersih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($finalResults as $produk)
                                <tr>
                                    <td class="text-center" style="width: 5%;">{{ $no++ }}</td>
                                    <td style="width: 15%;">{{ $produk['kode_lama'] }}</td>
                                    <td style="width: 25%;">{{ $produk['nama_produk'] }}</td>
                                    <td class="text-right" style="width: 10%;">{{ $produk['jumlah'] }}</td>
                                    <td class="text-right" style="width: 15%;">{{ number_format($produk['harga'], 0, ',', '.') }}</td>
                                    <td class="text-right" style="width: 20%;">{{ number_format($produk['penjualan_kotor'], 0, ',', '.') }}</td>
                                    <td class="text-right" style="width: 10%;">{{ number_format($produk['diskon'], 0, ',', '.') }}</td>
                                    <td class="text-right" style="width: 20%;">{{ number_format($produk['penjualan_bersih'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            @php
                                $totalJumlah = collect($finalResults)->sum('jumlah');
                                $grandTotal = collect($finalResults)->sum('penjualan_bersih');
                                $totalDiskon = collect($finalResults)->sum('diskon');
                                $totalKotor = collect($finalResults)->sum('penjualan_kotor');
                            @endphp
                            <tr>
                                <th colspan="3" style="text-align: right;">Total</th>
                                <th style="text-align: right;">{{ $totalJumlah }}</th>
                                <th></th>
                                <th style="text-align: right;">{{ number_format($totalKotor, 0, ',', '.') }}</th>
                                <th style="text-align: right;">{{ number_format($totalDiskon, 0, ',', '.') }}</th>
                                <th style="text-align: right;">{{ number_format($grandTotal, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                    

                    
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>


@endsection
