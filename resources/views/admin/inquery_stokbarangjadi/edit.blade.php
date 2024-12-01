@extends('layouts.app')

@section('title', 'Update Stok Barang Jadi')

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

                // Show the table based on klasifikasi_id
                var klasifikasiId = {{ $stok_barangjadi->klasifikasi_id ?? 'null' }};
                if (klasifikasiId) {
                    $('#produk-table-' + klasifikasiId).show(); // Show the selected produk table
                    $('#klasifikasi-select').val(klasifikasiId); // Set the selected value in the dropdown
                }
            }, 10); // Adjust the delay time as needed
        });
    </script>
    <!-- Content Header (Page header) -->
    <div class="content-header" style="display: none;" id="mainContent">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Update Stok Barang Jadi</h1>
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
                    {{-- Menampilkan pesan kesalahan dengan benar --}}
                    @foreach (session('error') as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <form action="{{ url('admin/inquery_stokbarangjadi/' . $stok_barangjadi->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="klasifikasi-select">Pilih Divisi:</label>
                            <select id="klasifikasi-select" class="form-control" name="klasifikasi_id">
                                <option value="">-- Pilih Divisi --</option>
                                @foreach ($klasifikasis as $klasifikasi)
                                    <option value="{{ $klasifikasi->id }}" {{ $klasifikasi->id == $stok_barangjadi->klasifikasi_id ? 'selected' : '' }}>
                                        {{ $klasifikasi->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" >
                            <label for="kode_input">Kode Input:</label>
                            <input type="text" id="kode_input" name="kode_input" class="form-control" value="{{ $stok_barangjadi->kode_input }}" readonly>
                        </div>

                        <div class="form-group" >
                            <label for="tanggal_input">Tanggal Input:</label>
                            <input type="date" id="tanggal_input" name="tanggal_input" class="form-control" value="{{ $stok_barangjadi->tanggal_input }}">
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Update Stok Barang Jadi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($klasifikasis as $klasifikasi)
                                            <tr class="produk-table" id="produk-table-{{ $klasifikasi->id }}">
                                                <td colspan="1">
                                                    <table class="table table-bordered" style="font-size: 13px;">
                                                        <div class="col-sm-12 text-right">
                                                            <input type="text" id="searchInput" class="form-control" placeholder="Cari produk..." style="display: inline-block; width: auto; margin-bottom: 10px;">
                                                        </div>
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Kode Produk</th>
                                                                <th>Produk</th>
                                                                <th>Stok</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($klasifikasi->produks as $produk)
                                                            @php
                                                                $jumlah = $stok_barangjadi->where('produk_id', $produk->id)->first()->stok ?? 0;
                                                            @endphp
                                                            <tr class="produk-row">
                                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                                <td class="kode-produk">{{ $produk->kode_produk }}</td>
                                                                <td class="nama-produk">{{ $produk->nama_produk }}</td>
                                                                <td>
                                                                    <input type="number" class="form-control" id="produk-{{ $produk->id }}" name="produk[{{ $produk->id }}][stok]" min="0" value="{{ $jumlah }}" style="width: 100px; height: 30px;">
                                                                </td>
                                                            </tr>
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
                    <div class="modal fade" id="modal-loading" tabindex="-1" role="dialog" aria-labelledby="modal-loading-label" aria-hidden="true" data-backdrop="static">
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
        $(document).ready(function() {
            $('#klasifikasi-select').change(function() {
                var klasifikasiId = $(this).val();
                $('.produk-table').hide(); // Hide all produk tables
                if (klasifikasiId) {
                    $('#produk-table-' + klasifikasiId).show(); // Show the selected produk table
                }
            });

            // Handle Enter key press on input fields
            $('input[type="number"]').on('keypress', function(e) {
                if (e.which == 13) { // Enter key pressed
                    e.preventDefault(); // Prevent form submission
                    var inputs = $('input[type="number"]');
                    var index = inputs.index(this);
                    
                    if (index + 1 < inputs.length) {
                        $(inputs[index + 1]).focus();
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('keyup', function() {
                var searchValue = searchInput.value.toLowerCase();
                var produkRows = document.querySelectorAll('.produk-row');
                
                produkRows.forEach(function(row) {
                    var kodeProduk = row.querySelector('.kode-produk').textContent.toLowerCase();
                    var namaProduk = row.querySelector('.nama-produk').textContent.toLowerCase();
                    if (kodeProduk.includes(searchValue) || namaProduk.includes(searchValue)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
