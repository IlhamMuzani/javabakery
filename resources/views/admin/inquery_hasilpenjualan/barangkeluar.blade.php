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
                    <h1 class="m-0">Inquery Hasil Penjualan BK </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Inquery Hasil Penjualan BK </li>
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
                        <select class="form-control" id="kategori1" name="kategori">
                            <option value="">- Pilih -</option>
                            <option value="masuk" {{ old('kategori1') == 'masuk' ? 'selected' : '' }}>Barang Masuk</option>
                            <option value="keluar" {{ old('kategori1') == 'keluar' ? 'selected' : '' }}>Barang Keluar</option>
                            <option value="retur" {{ old('kategori1') == 'retur' ? 'selected' : '' }}>Barang Retur</option>
                        </select>
                    </div>
                    <h3 class="card-title">Inquery Hasil Penjualan</h3>
                </div>
                <!-- /.card-header -->
                 
                <div class="card-body">
                    <form method="GET" id="form-action">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="toko_id" name="toko_id">
                                    <option value="">- Semua Toko -</option>
                                    @foreach($tokos as $toko)
                                        <option value="{{ $toko->id }}" {{ Request::get('toko_id') == $toko->id ? 'selected' : '' }}>{{ $toko->nama_toko }}</option>
                                    @endforeach
                                </select>
                                <label for="toko_id">(Pilih Toko)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="klasifikasi_id" name="klasifikasi_id">
                                    <option value="">- Semua Klasifikasi -</option>
                                    <!-- Populate klasifikasi options dynamically -->
                                    @foreach($klasifikasis as $klasifikasi)
                                        <option value="{{ $klasifikasi->id }}" {{ Request::get('klasifikasi_id') == $klasifikasi->id ? 'selected' : '' }}>
                                            {{ $klasifikasi->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="klasifikasi_id">(Pilih Klasifikasi)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_penjualan" name="tanggal_penjualan" type="date"
                                    value="{{ Request::get('tanggal_penjualan') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_penjualan">(Dari Tanggal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_akhir" name="tanggal_akhir" type="date"
                                    value="{{ Request::get('tanggal_akhir') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_akhir">(Sampai Tanggal)</label>
                            </div>
                        </div>
                    
                    </form>
                    
                    <form id="searchForm" method="GET">
                        <!-- Form fields go here -->
                    
                        <div class="col-md-3 mb-3">
                            <button type="button" class="btn btn-outline-primary btn-block" onclick="cari()">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </form>

                    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 13px">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Tanggal Penjualan</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Diskon</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1; // Inisialisasi nomor
                                $totalJumlah = 0;
                                $totalDiskon = 0;
                                $grandTotal = 0;
                            @endphp
                            @foreach($finalResults as $item)
                            @php
                                $totalJumlah += $item['jumlah'];
                                $totalDiskon += $item['diskon'];
                                $grandTotal += $item['total'];
                            @endphp
                            <tr>
                                <td class="text-center">{{ $no++ }}</td> <!-- Nomor urut -->
                                <td>{{ \Carbon\Carbon::parse($item['tanggal_penjualan'])->format('d/m/Y H:i') }}</td>
                                <td>{{ $item['kode_lama'] }}</td> <!-- Memanggil kode_lama dari tabel produk -->
                                <td>{{ $item['nama_produk'] }}</td>
                                <td style="text-align: right">{{ number_format($item['jumlah'], 0, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($item['harga'], 0, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($item['diskon'], 0, ',', '.') }}</td> <!-- Diskon 10% dari total yang memiliki diskon -->
                                <td style="text-align: right">{{ number_format($item['total'], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-center">Total</th>
                                <th style="text-align: right">{{ number_format($totalJumlah, 0, ',', '.') }}</th>
                                <th></th>
                                <th style="text-align: right">{{ number_format($totalDiskon, 0, ',', '.') }}</th>
                                <th style="text-align: right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
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


    <!-- /.card -->
    <script>
        var tanggalAwal = document.getElementById('tanggal_penjualan');
        var tanggalAkhir = document.getElementById('tanggal_akhir');
    
        tanggalAwal.addEventListener('change', function() {
            if (this.value == "") {
                tanggalAkhir.readOnly = true;
            } else {
                tanggalAkhir.readOnly = false;
                var today = new Date().toISOString().split('T')[0];
                tanggalAkhir.value = today;
                tanggalAkhir.setAttribute('min', this.value);
            }
        });
    
        function cari() {
            var form = document.getElementById('form-action');
            form.action = "{{ route('barangKeluar') }}";
            form.submit();
        }
    </script>

<script>
    document.getElementById('kategori1').addEventListener('change', function() {
        var selectedValue = this.value;

        if (selectedValue === 'masuk') {
            window.location.href = "{{ url('admin/inquery_hasilpenjualan') }}";
        } else if (selectedValue === 'keluar') {
            window.location.href = "{{ url('admin/inquery_hasilpenjualan/barangkeluar') }}";
        }else if (selectedValue === 'retur') {
            window.location.href = "{{ url('admin/inquery_hasilpenjualan/barangretur') }}";
        }
    });
</script>



@endsection
