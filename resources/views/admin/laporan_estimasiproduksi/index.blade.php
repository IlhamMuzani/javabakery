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
                    <h1 class="m-0">Laporan Estimasi Produksi All </h1>
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
                            <select class="form-control" id="kategori1" name="kategori">
                                <option value="">- Pilih -</option>
                                <option value="all" {{ old('kategori1') == 'all' ? 'selected' : '' }}>Semua Estimasi</option>
                                <option value="pemesanan" {{ old('kategori1') == 'pemesanan' ? 'selected' : '' }}>Estimasi Pemesanan</option>
                                <option value="permintaan" {{ old('kategori1') == 'permintaan' ? 'selected' : '' }}>Estimasi Permintaan</option>
                            </select>
                        </div>
       
                    <h3 class="card-title">Laporan Estimasi Produksi All</h3>
                </div>

                <!-- /.card-header -->
                 
                <div class="card-body">
                    <form method="GET" id="form-action">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal" name="tanggal" type="date" value="{{ Request::get('tanggal') }}" />
                                <label for="tanggal">(Dari Tanggal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_akhir" name="tanggal_akhir" type="date" value="{{ Request::get('tanggal_akhir') }}" />
                                <label for="tanggal_akhir">(Sampai Tanggal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="toko" name="toko_id">
                                    <option value="">- Semua Toko -</option>
                                    @foreach ($tokos as $toko)
                                        <option value="{{ $toko->id }}" {{ Request::get('toko_id') == $toko->id ? 'selected' : '' }}>{{ $toko->nama_toko }}</option>
                                    @endforeach
                                </select>
                                <label for="toko">(Pilih Toko)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="klasifikasi" name="klasifikasi_id" onchange="filterProduk()">
                                    <option value="">- Semua Divisi -</option>
                                    @foreach ($klasifikasis as $klasifikasi)
                                        <option value="{{ $klasifikasi->id }}" {{ Request::get('klasifikasi_id') == $klasifikasi->id ? 'selected' : '' }}>{{ $klasifikasi->nama }}</option>
                                    @endforeach
                                </select>
                                <label for="klasifikasi">(Pilih Divisi)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="produk" name="produk">
                                    <option value="">- Semua Produk -</option>
                                    @foreach ($produks as $produk)
                                        <option value="{{ $produk->id }}" data-klasifikasi="{{ $produk->klasifikasi_id }}" {{ Request::get('produk') == $produk->id ? 'selected' : '' }}>{{ $produk->nama_produk }}</option>
                                    @endforeach
                                </select>
                                <label for="produk">(Pilih Produk)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button type="submit" class="btn btn-outline-primary btn-block">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <button type="button" class="btn btn-primary btn-block" onclick="printReportpemesnanglobal()" target="_blank">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    
                   
                    <div class="container">

                        <table id="datatables66" class="table table-bordered" style="font-size: 13px">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Cabang</th>
                                    <th>Kode Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah Produk</th>
                                    <th>Jenis Transaksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Tampilkan data Pemesanan --}}
                                @foreach ($pemesanan as $pesanan)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ optional($pesanan->toko)->nama_toko ?? 'Toko tidak ditemukan' }}</td>
                                    <td>{{ $pesanan->kode_pemesanan }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pesanan->tanggal_kirim)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <ul>
                                            @foreach($pesanan->detailpemesananproduk as $detail)
                                                <li>{{ $detail->produk->nama_produk }} ({{ $detail->jumlah }})</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    
                                    <td>Pemesanan</td>
                                </tr>
                            @endforeach
                            
                        
                                {{-- Tampilkan data Permintaan --}}
                                @foreach ($permintaan as $permintaanItem)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + $pemesanan->count() }}</td>
                                    <td>
                                        @if ($permintaanItem->detailpermintaanproduks->isNotEmpty())
                                            {{ optional($permintaanItem->detailpermintaanproduks->first()->toko)->nama_toko ?? 'Toko tidak ditemukan' }}
                                        @else
                                            'Tidak ada detail permintaan'
                                        @endif
                                    </td>
                                                                        <td>{{ $permintaanItem->kode_permintaan }}</td>
                                    <td>{{ \Carbon\Carbon::parse($permintaanItem->created_at)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <ul>
                                            @foreach($permintaanItem->detailpermintaanproduks as $detail)
                                                <li>{{ $detail->produk->nama_produk }} ({{ $detail->jumlah }})</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    
                                    
                                    <td>Permintaan</td>
                                </tr>
                            @endforeach
                            
                            </tbody>
                        </table>
                        
                    </div>
                    
                    
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
        var tanggalAwal = document.getElementById('tanggal_kirim');
        var tanggalAkhir = document.getElementById('tanggal_akhir');
        if (tanggalAwal.value == "") {
            tanggalAkhir.readOnly = true;
        }
        tanggalAwal.addEventListener('change', function() {
            if (this.value == "") {
                tanggalAkhir.readOnly = true;
            } else {
                tanggalAkhir.readOnly = false;
            };
            tanggalAkhir.value = "";
            var today = new Date().toISOString().split('T')[0];
            tanggalAkhir.value = today;
            tanggalAkhir.setAttribute('min', this.value);
        });
        var form = document.getElementById('form-action')

        function cari() {
            form.action = "{{ url('admin/laporan_estimasiproduksi') }}";
            form.submit();
        }
    </script>

<script>
    function printReportpemesnanglobal() {
        const form = document.getElementById('form-action');
        const tokoSelect = document.getElementById('toko');
        const selectedToko = tokoSelect.value;

        // Cek apakah toko dipilih
        if (selectedToko) {
            // Jika toko dipilih, arahkan ke URL ini
            form.action = "{{ url('admin/printReportAll') }}";
        } else {
            // Jika tidak ada toko yang dipilih, arahkan ke URL ini
            form.action = "{{ url('admin/printReportAll') }}";
        }

        form.target = "_blank";
        form.submit();
    }
</script>


<script>
    document.getElementById('kategori1').addEventListener('change', function() {
        var selectedValue = this.value;

        if (selectedValue === 'all') {
            window.location.href = "{{ url('admin/laporan_estimasiproduksi') }}";
        } else if (selectedValue === 'pemesanan') {
            window.location.href = "{{ url('admin/indexpemesanan') }}";
        }else if (selectedValue === 'permintaan') {
            window.location.href = "{{ url('admin/indexpermintaan') }}";
        }

    });
</script>

<script>
    function filterProduk() {
        var klasifikasiId = document.getElementById('klasifikasi').value;
        var produkSelect = document.getElementById('produk');
        var produkOptions = produkSelect.options;
    
        for (var i = 0; i < produkOptions.length; i++) {
            var option = produkOptions[i];
            if (klasifikasiId === "" || option.getAttribute('data-klasifikasi') == klasifikasiId) {
                option.style.display = "block";
            } else {
                option.style.display = "none";
            }
        }
    
        // Reset the selected value of the product select box
        produkSelect.selectedIndex = 0;
    }
    </script>
@endsection
