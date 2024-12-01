@extends('layouts.app')

@section('title', 'Data Stok Toko')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Stok Pesanan Toko Pemalang</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Data Stok Pesanan Pemalang</li>
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
                    <div class="float-right">
                        <a href="{{ url('toko_pemalang/stokpesanan_tokopemalang/create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                    <form action="{{ route('stokpesanan_tokopemalang.deleteAll') }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus seluruh data stok?')">
                            <i class="fas fa-trash"></i> Hapus Semua
                        </button>
                    </form>
                </div>
            
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="GET" id="form-action">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="klasifikasi" name="klasifikasi_id" onchange="filterSubKlasifikasi()">
                                    <option value="">- Semua Divisi -</option>
                                    @foreach ($klasifikasis as $klasifikasi)
                                        <option value="{{ $klasifikasi->id }}" {{ Request::get('klasifikasi_id') == $klasifikasi->id ? 'selected' : '' }}>
                                            {{ $klasifikasi->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="klasifikasi">(Pilih Divisi)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="subklasifikasi" name="subklasifikasi_id">
                                    <option value="">- Semua Sub Klasifikasi -</option>
                                    @foreach ($subklasifikasis as $subklasifikasi)
                                        <option value="{{ $subklasifikasi->id }}" data-klasifikasi="{{ $subklasifikasi->klasifikasi_id }}" {{ Request::get('subklasifikasi_id') == $subklasifikasi->id ? 'selected' : '' }}>
                                            {{ $subklasifikasi->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="subklasifikasi">(Pilih Sub Klasifikasi)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button type="submit" class="btn btn-outline-primary btn-block">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </form>
            
                    {{-- <table id="data" class="table table-bordered" style="font-size: 13px"> --}}
                    <table id="datatables1" class="table table-bordered" style="font-size: 13px">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Sub Total</th>
                                <th>Aksi</th> 
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produkWithStok as $produk)
                            <tr>
                                <form action="{{ route('stokpesanan_tokopemalang.update', $produk->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $produk->kode_lama }}</td>
                                    <td>{{ $produk->nama_produk }}</td>
                                    <td style="text-align: right">
                                        <input type="number" name="jumlah" value="{{ $produk->jumlah }}" class="form-control" style="width: 80px; text-align: right;">
                                    </td>
                                    <td style="text-align: right">{{ number_format($produk->harga, 0, ',', '.') }}</td>
                                    <td style="text-align: right">{{ number_format($produk->subTotal, 0, ',', '.') }}</td> <!-- Sub Total -->
                                    <td>
                                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-save"></i></button>
                                    </td>
                                </form>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-center">Total</th>
                                <th style="text-align: right">{{ $totalStok }}</th>
                                <th></th>
                                <th style="text-align: right">{{ 'Rp. ' . number_format($totalSubTotal, 0, ',', '.') }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            
        </div>
    </section>
    
    <script>
        function filterSubKlasifikasi() {
            var klasifikasiId = document.getElementById('klasifikasi').value;
            var subKlasifikasiSelect = document.getElementById('subklasifikasi');
            var subKlasifikasiOptions = subKlasifikasiSelect.options;
    
            // Show all options initially
            for (var i = 0; i < subKlasifikasiOptions.length; i++) {
                var option = subKlasifikasiOptions[i];
                if (klasifikasiId === "" || option.getAttribute('data-klasifikasi') == klasifikasiId) {
                    option.style.display = "block";
                } else {
                    option.style.display = "none";
                }
            }
    
            // Automatically select the first valid option if any
            var foundValidOption = false;
            for (var i = 1; i < subKlasifikasiOptions.length; i++) { // Skip the first option (default)
                var option = subKlasifikasiOptions[i];
                if (option.style.display === "block") {
                    subKlasifikasiSelect.selectedIndex = i;
                    foundValidOption = true;
                    break;
                }
            }
            if (!foundValidOption) {
                subKlasifikasiSelect.selectedIndex = 0; // Select default if no valid option found
            }
        }
    
        // Initialize the filter on page load to show the correct subklasifikasi options
        document.addEventListener('DOMContentLoaded', function() {
            filterSubKlasifikasi();
        });
    </script>

{{-- <script>
    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Cegah form dari pengiriman standar

            var form = $(this);
            var url = form.attr('action');
            var data = form.serialize(); // Ambil semua input form

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            timer: 1000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan: ' + response.message,
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan. Silakan coba lagi.',
                    });
                }
            });
        });
    });
</script> --}}
@endsection
