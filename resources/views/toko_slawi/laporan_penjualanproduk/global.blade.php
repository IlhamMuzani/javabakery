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
                    <h1 class="m-0">Laporan Penjualan Produk Global</h1>
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
                            <option value="global" {{ old('kategori1') == 'global' ? 'selected' : '' }}>Laporan Penjualan Global</option>
                            <option value="rinci" {{ old('kategori1') == 'rinci' ? 'selected' : '' }}>Laporan Penjualan Rinci</option>
                        </select>
                    </div>
                    <h3 class="card-title">Laporan Penjualan Produk</h3>
                </div>
                <!-- /.card-header -->
                 
                <div class="card-body">
                    <form method="GET" id="form-action">
                        <div class="row">
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
                                <button type="button" class="btn btn-primary btn-block" onclick="printReportglobal()" target="_blank">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                            </div>
                        </div>
                    </form>
                   
                    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 13px">
                        <thead class="">
                            <tr>
                                <th class="text-center">No</th>
                                <th>Kode penjualan</th>
                                <th>Tanggal penjualan</th>
                                <th>Kasir</th>
                                <th>Divisi</th>
                                <th>Produk</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $grandTotal = 0;
                        @endphp
                        @foreach ($inquery as $item)
                            @php
                                $grandTotal += floatval($item->sub_total); // Konversi sub_total ke tipe float
                            @endphp
                            <tr class="dropdown"{{ $item->id }}>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->kode_penjualan }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_penjualan)->format('d/m/Y H:i') }}</td>
                                <td>{{ $item->kasir }}</td>
                                <td>
                                    @if ($item->detailpenjualanproduk->isNotEmpty())
                                        {{ $item->detailpenjualanproduk->pluck('produk.klasifikasi.nama')->implode(', ') }}
                                    @else
                                        tidak ada
                                    @endif
                                </td>
                                <td>
                                    @if ($item->detailpenjualanproduk->isNotEmpty())
                                        {{ $item->detailpenjualanproduk->pluck('nama_produk')->implode(', ') }}
                                    @else
                                        tidak ada
                                    @endif
                                </td>
                                <td>
                                    {{ Str::startsWith($item->sub_total, 'Rp') ? $item->sub_total : 'Rp ' . number_format((float)$item->sub_total, 0, ',', '.') }}
                                </td> 
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="6" class="text-right"><strong>Grand Total</strong></td>
                            <td>{{ 'Rp. ' . number_format($grandTotal, 0, ',', '.') }}</td>
                        </tr>
                        
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

    <!-- /.card -->
    <script>
        var tanggalAwal = document.getElementById('tanggal_penjualan');
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
            form.action = "{{ url('toko_slawi/indexglobal') }}";
            form.submit();
        }
    </script>

<script>
    function printReportglobal() {
    const form = document.getElementById('form-action');
    form.action = "{{ url('toko_slawi/printReportglobal') }}";
    form.target = "_blank";
    form.submit();
}

</script>
    

<script>
    document.getElementById('kategori1').addEventListener('change', function() {
        var selectedValue = this.value;

        if (selectedValue === 'global') {
            window.location.href = "{{ url('toko_slawi/indexglobal') }}";
        } else if (selectedValue === 'rinci') {
            window.location.href = "{{ url('toko_slawi/laporan_penjualanproduk') }}";
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