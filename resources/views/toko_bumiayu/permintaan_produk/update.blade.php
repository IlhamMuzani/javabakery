@extends('layouts.app')

@section('title', 'Update Permintaan Produk')

@section('content')
    <div id="loadingSpinner" style="display: flex; align-items: center; justify-content: center; height: 100vh;">
        <i class="fas fa-spinner fa-spin" style="font-size: 3rem;"></i>
    </div>
    <style>
        .klasifikasi-header {
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .klasifikasi-header:hover {
            background-color: #f0f0f0;
        }
        .klasifikasi-header.active {
            background-color: #e0e0e0;
        }
        .produk-table {
            display: none;
        }
    </style>
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
                    <h1 class="m-0">Update Permintaan Produk</h1>
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
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{ url('toko_slawi/permintaan_produk/'.$permintaanProduk->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="toko_id" value="{{ $permintaanProduk->toko_id }}">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Form Permintaan Produk</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($klasifikasis as $klasifikasi)
                                            <tr class="klasifikasi-header" data-klasifikasi-id="{{ $klasifikasi->id }}">
                                                <th>{{ $klasifikasi->nama }}</th>
                                            </tr>
                                            <tr class="produk-table" id="produk-table-{{ $klasifikasi->id }}">
                                                <td colspan="1">
                                                    <table class="table table-bordered" style="font-size: 13px;">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Kode Produk</th>
                                                                <th>Produk</th>
                                                                <th>Jumlah</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($klasifikasi->produks as $produk)
                                                                @php
                                                                    $jumlah = $detailPermintaanProduk->where('produk_id', $produk->id)->first()->jumlah ?? 0;
                                                                @endphp
                                                                <tr>
                                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                                    <td>{{ $produk->kode_produk }}</td>
                                                                    <td>{{ $produk->nama_produk }}</td>
                                                                    <td>
                                                                        <input type="number" class="form-control" id="produk-{{ $produk->id }}" name="produk[{{ $produk->id }}][jumlah]" min="0" style="width: 100px; height: 30px;" value="{{ $jumlah }}">
                                                                    </td>
                                                                @endforeach
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                    
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

    <script>
        $(document).ready(function(){
            $('.klasifikasi-header').click(function(){
                var klasifikasiId = $(this).data('klasifikasi-id');
                var $produkTable = $('#produk-table-' + klasifikasiId);
    
                if ($produkTable.is(':visible')) {
                    $produkTable.hide();
                    $(this).removeClass('active');
                } else {
                    // Hide all produk tables and remove active class from all headers
                    $('.produk-table').hide();
                    $('.klasifikasi-header').removeClass('active');
    
                    // Show the selected produk table and add active class to the clicked header
                    $produkTable.show();
                    $(this).addClass('active');
                }
            });
    
            // Handle Enter key press on input fields
            $('input[type="number"]').on('keypress', function(e) {
                if (e.which == 13) { // Enter key pressed
                    e.preventDefault(); // Prevent form submission
    
                    // Get the next input element
                    var inputs = $('input[type="number"]');
                    var index = inputs.index(this);
                    
                    if (index + 1 < inputs.length) {
                        // Focus on the next input
                        $(inputs[index + 1]).focus();
                    } else {
                        // Optionally handle case where there's no next input
                        // e.g., focus on a specific element or alert user
                    }
                }
            });
        });
    </script>
@endsection
